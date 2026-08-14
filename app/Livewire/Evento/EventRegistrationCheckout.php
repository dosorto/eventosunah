<?php

namespace App\Livewire\Evento;

use App\Models\Evento;
use App\Models\EventoRegistro;
use App\Models\MetodoPago;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Services\ProfileDirectoryLookupService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventRegistrationCheckout extends Component
{
    use WithFileUploads;

    public Evento $evento;
    public ?EventoRegistro $existingRegistration = null;
    public string $tipoperfil_id = '';
    public string $lookup_identifier = '';
    public bool $lookupResolved = false;
    public bool $manualEntryEnabled = false;
    public string $lookupMessage = '';
    public bool $registrationSuccess = false;
    public string $dni = '';
    public string $numeroCuenta = '';
    public string $numeroEmpleado = '';
    public string $nombre = '';
    public string $apellido = '';
    public string $correo = '';
    public string $correoInstitucional = '';
    public string $fechaNacimiento = '';
    public string $sexo = '';
    public string $direccion = '';
    public string $telefono = '';
    public string $IdNacionalidad = '';
    public string $metodo_pago_id = '';
    public array $paymentData = [];
    public $paymentProof = null;

    public function mount(Evento $evento): void
    {
        $this->evento = $evento->load(['modalidad', 'localidad', 'precios.tipoPerfil', 'precios.moneda']);

        $requestedMethod = request()->query('metodo');

        if (filled($requestedMethod)) {
            $metodo = MetodoPago::query()->active()->find($requestedMethod);

            if ($metodo) {
                $this->metodo_pago_id = (string) $metodo->id;
            }
        }

        $this->ensureAuthenticatedUserRole();
        $this->prefillFromAuthenticatedPersona();
        $this->loadExistingRegistration();
    }

    public function render()
    {
        return view('livewire.Evento.event-registration-checkout', [
            'tiposPerfil' => Tipoperfil::query()->orderBy('tipoperfil')->get(),
            'nacionalidades' => Nacionalidad::query()->orderBy('nombreNacionalidad')->get(),
            'selectedProfile' => $this->selectedProfile(),
            'pricePreview' => $this->pricePreview(),
            'metodosPago' => MetodoPago::query()->active()->orderBy('orden')->orderBy('nombre')->get(),
            'selectedPaymentMethod' => $this->selectedPaymentMethod(),
            'paymentFields' => $this->paymentFields(),
        ]);
    }

    public function updatedTipoperfilId(): void
    {
        $this->resetLookupState();
        $this->clearIdentityFields();
        $this->prefillFromAuthenticatedPersona();
    }

    public function updatedMetodoPagoId(): void
    {
        $this->paymentData = [];
        $this->paymentProof = null;
    }

    public function lookupProfileData(ProfileDirectoryLookupService $lookupService): void
    {
        $profile = $this->selectedProfile();

        if (! $profile) {
            $this->addError('tipoperfil_id', 'Selecciona un tipo de perfil.');
            return;
        }

        $this->validate([
            'tipoperfil_id' => 'required|exists:tipoperfils,id',
            'lookup_identifier' => 'required|string|max:100',
        ]);

        $result = $lookupService->lookup($profile, trim($this->lookup_identifier));

        if (! $result['success']) {
            $this->lookupResolved = false;
            $this->lookupMessage = $result['message'];
            $this->manualEntryEnabled = ! $profile->requiere_api && $profile->permite_registro_manual;

            if ($profile->requiere_api) {
                $this->addError('lookup_identifier', $result['message']);
            }

            return;
        }

        $this->fillFieldsFromLookup($result['data'], $profile);
        $this->lookupResolved = true;
        $this->manualEntryEnabled = ! $profile->requiere_api && $profile->permite_registro_manual;
        $this->lookupMessage = $result['message'];
    }

    public function enableManualEntry(): void
    {
        $profile = $this->selectedProfile();

        if (! $profile || ! $profile->permite_registro_manual) {
            return;
        }

        $this->manualEntryEnabled = true;
        $this->lookupMessage = 'Completa manualmente la información para continuar con la inscripción.';
    }

    public function register(): void
    {
        if ($this->existingRegistration) {
            return;
        }

        $profile = $this->selectedProfile();

        if (! $profile) {
            $this->addError('tipoperfil_id', 'Selecciona un tipo de perfil.');
            return;
        }

        if ($profile->requiere_api && ! $this->lookupResolved) {
            $this->addError('lookup_identifier', 'Primero debes consultar la información desde la API para este tipo de perfil.');
            return;
        }

        if (! $profile->requiere_api && ! $this->lookupResolved && ! $this->manualEntryEnabled) {
            $this->manualEntryEnabled = true;
        }

        $rules = [
            'tipoperfil_id' => 'required|exists:tipoperfils,id',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'correoInstitucional' => 'nullable|email|max:255',
            'fechaNacimiento' => 'nullable|date',
            'sexo' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:30',
            'IdNacionalidad' => 'required|exists:nacionalidads,id',
        ];

        $identifierField = $profile->tipo_identificador;

        if ($identifierField === 'dni') {
            $rules['dni'] = 'required|string|max:50';
        }

        if ($identifierField === 'numeroCuenta') {
            $rules['numeroCuenta'] = 'required|string|max:50';
        }

        if ($identifierField === 'numeroEmpleado') {
            $rules['numeroEmpleado'] = 'required|string|max:50';
        }

        if ($this->evento->tipo_acceso === 'pagada') {
            $rules['metodo_pago_id'] = 'required|exists:metodos_pago,id';
        }

        foreach ($this->paymentFields() as $field) {
            $fieldName = (string) ($field['name'] ?? '');

            if (! filled($fieldName)) {
                continue;
            }

            $rules['paymentData.' . $fieldName] = ! empty($field['required']) ? 'required|string|max:255' : 'nullable|string|max:255';
        }

        if ($this->selectedPaymentMethod()?->requiresProof()) {
            $rules['paymentProof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:10240';
        }

        $validated = $this->validate($rules);
        $price = $this->resolvePriceForProfile((int) $profile->id);
        $paymentProofFile = $this->paymentProof;

        DB::transaction(function () use ($profile, $validated, $price, $paymentProofFile) {
            $persona = $this->resolvePersona($profile, $validated);

            $existingRegistration = EventoRegistro::query()
                ->where('evento_id', $this->evento->id)
                ->where('persona_id', $persona->id)
                ->first();

            if ($existingRegistration) {
                $this->existingRegistration = $existingRegistration->load('metodoPago', 'tipoPerfil');
                return;
            }

            $metodoPago = $this->selectedPaymentMethod();
            $estado = $this->evento->tipo_acceso === 'pagada' ? 'pendiente_pago' : 'registrado';
            $estadoPago = match (true) {
                $this->evento->tipo_acceso !== 'pagada' => 'no_aplica',
                $metodoPago?->requiere_api => 'pendiente_pasarela',
                default => 'pendiente_confirmacion',
            };

            $proofPath = null;
            $proofName = null;
            $proofMime = null;

            if ($paymentProofFile) {
                $proofPath = $paymentProofFile->store('payment-proofs/eventos/' . $this->evento->id, 'public');
                $proofName = $paymentProofFile->getClientOriginalName();
                $proofMime = $paymentProofFile->getMimeType();
            }

            $registro = EventoRegistro::query()->create([
                'evento_id' => $this->evento->id,
                'persona_id' => $persona->id,
                'tipoperfil_id' => $profile->id,
                'metodo_pago_id' => $metodoPago?->id,
                'precio_aplicado' => $price['amount'],
                'detalle_precio' => $price['label'],
                'estado' => $estado,
                'estado_pago' => $estadoPago,
                'referencia_pago' => $this->buildPaymentReference(),
                'payload_pago' => $this->evento->tipo_acceso === 'pagada' ? $this->normalizedPaymentPayload($metodoPago) : null,
                'comprobante_pago_path' => $proofPath,
                'comprobante_pago_nombre' => $proofName,
                'comprobante_pago_mime' => $proofMime,
            ]);

            $this->existingRegistration = $registro->load('metodoPago', 'tipoPerfil');
            $this->registrationSuccess = true;
            $this->lookupMessage = $this->evento->tipo_acceso === 'pagada'
                ? 'La inscripción quedó registrada en estado pendiente de pago.'
                : 'La inscripción al evento se realizó correctamente.';
            $this->paymentProof = null;
        });
    }

    private function resolvePersona(Tipoperfil $profile, array $validated): Persona
    {
        $identifierField = $profile->tipo_identificador;
        $identifierValue = match ($identifierField) {
            'numeroCuenta' => trim($validated['numeroCuenta'] ?? ''),
            'numeroEmpleado' => trim($validated['numeroEmpleado'] ?? ''),
            default => trim($validated['dni'] ?? ''),
        };

        $query = Persona::query();

        if (filled($identifierField) && filled($identifierValue)) {
            $query->where($identifierField, $identifierValue);
        } elseif (Auth::user()?->persona) {
            $query->whereKey(Auth::user()->persona->id);
        } else {
            $query->where('correo', trim($validated['correo']));
        }

        $persona = $query->first() ?? new Persona();

        $persona->fill([
            'IdUsuario' => Auth::id() ?: $persona->IdUsuario,
            'dni' => trim($validated['dni'] ?? $this->fallbackDni($profile)),
            'nombre' => trim($validated['nombre']),
            'apellido' => trim($validated['apellido']),
            'correo' => trim($validated['correo']),
            'correoInstitucional' => filled($validated['correoInstitucional']) ? trim($validated['correoInstitucional']) : null,
            'fechaNacimiento' => filled($validated['fechaNacimiento']) ? $validated['fechaNacimiento'] : null,
            'sexo' => trim($validated['sexo']),
            'direccion' => trim($validated['direccion']),
            'telefono' => trim($validated['telefono']),
            'numeroCuenta' => filled($validated['numeroCuenta'] ?? null) ? trim($validated['numeroCuenta']) : null,
            'numeroEmpleado' => filled($validated['numeroEmpleado'] ?? null) ? trim($validated['numeroEmpleado']) : null,
            'IdNacionalidad' => $validated['IdNacionalidad'],
            'IdTipoPerfil' => $profile->id,
        ]);

        if (! $persona->exists) {
            $persona->created_by = Auth::id() ?? 0;
        }

        $persona->save();

        return $persona;
    }

    private function resolvePriceForProfile(int $profileId): array
    {
        if ($this->evento->tipo_acceso !== 'pagada') {
            return [
                'amount' => 0,
                'label' => 'Evento gratuito',
                'currency_symbol' => null,
                'currency_code' => null,
            ];
        }

        $today = now()->toDateString();
        $prices = $this->evento->precios->where('IdTipoPerfil', $profileId)->values();

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

    private function selectedProfile(): ?Tipoperfil
    {
        return filled($this->tipoperfil_id) ? Tipoperfil::query()->find($this->tipoperfil_id) : null;
    }

    private function selectedPaymentMethod(): ?MetodoPago
    {
        return filled($this->metodo_pago_id) ? MetodoPago::query()->active()->find($this->metodo_pago_id) : null;
    }

    private function paymentFields(): array
    {
        return $this->selectedPaymentMethod()?->checkout_fields_json ?? [];
    }

    private function pricePreview(): array
    {
        $profile = $this->selectedProfile();
        return $profile
            ? $this->resolvePriceForProfile((int) $profile->id)
            : ['amount' => null, 'label' => null, 'currency_symbol' => null, 'currency_code' => null];
    }

    private function fillFieldsFromLookup(array $data, Tipoperfil $profile): void
    {
        $this->dni = trim((string) ($data['dni'] ?? $this->fallbackDni($profile)));
        $this->numeroCuenta = trim((string) ($data['numeroCuenta'] ?? ''));
        $this->numeroEmpleado = trim((string) ($data['numeroEmpleado'] ?? ''));
        $this->nombre = trim((string) ($data['nombre'] ?? ''));
        $this->apellido = trim((string) ($data['apellido'] ?? ''));
        $this->correo = trim((string) ($data['correo'] ?? Auth::user()?->email ?? ''));
        $this->correoInstitucional = trim((string) ($data['correoInstitucional'] ?? ''));
        $this->fechaNacimiento = trim((string) ($data['fechaNacimiento'] ?? ''));
        $this->sexo = trim((string) ($data['sexo'] ?? ''));
        $this->direccion = trim((string) ($data['direccion'] ?? ''));
        $this->telefono = trim((string) ($data['telefono'] ?? ''));
    }

    private function prefillFromAuthenticatedPersona(): void
    {
        $persona = Auth::user()?->persona;

        if ($persona) {
            $this->tipoperfil_id = (string) ($persona->IdTipoPerfil ?? $this->tipoperfil_id);
            $this->dni = $persona->dni ?? $this->dni;
            $this->numeroCuenta = $persona->numeroCuenta ?? $this->numeroCuenta;
            $this->numeroEmpleado = $persona->numeroEmpleado ?? $this->numeroEmpleado;
            $this->nombre = $persona->nombre ?? $this->nombre;
            $this->apellido = $persona->apellido ?? $this->apellido;
            $this->correo = $persona->correo ?? (Auth::user()->email ?? $this->correo);
            $this->correoInstitucional = $persona->correoInstitucional ?? $this->correoInstitucional;
            $this->fechaNacimiento = $persona->fechaNacimiento ? Carbon::parse($persona->fechaNacimiento)->format('Y-m-d') : $this->fechaNacimiento;
            $this->sexo = $persona->sexo ?? $this->sexo;
            $this->direccion = $persona->direccion ?? $this->direccion;
            $this->telefono = $persona->telefono ?? $this->telefono;
            $this->IdNacionalidad = (string) ($persona->IdNacionalidad ?? $this->IdNacionalidad);
            return;
        }

        $this->correo = Auth::user()?->email ?? $this->correo;

        if (! filled($this->nombre) && filled(Auth::user()?->name)) {
            $parts = preg_split('/\s+/', trim((string) Auth::user()->name)) ?: [];
            $this->nombre = $parts[0] ?? '';
            $this->apellido = trim(implode(' ', array_slice($parts, 1)));
        }
    }

    private function clearIdentityFields(): void
    {
        $this->lookup_identifier = '';
        $this->dni = '';
        $this->numeroCuenta = '';
        $this->numeroEmpleado = '';
        $this->nombre = '';
        $this->apellido = '';
        $this->correo = Auth::user()?->email ?? '';
        $this->correoInstitucional = '';
        $this->fechaNacimiento = '';
        $this->sexo = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->IdNacionalidad = '';
    }

    private function resetLookupState(): void
    {
        $this->lookupResolved = false;
        $this->manualEntryEnabled = false;
        $this->lookupMessage = '';
    }

    private function fallbackDni(Tipoperfil $profile): string
    {
        if ($profile->tipo_identificador === 'dni' && filled($this->lookup_identifier)) {
            return trim($this->lookup_identifier);
        }

        return 'TMP-' . Str::upper(Str::random(12));
    }

    private function loadExistingRegistration(): void
    {
        $personaId = Auth::user()?->persona?->id;

        if (! $personaId) {
            return;
        }

        $this->existingRegistration = EventoRegistro::query()
            ->with(['metodoPago', 'tipoPerfil'])
            ->where('evento_id', $this->evento->id)
            ->where('persona_id', $personaId)
            ->first();
    }

    private function buildPaymentReference(): ?string
    {
        if ($this->evento->tipo_acceso !== 'pagada') {
            return null;
        }

        return 'EVT-' . $this->evento->id . '-' . Str::upper(Str::random(8));
    }

    private function normalizedPaymentPayload(?MetodoPago $metodoPago): ?array
    {
        if ($this->evento->tipo_acceso !== 'pagada') {
            return null;
        }

        return [
            'metodo' => $metodoPago?->nombre,
            'tipo' => $metodoPago?->tipo,
            'proveedor' => $metodoPago?->proveedor,
            'requiere_api' => (bool) $metodoPago?->requiere_api,
            'requiere_comprobante' => (bool) $metodoPago?->requiresProof(),
            'campos' => $this->paymentData,
        ];
    }

    private function ensureAuthenticatedUserRole(): void
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'roles') || ! method_exists($user, 'assignRole')) {
            return;
        }

        if ($user->roles()->count() === 0) {
            $user->assignRole('participante');
        }
    }
}
