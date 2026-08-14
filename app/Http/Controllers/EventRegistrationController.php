<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\EventoRegistro;
use App\Models\MetodoPago;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventRegistrationController extends Controller
{
    private const FIXED_PAYMENT_CODES = [
        'efectivo',
        'transferencia',
        'tarjeta_online',
    ];

    public function promptLogin(Evento $evento)
    {
        session()->put('url.intended', route('evento', $evento));

        return redirect()
            ->route('login')
            ->with('status', 'Debes iniciar sesión para inscribirte al evento. Si aún no tienes cuenta, primero regístrate.');
    }

    public function show($id)
    {
        $query = Evento::query();

        if (! auth()->check() || ! auth()->user()->can('events.manage')) {
            $query->published();
        }

        $evento = $query->findOrFail($id);

        return redirect()
            ->route('evento', $evento)
            ->with('status', 'Completa tu inscripción directamente desde la ventana de pago del evento.');
    }

    public function quickRegister(Request $request, Evento $evento)
    {
        $user = Auth::user()?->loadMissing('persona.tipoPerfil');
        $persona = $user?->persona;

        if (! $persona) {
            return redirect()
                ->route('evento', $evento)
                ->with('error', 'Tu cuenta aún no tiene un perfil personal completo para inscribirse en eventos.');
        }

        if (! $persona->IdTipoPerfil) {
            return redirect()
                ->route('evento', $evento)
                ->with('error', 'Tu perfil no tiene tipo asignado. Actualiza tus datos antes de inscribirte.');
        }

        $evento->loadMissing(['precios.tipoPerfil', 'precios.moneda']);

        $existingRegistration = EventoRegistro::query()
            ->where('evento_id', $evento->id)
            ->where('persona_id', $persona->id)
            ->first();

        if ($existingRegistration) {
            return redirect()
                ->route('evento', $evento)
                ->with('status', 'Ya cuentas con una inscripción registrada para este evento.');
        }

        $metodoPago = null;
        $proofPath = null;
        $proofName = null;
        $proofMime = null;

        if ($evento->tipo_acceso === 'pagada') {
            $validated = $request->validate([
                'metodo_pago_id' => ['required', 'exists:metodos_pago,id'],
                'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
            ], [
                'payment_proof.uploaded' => 'No se pudo cargar el comprobante. Intenta nuevamente con una imagen o PDF válido.',
                'payment_proof.file' => 'El comprobante debe ser un archivo válido.',
                'payment_proof.mimes' => 'El comprobante debe estar en formato JPG, PNG o PDF.',
                'payment_proof.max' => 'El comprobante no debe superar los 10 MB.',
            ], [
                'metodo_pago_id' => 'método de pago',
                'payment_proof' => 'comprobante de pago',
            ]);

            $metodoPago = MetodoPago::query()
                ->active()
                ->whereIn('codigo', self::FIXED_PAYMENT_CODES)
                ->find($validated['metodo_pago_id']);

            if (! $metodoPago) {
                return redirect()
                    ->route('evento', $evento)
                    ->with('error', 'El método de pago seleccionado ya no está disponible.');
            }

            if ($metodoPago->isCard()) {
                return redirect()
                    ->route('evento', $evento)
                    ->with('error', 'El pago con tarjeta todavía no está habilitado. Usa efectivo o transferencia.');
            }

            if ($metodoPago->isTransfer() && ! $request->hasFile('payment_proof')) {
                return redirect()
                    ->route('evento', $evento)
                    ->with('error', 'Debes subir el comprobante para completar la inscripción por transferencia.');
            }

            if (
                $metodoPago->isTransfer()
                && (! filled($metodoPago->banco_nombre) || ! filled($metodoPago->numero_cuenta) || ! filled($metodoPago->titular_cuenta))
            ) {
                return redirect()
                    ->route('evento', $evento)
                    ->with('error', 'La transferencia bancaria todavía no está configurada completamente para este evento.');
            }

            if ($request->hasFile('payment_proof')) {
                $proofFile = $request->file('payment_proof');
                $proofPath = $proofFile->store('payment-proofs/eventos/' . $evento->id, 'public');
                $proofName = $proofFile->getClientOriginalName();
                $proofMime = $proofFile->getMimeType();
            }
        }

        $price = $this->resolvePriceForProfile($evento, (int) $persona->IdTipoPerfil);

        DB::transaction(function () use ($evento, $persona, $metodoPago, $price, $proofPath, $proofName, $proofMime) {
            EventoRegistro::query()->create([
                'evento_id' => $evento->id,
                'persona_id' => $persona->id,
                'tipoperfil_id' => $persona->IdTipoPerfil,
                'metodo_pago_id' => $metodoPago?->id,
                'precio_aplicado' => $price['amount'],
                'detalle_precio' => $price['label'],
                'estado' => $evento->tipo_acceso === 'pagada' ? 'pendiente_pago' : 'registrado',
                'estado_pago' => match (true) {
                    $evento->tipo_acceso !== 'pagada' => 'no_aplica',
                    $metodoPago?->isCash() => 'pendiente_efectivo',
                    $metodoPago?->requiere_api => 'pendiente_pasarela',
                    default => 'pendiente_confirmacion',
                },
                'referencia_pago' => $evento->tipo_acceso === 'pagada'
                    ? 'PAY-' . Str::upper(Str::random(10))
                    : null,
                'payload_pago' => $evento->tipo_acceso === 'pagada'
                    ? [
                        'metodo' => $metodoPago?->nombre,
                        'tipo' => $metodoPago?->tipo,
                        'proveedor' => $metodoPago?->proveedor,
                        'documento_cobro_tipo' => $metodoPago?->documento_cobro_tipo,
                        'banco_nombre' => $metodoPago?->banco_nombre,
                        'numero_cuenta' => $metodoPago?->numero_cuenta,
                        'titular_cuenta' => $metodoPago?->titular_cuenta,
                    ]
                    : null,
                'comprobante_pago_path' => $proofPath,
                'comprobante_pago_nombre' => $proofName,
                'comprobante_pago_mime' => $proofMime,
            ]);
        });

        return redirect()
            ->route('evento', $evento)
            ->with(
                'status',
                match (true) {
                    $evento->tipo_acceso !== 'pagada' => 'Tu inscripción al evento se realizó correctamente.',
                    $metodoPago?->isCash() => 'Tu inscripción fue registrada. El cobro quedó pendiente para pago en efectivo dentro del evento.',
                    $metodoPago?->isTransfer() => 'Tu inscripción fue registrada y el comprobante quedó pendiente de validación.',
                    default => 'Tu inscripción fue creada correctamente y quedó pendiente de pago.',
                }
            );
    }

    private function resolvePriceForProfile(Evento $evento, int $profileId): array
    {
        if ($evento->tipo_acceso !== 'pagada') {
            return [
                'amount' => 0,
                'label' => 'Evento gratuito',
                'currency_symbol' => null,
                'currency_code' => null,
            ];
        }

        $today = now()->toDateString();
        $prices = $evento->precios->where('IdTipoPerfil', $profileId)->values();

        $range = $prices->first(function ($price) use ($today) {
            return ! $price->es_precio_evento_dia
                && $price->fecha_inicio?->format('Y-m-d') <= $today
                && $price->fecha_fin?->format('Y-m-d') >= $today;
        });

        if ($range) {
            return [
                'amount' => (float) $range->precio,
                'label' => $range->categoria_nombre ?: 'Rango configurado',
                'currency_symbol' => $range->moneda?->simbolo,
                'currency_code' => $range->moneda?->codigo,
            ];
        }

        $eventDayPrice = $prices->firstWhere('es_precio_evento_dia', true);

        return [
            'amount' => (float) ($eventDayPrice->precio ?? 0),
            'label' => $eventDayPrice?->categoria_nombre ?: 'Precio del día del evento',
            'currency_symbol' => $eventDayPrice?->moneda?->simbolo,
            'currency_code' => $eventDayPrice?->moneda?->codigo,
        ];
    }
}
