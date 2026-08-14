<?php

namespace App\Http\Controllers\Pago;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\EventoRegistro;
use App\Models\FacturacionConfig;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class PagoDocumentoController extends Controller
{
    public function receipt(Evento $evento, EventoRegistro $registro): Response
    {
        $this->authorizeDocument($evento, $registro);
        abort_unless($registro->estado_pago === 'pagado', 404);

        $document = $this->receiptPreviewData($evento, $registro);
        $pdf = PDF::loadView('pdf.payments.receipt', [
            'document' => $document,
        ])->setPaper('letter');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document['filename'] . '"',
        ]);
    }

    public function invoice(Evento $evento, EventoRegistro $registro): Response
    {
        $this->authorizeDocument($evento, $registro);
        abort_unless($registro->estado_pago === 'pagado', 404);

        $document = $this->invoicePreviewData($evento, $registro, true);
        abort_unless($document['enabled'] ?? false, 404);

        $pdf = PDF::loadView('pdf.payments.invoice', [
            'document' => $document,
        ])->setPaper('letter');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document['filename'] . '"',
        ]);
    }

    public function refundReceipt(Evento $evento, EventoRegistro $registro): Response
    {
        $this->authorizeDocument($evento, $registro);
        abort_unless($registro->estado_pago === 'reembolsado', 404);

        $document = $this->refundReceiptPreviewData($evento, $registro);
        $pdf = PDF::loadView('pdf.payments.refund-receipt', [
            'document' => $document,
        ])->setPaper('letter');

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $document['filename'] . '"',
        ]);
    }

    private function authorizeDocument(Evento $evento, EventoRegistro $registro): void
    {
        abort_unless(Auth::user()?->can('events.manage'), 403);
        abort_unless((int) $registro->evento_id === (int) $evento->id, 404);
    }

    private function receiptPreviewData(Evento $evento, EventoRegistro $registro): array
    {
        $registro->loadMissing('evento.precios.moneda');

        $participantName = trim(($registro->persona?->nombre ?? '') . ' ' . ($registro->persona?->apellido ?? '')) ?: 'Participante';
        $issuedAt = $registro->pagado_en ?? now();
        $receiptNumber = sprintf('REC-%s-%06d', $issuedAt->format('Y'), $registro->id);

        return [
            'enabled' => true,
            'filename' => Str::slug('recibo-' . $evento->nombreevento . '-' . $participantName) . '.pdf',
            'document_number' => $receiptNumber,
            'issued_at' => $issuedAt,
            'participant_name' => $participantName,
            'participant_identity' => $registro->persona?->dni ?: 'Sin identidad',
            'participant_email' => $registro->persona?->correo ?: 'Sin correo',
            'event_name' => $evento->nombreevento,
            'event_organizer' => $evento->organizador,
            'event_dates' => $evento->fechainicio?->format('d/m/Y') . ($evento->fechafinal ? ' - ' . $evento->fechafinal->format('d/m/Y') : ''),
            'event_location' => $evento->localidad_display,
            'payment_method' => $registro->metodoPago?->nombre ?? 'Sin método',
            'payment_amount' => (float) $registro->precio_aplicado,
            'payment_amount_formatted' => $registro->formattedPrecioAplicado(true),
            'payment_reference' => $registro->referencia_pago ?: 'Generada por sistema',
            'payment_notes' => data_get($registro->payload_pago, 'manual_payment_notes'),
            'issuer_name' => Auth::user()?->name ?? 'Sistema',
            'document_title' => 'Recibo de pago',
            'receiver_signature_label' => 'Firma de quien recibe',
        ];
    }

    private function refundReceiptPreviewData(Evento $evento, EventoRegistro $registro): array
    {
        $registro->loadMissing('evento.precios.moneda');

        $participantName = trim(($registro->persona?->nombre ?? '') . ' ' . ($registro->persona?->apellido ?? '')) ?: 'Participante';
        $refund = data_get($registro->payload_pago, 'refund', []);
        $issuedAt = filled($refund['processed_at'] ?? null) ? Carbon::parse($refund['processed_at']) : now();
        $creator = $this->eventCreatorUser($evento);
        $processedBy = User::query()->find($refund['requested_by'] ?? null);

        return [
            'enabled' => true,
            'filename' => Str::slug('recibo-devolucion-' . $evento->nombreevento . '-' . $participantName) . '.pdf',
            'document_number' => sprintf('DEV-%s-%06d', $issuedAt->format('Y'), $registro->id),
            'issued_at' => $issuedAt,
            'participant_name' => $participantName,
            'participant_identity' => $registro->persona?->dni ?: 'Sin identidad',
            'participant_email' => $registro->persona?->correo ?: 'Sin correo',
            'event_name' => $evento->nombreevento,
            'event_organizer' => $evento->organizador,
            'event_dates' => $evento->fechainicio?->format('d/m/Y') . ($evento->fechafinal ? ' - ' . $evento->fechafinal->format('d/m/Y') : ''),
            'event_location' => $evento->localidad_display,
            'payment_method' => $registro->metodoPago?->nombre ?? 'Sin método',
            'payment_amount' => (float) $registro->precio_aplicado,
            'payment_amount_formatted' => $registro->formattedPrecioAplicado(true),
            'payment_reference' => $registro->referencia_pago ?: 'Generada por sistema',
            'payment_notes' => $refund['reason'] ?? null,
            'issuer_name' => $processedBy?->name ?? (Auth::user()?->name ?? 'Sistema'),
            'creator_name' => $creator?->name ?? 'Sin creador asignado',
            'document_title' => 'Recibo de devolución',
            'receiver_signature_label' => 'Firma de quien recibe la devolución',
        ];
    }

    private function invoicePreviewData(Evento $evento, EventoRegistro $registro, bool $assignNumber = false): array
    {
        $config = $this->activeBillingConfig();

        if (! $config) {
            return [
                'enabled' => false,
                'error' => 'No hay una configuración fiscal activa para generar facturas.',
            ];
        }

        $invoiceMeta = $assignNumber
            ? $this->ensureInvoiceMetadata($registro, $config)
            : $this->peekInvoiceMetadata($registro, $config);

        if (! ($invoiceMeta['enabled'] ?? false)) {
            return $invoiceMeta;
        }

        $registro->loadMissing('evento.precios.moneda');

        $participantName = trim(($registro->persona?->nombre ?? '') . ' ' . ($registro->persona?->apellido ?? '')) ?: 'Participante';

        return [
            'enabled' => true,
            'filename' => Str::slug('factura-' . $evento->nombreevento . '-' . $participantName) . '.pdf',
            'document_number' => $invoiceMeta['document_number'],
            'issued_at' => $invoiceMeta['issued_at'],
            'participant_name' => $participantName,
            'participant_identity' => $registro->persona?->dni ?: 'CF',
            'participant_email' => $registro->persona?->correo ?: 'Sin correo',
            'participant_phone' => $registro->persona?->telefono ?: 'Sin teléfono',
            'event_name' => $evento->nombreevento,
            'event_organizer' => $evento->organizador,
            'event_dates' => $evento->fechainicio?->format('d/m/Y') . ($evento->fechafinal ? ' - ' . $evento->fechafinal->format('d/m/Y') : ''),
            'event_location' => $evento->localidad_display,
            'payment_method' => $registro->metodoPago?->nombre ?? 'Sin método',
            'payment_amount' => (float) $registro->precio_aplicado,
            'payment_amount_formatted' => $registro->formattedPrecioAplicado(true),
            'payment_reference' => $registro->referencia_pago ?: 'Generada por sistema',
            'fiscal_name' => $config->razon_social,
            'fiscal_rtn' => $config->rtn_emisor,
            'fiscal_cai' => $config->cai,
            'fiscal_address' => $config->direccion_fiscal,
            'fiscal_phone' => $config->telefono_fiscal,
            'fiscal_email' => $config->correo_fiscal,
            'fiscal_legend' => $config->leyenda,
            'expiry_date' => $config->fecha_limite_emision,
            'document_title' => 'Factura',
        ];
    }

    private function activeBillingConfig(): ?FacturacionConfig
    {
        return FacturacionConfig::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    private function eventCreatorUser(Evento $evento): ?User
    {
        if (! $evento->created_by) {
            return null;
        }

        return User::query()->find($evento->created_by);
    }

    private function peekInvoiceMetadata(EventoRegistro $registro, FacturacionConfig $config): array
    {
        $payload = $registro->payload_pago ?? [];
        $invoice = data_get($payload, 'invoice');

        if (is_array($invoice) && filled($invoice['document_number'] ?? null)) {
            return [
                'enabled' => true,
                'document_number' => $invoice['document_number'],
                'issued_at' => isset($invoice['issued_at']) ? Carbon::parse($invoice['issued_at']) : now(),
            ];
        }

        if (! $config->hasValidRange()) {
            return [
                'enabled' => false,
                'error' => 'La configuración fiscal no tiene un CAI vigente o el rango autorizado ya se agotó.',
            ];
        }

        return [
            'enabled' => true,
            'document_number' => $config->formattedDocumentNumber((int) $config->siguiente_numero),
            'issued_at' => now(),
        ];
    }

    private function ensureInvoiceMetadata(EventoRegistro $registro, FacturacionConfig $config): array
    {
        $payload = $registro->payload_pago ?? [];
        $invoice = data_get($payload, 'invoice');

        if (is_array($invoice) && filled($invoice['document_number'] ?? null)) {
            return [
                'enabled' => true,
                'document_number' => $invoice['document_number'],
                'issued_at' => isset($invoice['issued_at']) ? Carbon::parse($invoice['issued_at']) : now(),
            ];
        }

        return DB::transaction(function () use ($registro, $config) {
            $lockedConfig = FacturacionConfig::query()
                ->whereKey($config->id)
                ->lockForUpdate()
                ->first();

            if (! $lockedConfig || ! $lockedConfig->hasValidRange()) {
                return [
                    'enabled' => false,
                    'error' => 'La configuración fiscal no tiene un CAI vigente o el rango autorizado ya se agotó.',
                ];
            }

            $sequence = (int) $lockedConfig->siguiente_numero;
            $documentNumber = $lockedConfig->formattedDocumentNumber($sequence);
            $issuedAt = now();

            $lockedConfig->update([
                'siguiente_numero' => $sequence + 1,
            ]);

            $payload = $registro->payload_pago ?? [];
            $payload['invoice'] = [
                'document_number' => $documentNumber,
                'issued_at' => $issuedAt->toDateTimeString(),
                'cai' => $lockedConfig->cai,
                'config_id' => $lockedConfig->id,
                'generated_by' => Auth::id(),
            ];

            $registro->update([
                'payload_pago' => $payload,
            ]);

            return [
                'enabled' => true,
                'document_number' => $documentNumber,
                'issued_at' => $issuedAt,
            ];
        });
    }
}
