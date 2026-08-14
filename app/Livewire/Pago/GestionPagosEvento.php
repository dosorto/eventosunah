<?php

namespace App\Livewire\Pago;

use App\Mail\ParticipantAccessCredentialsMail;
use App\Models\Evento;
use App\Models\EventoRegistro;
use App\Models\FacturacionConfig;
use App\Models\MetodoPago;
use App\Models\Nacionalidad;
use App\Models\Persona;
use App\Models\Tipoperfil;
use App\Models\User;
use App\Services\ProfileDirectoryLookupService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GestionPagosEvento extends Component
{
    use WithFileUploads;
    use WithPagination;

    private const FIXED_PAYMENT_CODES = [
        'efectivo',
        'transferencia',
        'tarjeta_online',
    ];

    public Evento $evento;
    public string $search = '';
    public string $statusFilter = 'todos';
    public bool $showManualPaymentModal = false;
    public bool $showReceiptModal = false;
    public bool $showInvoiceModal = false;
    public bool $showRefundModal = false;
    public bool $showRefundReceiptModal = false;
    public bool $showRegistrationWizardModal = false;
    public ?int $selectedRegistrationId = null;
    public string $manualMetodoPagoId = '';
    public string $manualNotes = '';
    public string $manualAmount = '';
    public string $refundCreatorPassword = '';
    public string $refundReason = '';
    public int $registrationStep = 1;
    public string $registrationTipoperfilId = '';
    public string $registrationLookupIdentifier = '';
    public bool $registrationLookupResolved = false;
    public bool $registrationManualEntryEnabled = false;
    public bool $registrationPersonaExists = false;
    public bool $registrationExistingEventRegistration = false;
    public string $registrationLookupMessage = '';
    public ?int $registrationPersonaId = null;
    public ?int $registrationUserId = null;
    public string $registrationDni = '';
    public string $registrationNumeroCuenta = '';
    public string $registrationNumeroEmpleado = '';
    public string $registrationPrimerNombre = '';
    public string $registrationSegundoNombre = '';
    public string $registrationPrimerApellido = '';
    public string $registrationSegundoApellido = '';
    public string $registrationCorreo = '';
    public string $registrationCorreoInstitucional = '';
    public string $registrationFechaNacimiento = '';
    public string $registrationSexo = '';
    public string $registrationDireccion = '';
    public string $registrationTelefono = '';
    public string $registrationIdNacionalidad = '';
    public string $registrationMetodoPagoId = '';
    public string $registrationAmount = '';
    public string $registrationPaymentNotes = '';
    public $registrationPaymentProof = null;

    public function mount(Evento $evento): void
    {
        abort_unless(Auth::user()?->can('events.manage'), 403);

        $this->evento = $evento->load('modalidad', 'localidad', 'tipoEvento');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openRegistrationWizardModal(): void
    {
        $this->closeDocumentModals();
        $this->resetRegistrationWizard();
        $this->showRegistrationWizardModal = true;
    }

    public function closeRegistrationWizardModal(): void
    {
        $this->showRegistrationWizardModal = false;
        $this->resetRegistrationWizard();
        $this->resetValidation();
    }

    public function updatedRegistrationTipoperfilId(): void
    {
        $this->resetRegistrationLookupState();
        $this->clearRegistrationIdentityFields();
        $this->registrationMetodoPagoId = '';
        $this->syncRegistrationAmount();
    }

    public function updatedRegistrationMetodoPagoId(): void
    {
        $this->registrationPaymentNotes = '';
    }

    public function lookupRegistrationParticipant(ProfileDirectoryLookupService $lookupService): void
    {
        $this->validate([
            'registrationLookupIdentifier' => 'required|string|max:100',
        ]);

        $this->resetValidation();
        $this->resetRegistrationLookupState();
        $identifierValue = trim($this->registrationLookupIdentifier);
        $this->clearRegistrationIdentityFields();
        $this->registrationDni = $identifierValue;

        $localPerson = Persona::query()
            ->where('dni', $identifierValue)
            ->first();

        if ($localPerson) {
            $profile = $localPerson->tipoPerfil ?: $this->externalRegistrationProfile();

            if (! $profile) {
                $this->addError('registrationLookupIdentifier', 'No se pudo determinar el tipo de perfil del participante.');
                return;
            }

            $this->fillRegistrationFieldsFromPersona($localPerson, $profile);
            $this->registrationLookupResolved = true;
            $this->registrationPersonaExists = true;
            $this->registrationManualEntryEnabled = false;
            $this->registrationLookupMessage = 'Participante encontrado en la base local.';
            $this->registrationExistingEventRegistration = EventoRegistro::query()
                ->where('evento_id', $this->evento->id)
                ->where('persona_id', $localPerson->id)
                ->exists();

            if ($this->registrationExistingEventRegistration) {
                $this->addError('registrationLookupIdentifier', 'Esta persona ya se encuentra inscrita en este evento.');
            }

            if (! $this->registrationExistingEventRegistration) {
                $this->registrationStep = 2;
            }

            $this->syncRegistrationAmount();
            return;
        }

        $profile = $this->externalRegistrationProfile();

        if (! $profile) {
            $this->addError('registrationLookupIdentifier', 'No existe un tipo de perfil externo configurado.');
            return;
        }

        $this->registrationTipoperfilId = (string) $profile->id;

        $result = $lookupService->lookup($profile, $identifierValue);

        if (($result['success'] ?? false) === true) {
            $this->fillRegistrationFieldsFromLookup($result['data'] ?? [], $profile);
            $this->registrationDni = trim((string) ($this->registrationDni ?: $identifierValue));
            $this->registrationLookupResolved = true;
            $this->registrationManualEntryEnabled = true;
            $this->registrationLookupMessage = $result['message'] ?? 'Se encontró la información del participante en la API.';
        } else {
            $this->registrationManualEntryEnabled = true;
            $this->registrationDni = $identifierValue;
            $this->registrationLookupMessage = ($result['message'] ?? 'No se encontró el participante.') . ' Completa los datos manualmente para continuar.';
        }

        $this->syncRegistrationAmount();
        $this->registrationStep = 2;
    }

    public function continueRegistrationWizard(): void
    {
        if ($this->registrationStep === 1) {
            $this->validate([
                'registrationLookupIdentifier' => 'required|string|max:100',
            ]);

            if ($this->registrationExistingEventRegistration) {
                $this->addError('registrationLookupIdentifier', 'Esta persona ya se encuentra inscrita en este evento.');
                return;
            }

            if (! $this->registrationLookupResolved && ! $this->registrationManualEntryEnabled) {
                $this->addError('registrationLookupIdentifier', 'Primero busca a la persona por número de identidad para continuar.');
                return;
            }

            $this->registrationStep = 2;
            return;
        }

        if ($this->registrationStep === 2) {
            if ($this->registrationExistingEventRegistration) {
                $this->addError('registrationLookupIdentifier', 'Esta persona ya se encuentra inscrita en este evento.');
                return;
            }

            if (! $this->registrationPersonaExists) {
                $this->validate($this->registrationIdentityRules());
            }

            $this->registrationStep = 3;
            return;
        }

        $this->syncRegistrationAmount();
    }

    public function previousRegistrationWizardStep(): void
    {
        if ($this->registrationStep > 1) {
            $this->registrationStep--;
        }
    }

    public function saveRegistrationWizard(): void
    {
        $profile = $this->registrationSelectedProfile();

        if (! $profile) {
            $this->addError('registrationLookupIdentifier', 'No se pudo determinar el perfil del participante.');
            return;
        }

        $identityValidated = $this->registrationPersonaExists && $this->registrationPersonaId
            ? []
            : $this->validate($this->registrationIdentityRules());

        $paymentRules = [];

        if ($this->evento->tipo_acceso === 'pagada') {
            $paymentRules = [
                'registrationMetodoPagoId' => 'required|exists:metodos_pago,id',
                'registrationAmount' => 'required|numeric|min:0',
                'registrationPaymentNotes' => 'nullable|string|max:500',
            ];

            if ($this->registrationSelectedPaymentMethod()?->requiresProof()) {
                $paymentRules['registrationPaymentProof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:10240';
            }
        }

        $validatedPayment = $paymentRules ? $this->validate($paymentRules) : [];
        $paymentProofFile = $this->registrationPaymentProof;
        $createdCredentials = null;

        $preExistingRegistration = null;
        $existingPersonaForRegistration = $this->registrationPersonaForDuplicateCheck($profile);

        if ($existingPersonaForRegistration) {
            $preExistingRegistration = EventoRegistro::query()
                ->where('evento_id', $this->evento->id)
                ->where('persona_id', $existingPersonaForRegistration->id)
                ->first();
        }

        if ($preExistingRegistration) {
            $this->registrationExistingEventRegistration = true;
            $this->registrationStep = 2;
            $this->registrationLookupMessage = 'Esta persona ya se encuentra inscrita en este evento.';
            $this->addError('registrationLookupIdentifier', 'Esta persona ya se encuentra inscrita en este evento.');
            return;
        }

        DB::transaction(function () use ($profile, $identityValidated, $validatedPayment, $paymentProofFile, &$createdCredentials) {
            $persona = $this->resolveRegistrationPersona($profile, $identityValidated);

            $existingRegistration = EventoRegistro::query()
                ->where('evento_id', $this->evento->id)
                ->where('persona_id', $persona->id)
                ->first();

            if ($existingRegistration) {
                $this->registrationExistingEventRegistration = true;
                $this->registrationLookupMessage = 'Esta persona ya se encuentra inscrita en este evento.';
                return;
            }

            $createdCredentials = $this->ensureRegistrationUserAccount($persona);
            $price = $this->resolveRegistrationPriceForProfile((int) $profile->id);
            $metodoPago = $this->registrationSelectedPaymentMethod();

            $estado = 'registrado';
            $estadoPago = 'no_aplica';
            $pagadoEn = null;
            $payloadPago = null;
            $proofPath = null;
            $proofName = null;
            $proofMime = null;

            if ($this->evento->tipo_acceso === 'pagada') {
                $estado = $metodoPago?->isCash() ? 'registrado' : 'pendiente_pago';
                $estadoPago = match (true) {
                    $metodoPago?->isCash() => 'pagado',
                    $metodoPago?->requiere_api => 'pendiente_pasarela',
                    default => 'pendiente_confirmacion',
                };
                $pagadoEn = $metodoPago?->isCash() ? now() : null;

                if ($paymentProofFile) {
                    $proofPath = $paymentProofFile->store('payment-proofs/eventos/' . $this->evento->id, 'public');
                    $proofName = $paymentProofFile->getClientOriginalName();
                    $proofMime = $paymentProofFile->getMimeType();
                }

                $payloadPago = [
                    'metodo' => $metodoPago?->nombre,
                    'tipo' => $metodoPago?->tipo,
                    'proveedor' => $metodoPago?->proveedor,
                    'requiere_api' => (bool) $metodoPago?->requiere_api,
                    'requiere_comprobante' => (bool) $metodoPago?->requiresProof(),
                    'campos' => [],
                    'manual_payment_notes' => trim((string) ($validatedPayment['registrationPaymentNotes'] ?? '')) ?: null,
                    'manual_payment_recorded_by' => $metodoPago?->isCash() ? Auth::id() : null,
                    'manual_payment_recorded_at' => $metodoPago?->isCash() ? now()->toDateTimeString() : null,
                    'documento_cobro_tipo' => $metodoPago?->documento_cobro_tipo,
                ];
            }

            EventoRegistro::query()->create([
                'evento_id' => $this->evento->id,
                'persona_id' => $persona->id,
                'tipoperfil_id' => $profile->id,
                'metodo_pago_id' => $metodoPago?->id,
                'precio_aplicado' => $this->evento->tipo_acceso === 'pagada'
                    ? (float) ($validatedPayment['registrationAmount'] ?? $price['amount'])
                    : 0,
                'detalle_precio' => $price['label'],
                'estado' => $estado,
                'estado_pago' => $estadoPago,
                'referencia_pago' => $this->evento->tipo_acceso === 'pagada'
                    ? 'PAY-' . Str::upper(Str::random(10))
                    : null,
                'payload_pago' => $payloadPago,
                'comprobante_pago_path' => $proofPath,
                'comprobante_pago_nombre' => $proofName,
                'comprobante_pago_mime' => $proofMime,
                'pagado_en' => $pagadoEn,
            ]);
        });

        if ($this->registrationExistingEventRegistration) {
            $this->registrationStep = 2;
            $this->registrationLookupMessage = 'Esta persona ya se encuentra inscrita en este evento.';
            $this->addError('registrationLookupIdentifier', 'Esta persona ya se encuentra inscrita en este evento.');
            return;
        }

        if ($createdCredentials && filled($createdCredentials['email'])) {
            try {
                Mail::to($createdCredentials['email'])->send(new ParticipantAccessCredentialsMail(
                    participantName: $createdCredentials['name'],
                    email: $createdCredentials['email'],
                    password: $createdCredentials['password'],
                    eventName: $this->evento->nombreevento,
                ));
            } catch (\Throwable $exception) {
                report($exception);
                session()->flash('error', 'La inscripción se guardó, pero no se pudo enviar el correo con las credenciales.');
            }
        }

        $this->closeRegistrationWizardModal();
        $this->resetPage();

        session()->flash(
            'message',
            $createdCredentials
                ? 'Participante inscrito correctamente. Se generó su cuenta y se enviaron sus credenciales por correo.'
                : 'Participante inscrito correctamente.'
        );
    }

    public function approvePayment(): void
    {
        $registration = $this->selectedRegistration();

        if (! $registration) {
            return;
        }

        $payload = $registration->payload_pago ?? [];
        $payload['validated_by'] = Auth::id();
        $payload['validated_at'] = now()->toDateTimeString();
        $payload['documento_cobro_tipo'] = $registration->metodoPago?->documento_cobro_tipo;

        $registration->update([
            'estado' => 'registrado',
            'estado_pago' => 'pagado',
            'pagado_en' => now(),
            'payload_pago' => $payload,
        ]);

        $this->closeManualPaymentModal();
        session()->flash('message', 'Pago aprobado correctamente.');
    }

    public function rejectPayment(): void
    {
        $registration = $this->selectedRegistration();

        if (! $registration) {
            return;
        }

        $payload = $registration->payload_pago ?? [];
        $payload['rejected_by'] = Auth::id();
        $payload['rejected_at'] = now()->toDateTimeString();

        $registration->update([
            'estado' => 'pendiente_pago',
            'estado_pago' => 'rechazado',
            'payload_pago' => $payload,
        ]);

        $this->closeManualPaymentModal();
        session()->flash('message', 'Pago marcado como rechazado.');
    }

    public function openManualPaymentModal(int $registrationId): void
    {
        $registration = $this->registrationQuery()->findOrFail($registrationId);

        $this->closeDocumentModals();
        $this->selectedRegistrationId = $registration->id;
        $this->manualMetodoPagoId = (string) ($registration->metodo_pago_id ?? '');
        $this->manualNotes = '';
        $this->manualAmount = number_format((float) $registration->precio_aplicado, 2, '.', '');
        $this->showManualPaymentModal = true;
    }

    public function closeManualPaymentModal(): void
    {
        $this->showManualPaymentModal = false;
        $this->resetPaymentSelection();
    }

    public function openReceiptModal(int $registrationId): void
    {
        $registration = $this->registrationQuery()->findOrFail($registrationId);

        if ($registration->estado_pago !== 'pagado') {
            session()->flash('error', 'Solo se puede generar recibo para pagos ya validados.');
            return;
        }

        $this->closeDocumentModals();
        $this->selectedRegistrationId = $registration->id;
        $this->showReceiptModal = true;
    }

    public function closeReceiptModal(): void
    {
        $this->showReceiptModal = false;
        $this->resetPaymentSelection();
    }

    public function openInvoiceModal(int $registrationId): void
    {
        $registration = $this->registrationQuery()->findOrFail($registrationId);

        if ($registration->estado_pago !== 'pagado') {
            session()->flash('error', 'Solo se puede generar factura para pagos ya validados.');
            return;
        }

        if (! $this->activeBillingConfig()) {
            session()->flash('error', 'No puedes generar facturas porque no hay una configuración fiscal activa.');
            return;
        }

        $this->closeDocumentModals();
        $this->selectedRegistrationId = $registration->id;
        $this->showInvoiceModal = true;
    }

    public function closeInvoiceModal(): void
    {
        $this->showInvoiceModal = false;
        $this->resetPaymentSelection();
    }

    public function openRefundModal(int $registrationId): void
    {
        $registration = $this->registrationQuery()->findOrFail($registrationId);

        if ($registration->estado_pago !== 'pagado') {
            session()->flash('error', 'Solo se pueden devolver pagos ya validados.');
            return;
        }

        $this->closeDocumentModals();
        $this->selectedRegistrationId = $registration->id;
        $this->refundCreatorPassword = '';
        $this->refundReason = '';
        $this->showRefundModal = true;
    }

    public function closeRefundModal(): void
    {
        $this->showRefundModal = false;
        $this->refundCreatorPassword = '';
        $this->refundReason = '';
        $this->resetPaymentSelection();
    }

    public function openRefundReceiptModal(int $registrationId): void
    {
        $registration = $this->registrationQuery()->findOrFail($registrationId);

        if ($registration->estado_pago !== 'reembolsado') {
            session()->flash('error', 'El recibo de devolución solo está disponible para pagos reembolsados.');
            return;
        }

        $this->closeDocumentModals();
        $this->selectedRegistrationId = $registration->id;
        $this->showRefundReceiptModal = true;
    }

    public function closeRefundReceiptModal(): void
    {
        $this->showRefundReceiptModal = false;
        $this->resetPaymentSelection();
    }

    public function saveManualPayment(): void
    {
        $validated = $this->validate([
            'manualMetodoPagoId' => 'required|exists:metodos_pago,id',
            'manualNotes' => 'nullable|string|max:500',
            'manualAmount' => 'required|numeric|min:0',
        ]);

        $registration = $this->selectedRegistration();

        if (! $registration) {
            return;
        }

        $payload = $registration->payload_pago ?? [];
        $payload['manual_payment_notes'] = $validated['manualNotes'] ?? null;
        $payload['manual_payment_recorded_by'] = Auth::id();
        $payload['manual_payment_recorded_at'] = now()->toDateTimeString();
        $payload['documento_cobro_tipo'] = MetodoPago::query()->find($validated['manualMetodoPagoId'])?->documento_cobro_tipo;

        $registration->update([
            'metodo_pago_id' => (int) $validated['manualMetodoPagoId'],
            'precio_aplicado' => (float) $validated['manualAmount'],
            'referencia_pago' => $registration->referencia_pago ?: 'PAY-' . Str::upper(Str::random(10)),
            'estado' => 'registrado',
            'estado_pago' => 'pagado',
            'pagado_en' => now(),
            'payload_pago' => $payload,
        ]);

        $this->closeManualPaymentModal();
        session()->flash('message', 'Cobro registrado y validado correctamente.');
    }

    public function downloadReceiptPdf()
    {
        $registration = $this->selectedRegistration();

        if (! $registration) {
            return null;
        }

        $document = $this->receiptPreviewData($registration);
        $pdf = PDF::loadView('pdf.payments.receipt', [
            'document' => $document,
        ])->setPaper('letter');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $document['filename'],
            ['Content-Type' => 'application/pdf']
        );
    }

    public function downloadInvoicePdf()
    {
        $registration = $this->selectedRegistration();

        if (! $registration) {
            return null;
        }

        $document = $this->invoicePreviewData($registration, true);

        if (! ($document['enabled'] ?? false)) {
            session()->flash('error', $document['error'] ?? 'No fue posible generar la factura.');
            return null;
        }

        $pdf = PDF::loadView('pdf.payments.invoice', [
            'document' => $document,
        ])->setPaper('letter');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $document['filename'],
            ['Content-Type' => 'application/pdf']
        );
    }

    public function processRefund(): void
    {
        $this->validate([
            'refundCreatorPassword' => ['required', 'string'],
            'refundReason' => ['required', 'string', 'max:500'],
        ]);

        $registration = $this->selectedRegistration();

        if (! $registration) {
            return;
        }

        $creator = $this->eventCreatorUser();

        if (! $creator || ! Hash::check($this->refundCreatorPassword, $creator->password)) {
            $this->addError('refundCreatorPassword', 'La contraseña del creador del evento no es válida.');
            return;
        }

        $payload = $registration->payload_pago ?? [];
        $payload['refund'] = [
            'requested_by' => Auth::id(),
            'authorized_by' => $creator->id,
            'processed_at' => now()->toDateTimeString(),
            'reason' => trim($this->refundReason),
            'previous_payment_status' => $registration->estado_pago,
        ];

        $registration->update([
            'estado' => 'reembolsado',
            'estado_pago' => 'reembolsado',
            'payload_pago' => $payload,
        ]);

        $this->closeRefundModal();
        $this->statusFilter = 'todos';
        $this->resetPage();
        session()->flash('message', 'La devolución fue registrada correctamente.');
    }

    public function render()
    {
        $registrations = $this->registrationQuery()->paginate(12);

        $summaryQuery = $this->summaryQuery();
        $pendingStates = ['pendiente_confirmacion', 'pendiente_pasarela', 'pendiente_efectivo'];

        $summary = [
            'total' => (clone $summaryQuery)->count(),
            'pending_count' => (clone $summaryQuery)->whereIn('estado_pago', $pendingStates)->count(),
            'pending_amount' => (float) ((clone $summaryQuery)->whereIn('estado_pago', $pendingStates)->sum('precio_aplicado') ?: 0),
            'approved_count' => (clone $summaryQuery)->where('estado_pago', 'pagado')->count(),
            'approved_amount' => (float) ((clone $summaryQuery)->where('estado_pago', 'pagado')->sum('precio_aplicado') ?: 0),
            'refunded_count' => (clone $summaryQuery)->where('estado_pago', 'reembolsado')->count(),
            'refunded_amount' => (float) ((clone $summaryQuery)->where('estado_pago', 'reembolsado')->sum('precio_aplicado') ?: 0),
            'free_count' => (clone $summaryQuery)->where('estado_pago', 'no_aplica')->count(),
        ];

        return view('livewire.pago.gestion-pagos-evento', [
            'registrations' => $registrations,
            'summary' => $summary,
            'selectedRegistration' => $this->selectedRegistration(),
            'eventCreatorName' => $this->eventCreatorUser()?->name ?? 'Sin creador asignado',
            'manualPaymentMethods' => MetodoPago::query()
                ->active()
                ->whereIn('codigo', self::FIXED_PAYMENT_CODES)
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get(),
            'registrationProfiles' => Tipoperfil::query()->orderBy('tipoperfil')->get(),
            'registrationNationalities' => Nacionalidad::query()->orderBy('nombreNacionalidad')->get(),
            'registrationSelectedProfile' => $this->registrationSelectedProfile(),
            'registrationSelectedPaymentMethod' => $this->registrationSelectedPaymentMethod(),
            'registrationPricePreview' => $this->registrationPricePreview(),
            'registrationPaymentMethods' => MetodoPago::query()
                ->active()
                ->whereIn('codigo', self::FIXED_PAYMENT_CODES)
                ->orderBy('orden')
                ->orderBy('nombre')
                ->get(),
        ])->layout('components.layouts.app');
    }

    public function proofUrl(?EventoRegistro $registration): ?string
    {
        if (! $registration?->comprobante_pago_path) {
            return null;
        }

        return route('pagos.comprobante.preview', [
            'evento' => $this->evento->id,
            'registro' => $registration->id,
        ]);
    }

    public function proofMimeCategory(?EventoRegistro $registration): string
    {
        $mime = strtolower((string) ($registration?->comprobante_pago_mime ?? ''));
        $path = strtolower((string) ($registration?->comprobante_pago_path ?? ''));

        if ($mime === 'application/pdf' || str_ends_with($path, '.pdf')) {
            return 'pdf';
        }

        return 'image';
    }

    public function receiptPdfUrl(?EventoRegistro $registration): ?string
    {
        if (! $registration || $registration->estado_pago !== 'pagado') {
            return null;
        }

        return route('pagos.documentos.recibo', [
            'evento' => $this->evento->id,
            'registro' => $registration->id,
        ]);
    }

    public function invoicePdfUrl(?EventoRegistro $registration): ?string
    {
        if (! $registration || $registration->estado_pago !== 'pagado' || ! $this->activeBillingConfig()) {
            return null;
        }

        return route('pagos.documentos.factura', [
            'evento' => $this->evento->id,
            'registro' => $registration->id,
        ]);
    }

    public function refundReceiptPdfUrl(?EventoRegistro $registration): ?string
    {
        if (! $registration || $registration->estado_pago !== 'reembolsado') {
            return null;
        }

        return route('pagos.documentos.recibo-devolucion', [
            'evento' => $this->evento->id,
            'registro' => $registration->id,
        ]);
    }

    public function receiptPreviewData(?EventoRegistro $registration): array
    {
        if (! $registration) {
            return [];
        }

        $registration->loadMissing('evento.precios.moneda');

        $participantName = trim(($registration->persona?->nombre ?? '') . ' ' . ($registration->persona?->apellido ?? '')) ?: 'Participante';
        $issuedAt = $registration->pagado_en ?? now();
        $receiptNumber = sprintf('REC-%s-%06d', $issuedAt->format('Y'), $registration->id);

        return [
            'enabled' => true,
            'filename' => Str::slug('recibo-' . $this->evento->nombreevento . '-' . $participantName) . '.pdf',
            'document_number' => $receiptNumber,
            'issued_at' => $issuedAt,
            'participant_name' => $participantName,
            'participant_identity' => $registration->persona?->dni ?: 'Sin identidad',
            'participant_email' => $registration->persona?->correo ?: 'Sin correo',
            'event_name' => $this->evento->nombreevento,
            'event_organizer' => $this->evento->organizador,
            'event_dates' => $this->evento->fechainicio?->format('d/m/Y') . ($this->evento->fechafinal ? ' - ' . $this->evento->fechafinal->format('d/m/Y') : ''),
            'event_location' => $this->evento->localidad_display,
            'payment_method' => $registration->metodoPago?->nombre ?? 'Sin método',
            'payment_amount' => (float) $registration->precio_aplicado,
            'payment_amount_formatted' => $registration->formattedPrecioAplicado(true),
            'payment_reference' => $registration->referencia_pago ?: 'Generada por sistema',
            'payment_notes' => data_get($registration->payload_pago, 'manual_payment_notes'),
            'issuer_name' => Auth::user()?->name ?? 'Sistema',
            'document_title' => 'Recibo de pago',
            'receiver_signature_label' => 'Firma de quien recibe',
        ];
    }

    public function refundReceiptPreviewData(?EventoRegistro $registration): array
    {
        if (! $registration) {
            return [];
        }

        $registration->loadMissing('evento.precios.moneda');

        $participantName = trim(($registration->persona?->nombre ?? '') . ' ' . ($registration->persona?->apellido ?? '')) ?: 'Participante';
        $refund = data_get($registration->payload_pago, 'refund', []);
        $issuedAt = filled($refund['processed_at'] ?? null)
            ? Carbon::parse($refund['processed_at'])
            : now();
        $creator = $this->eventCreatorUser();
        $processedBy = User::query()->find($refund['requested_by'] ?? null);

        return [
            'enabled' => true,
            'filename' => Str::slug('recibo-devolucion-' . $this->evento->nombreevento . '-' . $participantName) . '.pdf',
            'document_number' => sprintf('DEV-%s-%06d', $issuedAt->format('Y'), $registration->id),
            'issued_at' => $issuedAt,
            'participant_name' => $participantName,
            'participant_identity' => $registration->persona?->dni ?: 'Sin identidad',
            'participant_email' => $registration->persona?->correo ?: 'Sin correo',
            'event_name' => $this->evento->nombreevento,
            'event_organizer' => $this->evento->organizador,
            'event_dates' => $this->evento->fechainicio?->format('d/m/Y') . ($this->evento->fechafinal ? ' - ' . $this->evento->fechafinal->format('d/m/Y') : ''),
            'event_location' => $this->evento->localidad_display,
            'payment_method' => $registration->metodoPago?->nombre ?? 'Sin método',
            'payment_amount' => (float) $registration->precio_aplicado,
            'payment_amount_formatted' => $registration->formattedPrecioAplicado(true),
            'payment_reference' => $registration->referencia_pago ?: 'Generada por sistema',
            'payment_notes' => $refund['reason'] ?? null,
            'issuer_name' => $processedBy?->name ?? (Auth::user()?->name ?? 'Sistema'),
            'creator_name' => $creator?->name ?? 'Sin creador asignado',
            'document_title' => 'Recibo de devolución',
            'receiver_signature_label' => 'Firma de quien recibe la devolución',
        ];
    }

    public function invoicePreviewData(?EventoRegistro $registration, bool $assignNumber = false): array
    {
        if (! $registration) {
            return [];
        }

        $config = $this->activeBillingConfig();

        if (! $config) {
            return [
                'enabled' => false,
                'error' => 'No hay una configuración fiscal activa para generar facturas.',
            ];
        }

        $invoiceMeta = $assignNumber
            ? $this->ensureInvoiceMetadata($registration, $config)
            : $this->peekInvoiceMetadata($registration, $config);

        if (! ($invoiceMeta['enabled'] ?? false)) {
            return $invoiceMeta;
        }

        $registration->loadMissing('evento.precios.moneda');

        $participantName = trim(($registration->persona?->nombre ?? '') . ' ' . ($registration->persona?->apellido ?? '')) ?: 'Participante';

        return [
            'enabled' => true,
            'filename' => Str::slug('factura-' . $this->evento->nombreevento . '-' . $participantName) . '.pdf',
            'document_number' => $invoiceMeta['document_number'],
            'issued_at' => $invoiceMeta['issued_at'],
            'participant_name' => $participantName,
            'participant_identity' => $registration->persona?->dni ?: 'CF',
            'participant_email' => $registration->persona?->correo ?: 'Sin correo',
            'participant_phone' => $registration->persona?->telefono ?: 'Sin teléfono',
            'event_name' => $this->evento->nombreevento,
            'event_organizer' => $this->evento->organizador,
            'event_dates' => $this->evento->fechainicio?->format('d/m/Y') . ($this->evento->fechafinal ? ' - ' . $this->evento->fechafinal->format('d/m/Y') : ''),
            'event_location' => $this->evento->localidad_display,
            'payment_method' => $registration->metodoPago?->nombre ?? 'Sin método',
            'payment_amount' => (float) $registration->precio_aplicado,
            'payment_amount_formatted' => $registration->formattedPrecioAplicado(true),
            'payment_reference' => $registration->referencia_pago ?: 'Generada por sistema',
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

    private function registrationQuery()
    {
        return EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->with(['persona.tipoPerfil', 'tipoPerfil', 'metodoPago', 'evento.precios.moneda'])
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->whereHas('persona', function ($personaQuery) use ($search) {
                            $personaQuery
                                ->searchName($search)
                                ->orWhere('correo', 'like', '%' . $search . '%')
                                ->orWhere('telefono', 'like', '%' . $search . '%')
                                ->orWhere('dni', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('tipoPerfil', fn ($profileQuery) => $profileQuery->where('tipoperfil', 'like', '%' . $search . '%'))
                        ->orWhereHas('metodoPago', fn ($paymentQuery) => $paymentQuery->where('nombre', 'like', '%' . $search . '%'))
                        ->orWhere('estado_pago', 'like', '%' . $search . '%')
                        ->orWhere('referencia_pago', 'like', '%' . $search . '%');
                });
            })
            ->when($this->statusFilter === 'pendientes', fn ($query) => $query->whereIn('estado_pago', ['pendiente_confirmacion', 'pendiente_pasarela', 'pendiente_efectivo']))
            ->when($this->statusFilter === 'pagados', fn ($query) => $query->where('estado_pago', 'pagado'))
            ->when($this->statusFilter === 'reembolsados', fn ($query) => $query->where('estado_pago', 'reembolsado'))
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    private function summaryQuery()
    {
        return EventoRegistro::query()
            ->where('evento_id', $this->evento->id);
    }

    private function selectedRegistration(): ?EventoRegistro
    {
        if (! $this->selectedRegistrationId) {
            return null;
        }

        return EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->with(['persona.tipoPerfil', 'tipoPerfil', 'metodoPago', 'evento.precios.moneda'])
            ->find($this->selectedRegistrationId);
    }

    private function activeBillingConfig(): ?FacturacionConfig
    {
        return FacturacionConfig::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    private function eventCreatorUser(): ?User
    {
        if (! $this->evento->created_by) {
            return null;
        }

        return User::query()->find($this->evento->created_by);
    }

    private function resetPaymentSelection(): void
    {
        $this->selectedRegistrationId = null;
        $this->manualMetodoPagoId = '';
        $this->manualNotes = '';
        $this->manualAmount = '';
    }

    private function resetRegistrationWizard(): void
    {
        $this->registrationStep = 1;
        $this->registrationTipoperfilId = (string) optional($this->externalRegistrationProfile())->id;
        $this->registrationLookupIdentifier = '';
        $this->registrationMetodoPagoId = '';
        $this->registrationAmount = '';
        $this->registrationPaymentNotes = '';
        $this->registrationPaymentProof = null;
        $this->registrationPersonaId = null;
        $this->registrationUserId = null;
        $this->clearRegistrationIdentityFields();
        $this->resetRegistrationLookupState();
    }

    private function resetRegistrationLookupState(): void
    {
        $this->registrationLookupResolved = false;
        $this->registrationManualEntryEnabled = false;
        $this->registrationPersonaExists = false;
        $this->registrationExistingEventRegistration = false;
        $this->registrationLookupMessage = '';
        $this->registrationPersonaId = null;
        $this->registrationUserId = null;
    }

    private function clearRegistrationIdentityFields(): void
    {
        $this->registrationDni = '';
        $this->registrationNumeroCuenta = '';
        $this->registrationNumeroEmpleado = '';
        $this->registrationPrimerNombre = '';
        $this->registrationSegundoNombre = '';
        $this->registrationPrimerApellido = '';
        $this->registrationSegundoApellido = '';
        $this->registrationCorreo = '';
        $this->registrationCorreoInstitucional = '';
        $this->registrationFechaNacimiento = '';
        $this->registrationSexo = '';
        $this->registrationDireccion = '';
        $this->registrationTelefono = '';
        $this->registrationIdNacionalidad = (string) ($this->defaultHonduranNationalityId() ?? '');
    }

    private function registrationSelectedProfile(): ?Tipoperfil
    {
        return filled($this->registrationTipoperfilId)
            ? Tipoperfil::query()->find($this->registrationTipoperfilId)
            : null;
    }

    private function externalRegistrationProfile(): ?Tipoperfil
    {
        return Tipoperfil::query()
            ->where(function ($query) {
                $query->whereRaw('LOWER(codigo) = ?', ['externo'])
                    ->orWhereRaw('LOWER(tipoperfil) = ?', ['externo']);
            })
            ->first();
    }

    private function registrationSelectedPaymentMethod(): ?MetodoPago
    {
        return filled($this->registrationMetodoPagoId)
            ? MetodoPago::query()->active()->find($this->registrationMetodoPagoId)
            : null;
    }

    private function registrationPricePreview(): array
    {
        $profile = $this->registrationSelectedProfile();

        if (! $profile) {
            return ['amount' => null, 'label' => null, 'currency_symbol' => null, 'currency_code' => null];
        }

        return $this->resolveRegistrationPriceForProfile((int) $profile->id);
    }

    private function resolveRegistrationPriceForProfile(int $profileId): array
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
        $prices = $this->evento->precios()->with('moneda')->get()->where('IdTipoPerfil', $profileId)->values();

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

    private function syncRegistrationAmount(): void
    {
        $price = $this->registrationPricePreview();
        $this->registrationAmount = $price['amount'] !== null
            ? number_format((float) $price['amount'], 2, '.', '')
            : '';
    }

    private function fillRegistrationFieldsFromPersona(Persona $persona, Tipoperfil $profile): void
    {
        $this->registrationPersonaId = $persona->id;
        $this->registrationUserId = $persona->IdUsuario ? (int) $persona->IdUsuario : null;
        $this->registrationTipoperfilId = (string) ($persona->IdTipoPerfil ?: $profile->id);
        $this->registrationDni = (string) ($persona->dni ?? '');
        $this->registrationNumeroCuenta = (string) ($persona->numeroCuenta ?? '');
        $this->registrationNumeroEmpleado = (string) ($persona->numeroEmpleado ?? '');
        [$legacyPrimerNombre, $legacySegundoNombre] = $this->splitRegistrationNameParts($persona->nombre ?? '');
        [$legacyPrimerApellido, $legacySegundoApellido] = $this->splitRegistrationNameParts($persona->apellido ?? '');
        $this->registrationPrimerNombre = (string) ($persona->primer_nombre ?: $legacyPrimerNombre);
        $this->registrationSegundoNombre = (string) ($persona->segundo_nombre ?: $legacySegundoNombre);
        $this->registrationPrimerApellido = (string) ($persona->primer_apellido ?: $legacyPrimerApellido);
        $this->registrationSegundoApellido = (string) ($persona->segundo_apellido ?: $legacySegundoApellido);
        $this->registrationCorreo = (string) ($persona->correo ?? $persona->user?->email ?? '');
        $this->registrationCorreoInstitucional = (string) ($persona->correoInstitucional ?? '');
        $this->registrationFechaNacimiento = $persona->fechaNacimiento
            ? Carbon::parse($persona->fechaNacimiento)->format('Y-m-d')
            : '';
        $this->registrationSexo = (string) ($persona->sexo ?? '');
        $this->registrationDireccion = (string) ($persona->direccion ?? '');
        $this->registrationTelefono = (string) ($persona->telefono ?? '');
        $this->registrationIdNacionalidad = (string) ($persona->IdNacionalidad ?: $this->defaultHonduranNationalityId() ?: '');
    }

    private function fillRegistrationFieldsFromLookup(array $data, Tipoperfil $profile): void
    {
        $this->registrationDni = trim((string) ($data['dni'] ?? $this->registrationFallbackDni($profile)));
        $this->registrationNumeroCuenta = trim((string) ($data['numeroCuenta'] ?? ''));
        $this->registrationNumeroEmpleado = trim((string) ($data['numeroEmpleado'] ?? ''));
        [$lookupPrimerNombre, $lookupSegundoNombre] = $this->splitRegistrationNameParts($data['nombre'] ?? '');
        [$lookupPrimerApellido, $lookupSegundoApellido] = $this->splitRegistrationNameParts($data['apellido'] ?? '');
        $this->registrationPrimerNombre = trim((string) ($data['primer_nombre'] ?? $lookupPrimerNombre));
        $this->registrationSegundoNombre = trim((string) ($data['segundo_nombre'] ?? $lookupSegundoNombre));
        $this->registrationPrimerApellido = trim((string) ($data['primer_apellido'] ?? $lookupPrimerApellido));
        $this->registrationSegundoApellido = trim((string) ($data['segundo_apellido'] ?? $lookupSegundoApellido));
        $this->registrationCorreo = trim((string) ($data['correo'] ?? ''));
        $this->registrationCorreoInstitucional = trim((string) ($data['correoInstitucional'] ?? ''));
        $this->registrationFechaNacimiento = $this->normalizeRegistrationDate($data['fechaNacimiento'] ?? null);
        $this->registrationSexo = $this->normalizeRegistrationSex($data['sexo'] ?? null);
        $this->registrationDireccion = trim((string) ($data['direccion'] ?? ''));
        $this->registrationTelefono = trim((string) ($data['telefono'] ?? ''));
        $this->registrationIdNacionalidad = (string) ($this->resolveNationalityIdFromLookup($data) ?? $this->defaultHonduranNationalityId() ?? '');
    }

    private function splitRegistrationNameParts(mixed $value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), 2, PREG_SPLIT_NO_EMPTY) ?: [];

        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    private function normalizeRegistrationDate(mixed $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return '';
        }
    }

    private function normalizeRegistrationSex(mixed $value): string
    {
        $value = Str::lower(trim((string) $value));

        return match (true) {
            in_array($value, ['m', 'masculino', 'male', 'hombre'], true) => 'M',
            in_array($value, ['f', 'femenino', 'female', 'mujer'], true) => 'F',
            filled($value) => 'Otro',
            default => '',
        };
    }

    private function resolveNationalityIdFromLookup(array $data): ?int
    {
        $id = $data['IdNacionalidad'] ?? $data['idNacionalidad'] ?? $data['nacionalidad_id'] ?? null;

        if (filled($id) && Nacionalidad::query()->whereKey($id)->exists()) {
            return (int) $id;
        }

        $name = trim((string) ($data['nacionalidad'] ?? $data['nombreNacionalidad'] ?? ''));

        if ($name === '') {
            return null;
        }

        return Nacionalidad::query()
            ->whereRaw('LOWER(nombreNacionalidad) = ?', [Str::lower($name)])
            ->value('id');
    }

    private function defaultHonduranNationalityId(): ?int
    {
        return Nacionalidad::query()
            ->whereRaw('LOWER(nombreNacionalidad) = ?', ['hondureña'])
            ->orWhereRaw('LOWER(nombreNacionalidad) = ?', ['hondurena'])
            ->value('id');
    }

    private function registrationFallbackDni(Tipoperfil $profile): string
    {
        if ($profile->tipo_identificador === 'dni' && filled($this->registrationLookupIdentifier)) {
            return trim($this->registrationLookupIdentifier);
        }

        return 'TMP-' . Str::upper(Str::random(12));
    }

    private function registrationIdentityRules(): array
    {
        return array_merge(
            $this->registrationPersonalRules(),
            $this->registrationContactRules(),
        );
    }

    private function registrationPersonalRules(): array
    {
        $rules = [
            'registrationPrimerNombre' => 'required|string|max:100',
            'registrationSegundoNombre' => 'nullable|string|max:100',
            'registrationPrimerApellido' => 'required|string|max:100',
            'registrationSegundoApellido' => 'nullable|string|max:100',
            'registrationDni' => 'required|string|max:50',
            'registrationFechaNacimiento' => 'nullable|date',
            'registrationSexo' => 'required|string|max:20',
        ];

        $profile = $this->registrationSelectedProfile();
        $identifierField = $profile?->tipo_identificador ?: 'dni';

        if ($identifierField === 'numeroCuenta') {
            $rules['registrationNumeroCuenta'] = 'required|string|max:50';
        }

        if ($identifierField === 'numeroEmpleado') {
            $rules['registrationNumeroEmpleado'] = 'required|string|max:50';
        }

        return $rules;
    }

    private function registrationContactRules(): array
    {
        return [
            'registrationCorreo' => 'required|email|max:255',
            'registrationCorreoInstitucional' => 'nullable|email|max:255',
            'registrationDireccion' => 'required|string|max:255',
            'registrationTelefono' => 'required|string|max:30',
            'registrationIdNacionalidad' => 'required|exists:nacionalidads,id',
        ];
    }

    private function resolveRegistrationPersona(Tipoperfil $profile, array $validated): Persona
    {
        $input = array_replace($this->registrationStateInput(), $validated);
        $identifierField = $profile->tipo_identificador ?: 'dni';
        $identifierValue = match ($identifierField) {
            'numeroCuenta' => trim((string) ($input['registrationNumeroCuenta'] ?? '')),
            'numeroEmpleado' => trim((string) ($input['registrationNumeroEmpleado'] ?? '')),
            default => trim((string) ($input['registrationDni'] ?? '')),
        };

        $persona = $this->registrationPersonaId
            ? Persona::query()->find($this->registrationPersonaId)
            : null;

        if (! $persona) {
            $query = Persona::query();

            if (filled($identifierValue)) {
                $query->where($identifierField, $identifierValue);
            } else {
                $query->where('correo', trim((string) ($input['registrationCorreo'] ?? '')));
            }

            $persona = $query->first() ?? new Persona();
        }

        $value = function (string $key, mixed $fallback = '') use ($input): string {
            $current = $input[$key] ?? null;

            return filled($current)
                ? trim((string) $current)
                : trim((string) ($fallback ?? ''));
        };
        [$existingPrimerNombre, $existingSegundoNombre] = $this->splitRegistrationNameParts($persona->nombre ?? '');
        [$existingPrimerApellido, $existingSegundoApellido] = $this->splitRegistrationNameParts($persona->apellido ?? '');

        $persona->fill([
            'IdUsuario' => $persona->IdUsuario,
            'dni' => $value('registrationDni', $persona->dni ?: $this->registrationFallbackDni($profile)),
            'primer_nombre' => $value('registrationPrimerNombre', $persona->primer_nombre ?: $existingPrimerNombre),
            'segundo_nombre' => $value('registrationSegundoNombre', $persona->segundo_nombre ?: $existingSegundoNombre) ?: null,
            'primer_apellido' => $value('registrationPrimerApellido', $persona->primer_apellido ?: $existingPrimerApellido),
            'segundo_apellido' => $value('registrationSegundoApellido', $persona->segundo_apellido ?: $existingSegundoApellido) ?: null,
            'correo' => $value('registrationCorreo', $persona->correo ?: $persona->user?->email),
            'correoInstitucional' => filled($input['registrationCorreoInstitucional'] ?? null)
                ? trim((string) $input['registrationCorreoInstitucional'])
                : ($persona->correoInstitucional ?: null),
            'fechaNacimiento' => filled($input['registrationFechaNacimiento'] ?? null)
                ? $input['registrationFechaNacimiento']
                : $persona->fechaNacimiento,
            'sexo' => $value('registrationSexo', $persona->sexo),
            'direccion' => $value('registrationDireccion', $persona->direccion),
            'telefono' => $value('registrationTelefono', $persona->telefono),
            'numeroCuenta' => filled($input['registrationNumeroCuenta'] ?? null)
                ? trim((string) $input['registrationNumeroCuenta'])
                : ($persona->numeroCuenta ?: null),
            'numeroEmpleado' => filled($input['registrationNumeroEmpleado'] ?? null)
                ? trim((string) $input['registrationNumeroEmpleado'])
                : ($persona->numeroEmpleado ?: null),
            'IdNacionalidad' => $input['registrationIdNacionalidad'] ?: $persona->IdNacionalidad ?: $this->defaultHonduranNationalityId(),
            'IdTipoPerfil' => $profile->id,
        ]);

        if (! $persona->exists) {
            $persona->created_by = Auth::id() ?? 0;
        }

        $persona->save();

        $this->registrationPersonaId = $persona->id;

        return $persona;
    }

    private function registrationStateInput(): array
    {
        return [
            'registrationDni' => $this->registrationDni,
            'registrationNumeroCuenta' => $this->registrationNumeroCuenta,
            'registrationNumeroEmpleado' => $this->registrationNumeroEmpleado,
            'registrationPrimerNombre' => $this->registrationPrimerNombre,
            'registrationSegundoNombre' => $this->registrationSegundoNombre,
            'registrationPrimerApellido' => $this->registrationPrimerApellido,
            'registrationSegundoApellido' => $this->registrationSegundoApellido,
            'registrationCorreo' => $this->registrationCorreo,
            'registrationCorreoInstitucional' => $this->registrationCorreoInstitucional,
            'registrationFechaNacimiento' => $this->registrationFechaNacimiento,
            'registrationSexo' => $this->registrationSexo,
            'registrationDireccion' => $this->registrationDireccion,
            'registrationTelefono' => $this->registrationTelefono,
            'registrationIdNacionalidad' => $this->registrationIdNacionalidad ?: $this->defaultHonduranNationalityId(),
        ];
    }

    private function registrationPersonaForDuplicateCheck(Tipoperfil $profile): ?Persona
    {
        if ($this->registrationPersonaId) {
            return Persona::query()->find($this->registrationPersonaId);
        }

        $identifierField = $profile->tipo_identificador ?: 'dni';
        $identifierValue = match ($identifierField) {
            'numeroCuenta' => trim($this->registrationNumeroCuenta),
            'numeroEmpleado' => trim($this->registrationNumeroEmpleado),
            default => trim($this->registrationDni),
        };

        if (! filled($identifierValue)) {
            return null;
        }

        return Persona::query()->where($identifierField, $identifierValue)->first();
    }

    private function ensureRegistrationUserAccount(Persona $persona): ?array
    {
        if ($persona->IdUsuario) {
            $user = User::query()->find($persona->IdUsuario);

            if ($user && method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && ! $user->hasRole('participante')) {
                $user->assignRole('participante');
            }

            return null;
        }

        $email = trim((string) ($persona->correo ?: $this->registrationCorreo));
        $name = trim(($persona->nombre ?? '') . ' ' . ($persona->apellido ?? ''));

        if (! filled($email)) {
            return null;
        }

        $user = User::query()->where('email', $email)->first();
        $generatedPassword = null;

        if (! $user) {
            $generatedPassword = 'Evt-' . Str::upper(Str::random(10));

            $user = User::query()->create([
                'name' => $name ?: 'Participante EventIS',
                'email' => $email,
                'password' => Hash::make($generatedPassword),
            ]);
        }

        if (method_exists($user, 'hasRole') && method_exists($user, 'assignRole') && ! $user->hasRole('participante')) {
            $user->assignRole('participante');
        }

        $persona->IdUsuario = $user->id;
        $persona->save();
        $this->registrationUserId = $user->id;

        if (! $generatedPassword) {
            return null;
        }

        return [
            'name' => $name ?: $user->name,
            'email' => $user->email,
            'password' => $generatedPassword,
        ];
    }

    private function closeDocumentModals(): void
    {
        $this->showManualPaymentModal = false;
        $this->showReceiptModal = false;
        $this->showInvoiceModal = false;
        $this->showRefundModal = false;
        $this->showRefundReceiptModal = false;
        $this->refundCreatorPassword = '';
        $this->refundReason = '';
        $this->resetValidation();
    }

    private function peekInvoiceMetadata(EventoRegistro $registration, FacturacionConfig $config): array
    {
        $payload = $registration->payload_pago ?? [];
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

    private function ensureInvoiceMetadata(EventoRegistro $registration, FacturacionConfig $config): array
    {
        $payload = $registration->payload_pago ?? [];
        $invoice = data_get($payload, 'invoice');

        if (is_array($invoice) && filled($invoice['document_number'] ?? null)) {
            return [
                'enabled' => true,
                'document_number' => $invoice['document_number'],
                'issued_at' => isset($invoice['issued_at']) ? Carbon::parse($invoice['issued_at']) : now(),
            ];
        }

        return DB::transaction(function () use ($registration, $config) {
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

            $payload = $registration->payload_pago ?? [];
            $payload['invoice'] = [
                'document_number' => $documentNumber,
                'issued_at' => $issuedAt->toDateTimeString(),
                'cai' => $lockedConfig->cai,
                'config_id' => $lockedConfig->id,
                'generated_by' => Auth::id(),
            ];

            $registration->update([
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
