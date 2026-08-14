<?php

namespace App\Livewire\Evento;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Conferencia;
use App\Models\ConferenciaRegistroAsistencia;
use App\Models\Evento;
use App\Models\EventoCertificado;
use App\Models\EventoDocumentoProceso;
use App\Models\EventoInvitacion;
use App\Models\EventoRegistro;
use App\Models\EventoStaff;
use App\Models\Persona;
use App\Services\QRCodeService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use setasign\Fpdi\Fpdi;

class GestionarEventoPublicado extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public Evento $evento;
    public string $activeTab = 'invitados';
    public string $guestSearch = '';
    public string $guestDeliveryFilter = 'todos';
    public int $guestPerPage = 10;
    public bool $showGuestModal = false;
    public bool $showInvitationPreviewModal = false;
    public bool $showListExportModal = false;
    public bool $showDeleteConfirmModal = false;
    public string $guestName = '';
    public string $guestEmail = '';
    public string $guestPhone = '';
    public int $guestSlots = 1;
    public ?int $editingGuestId = null;
    public ?int $previewInvitationId = null;
    public string $previewQrCode = '';
    public string $previewMailTo = '';
    public string $previewWhatsappUrl = '';
    public string $staffSearch = '';
    public string $staffStatusFilter = 'todos';
    public int $staffPerPage = 10;
    public string $registrationSearch = '';
    public string $registrationStatusFilter = 'todos';
    public int $registrationPerPage = 10;
    public string $attendanceSearch = '';
    public int $attendancePerPage = 10;
    public bool $showAttendanceParticipantsModal = false;
    public ?int $selectedAttendanceConferenceId = null;
    public string $attendanceParticipantLookup = '';
    public string $attendanceParticipantRegistroId = '';
    public string $attendanceParticipantSelectedLabel = '';
    public string $attendanceParticipantSearch = '';
    public string $attendanceSelfQrCode = '';
    public int $attendanceParticipantPerPage = 10;
    public string $certificateSearch = '';
    public string $certificateTypeFilter = 'todos';
    public int $certificatePerPage = 10;
    public bool $showGeneralCertificateGenerationModal = false;
    public bool $generalCertificateGenerationProcessing = false;
    public bool $generalCertificateGenerationCompleted = false;
    public int $generalCertificateGenerationTotal = 0;
    public int $generalCertificateGenerationProcessed = 0;
    public int $generalCertificateGenerationGenerated = 0;
    public int $generalCertificateGenerationSkipped = 0;
    public int $generalCertificateGenerationBatchSize = 25;
    public int $generalCertificateGenerationLastRecordId = 0;
    public string $generalCertificateGenerationStatus = '';
    public bool $showDocumentGenerationConfirmModal = false;
    public string $documentGenerationType = '';
    public bool $showStaffModal = false;
    public bool $showStaffLinkModal = false;
    public bool $showStaffBadgePreviewModal = false;
    public bool $showRegistrationModal = false;
    public bool $showRegistrationBadgePreviewModal = false;
    public bool $showRegistrationBadgeGenerationModal = false;
    public bool $registrationBadgeGenerationProcessing = false;
    public bool $registrationBadgeGenerationCompleted = false;
    public int $registrationBadgeGenerationTotal = 0;
    public int $registrationBadgeGenerationProcessed = 0;
    public int $registrationBadgeGenerationGenerated = 0;
    public int $registrationBadgeGenerationBatchSize = 40;
    public int $registrationBadgeGenerationLastRecordId = 0;
    public string $registrationBadgeGenerationStatus = '';
    public string $registrationBadgeGenerationTempDirectory = '';
    public string $registrationBadgeGenerationOutputPath = '';
    public string $staffLookup = '';
    public string $staffPersonaId = '';
    public string $staffName = '';
    public string $staffEmail = '';
    public string $staffPhone = '';
    public ?int $editingStaffId = null;
    public ?int $previewStaffId = null;
    public ?int $editingRegistrationId = null;
    public ?int $previewRegistrationId = null;
    public string $previewStaffLink = '';
    public string $previewStaffBadgeQrCode = '';
    public string $previewStaffBadgeMailTo = '';
    public string $previewStaffBadgeWhatsappUrl = '';
    public string $previewStaffBadgeImageDataUri = '';
    public string $registrationPrimerNombre = '';
    public string $registrationSegundoNombre = '';
    public string $registrationPrimerApellido = '';
    public string $registrationSegundoApellido = '';
    public string $registrationCorreo = '';
    public string $registrationTelefono = '';
    public string $registrationDni = '';
    public string $registrationDireccion = '';
    public string $previewRegistrationBadgeQrCode = '';
    public string $previewRegistrationBadgeImageDataUri = '';
    public string $deleteContext = '';
    public ?int $deleteRecordId = null;
    public string $deleteRecordLabel = '';
    public string $listExportContext = 'invitados';
    public string $listExportFormat = 'pdf';
    public string $listExportDate = '';
    public bool $listExportIncludeSignature = false;

    private const DOCUMENT_TYPE_INVITATIONS = 'invitaciones_pdf';
    private const DOCUMENT_TYPE_STAFF_BADGES = 'staff_gafetes_pdf';
    private const DOCUMENT_TYPE_REGISTRATION_BADGES = 'inscripciones_gafetes_pdf';
    private const DOCUMENT_TYPE_GENERAL_CERTIFICATES = 'certificados_generales_pdf';

    protected array $allowedTabs = [
        'invitados',
        'staff',
        'inscripciones',
        'asistencia',
        'certificados',
    ];

    private const PAGE_SIZE_RATIOS = [
        'Carta' => [8.5, 11],
        'Oficio' => [8.5, 13],
        'A4' => [210, 297],
        'A3' => [297, 420],
        'Legal' => [8.5, 14],
        'Personalizado' => [1, 1.414],
    ];

    protected $queryString = [
        'guestSearch' => ['except' => ''],
        'guestDeliveryFilter' => ['except' => 'todos'],
        'staffSearch' => ['except' => ''],
        'staffStatusFilter' => ['except' => 'todos'],
        'registrationSearch' => ['except' => ''],
        'registrationStatusFilter' => ['except' => 'todos'],
        'attendanceSearch' => ['except' => ''],
        'certificateSearch' => ['except' => ''],
        'certificateTypeFilter' => ['except' => 'todos'],
    ];

    public function mount(Evento $evento): void
    {
        $this->evento = $evento;

        abort_unless($this->canAccessEventManagement(), 403);

        $this->evento->load([
            'modalidad',
            'tipoEvento',
            'localidad',
            'invitaciones',
            'staffMembers.persona.user',
            'registros.persona',
            'registros.tipoPerfil',
            'registros.metodoPago',
            'conferencias.registroAsistencias',
        ]);

        if ($this->evento->estado !== 'publicado') {
            $this->redirectRoute('eventos.configurar', ['evento' => $this->evento->id], navigate: true);

            return;
        }

    }

    public function setTab(string $tab): void
    {
        if (! in_array($tab, $this->allowedTabs, true)) {
            return;
        }

        $this->activeTab = $tab;
        $this->resetPage();
        $this->resetPage(pageName: 'staff');
        $this->resetPage(pageName: 'registros');
        $this->resetPage(pageName: 'asistencias');
        $this->resetPage(pageName: 'asistenciaParticipantes');
        $this->resetPage(pageName: 'certificados');
    }

    public function updatedGuestSearch(): void
    {
        $this->resetPage();
    }

    public function updatedGuestDeliveryFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStaffSearch(): void
    {
        $this->resetPage(pageName: 'staff');
    }

    public function updatedStaffStatusFilter(): void
    {
        $this->resetPage(pageName: 'staff');
    }

    public function updatedRegistrationSearch(): void
    {
        $this->resetPage(pageName: 'registros');
    }

    public function updatedRegistrationStatusFilter(): void
    {
        $this->resetPage(pageName: 'registros');
    }

    public function updatedAttendanceSearch(): void
    {
        $this->resetPage(pageName: 'asistencias');
    }

    public function updatedAttendanceParticipantSearch(): void
    {
        $this->resetPage(pageName: 'asistenciaParticipantes');
    }

    public function updatedCertificateSearch(): void
    {
        $this->resetPage(pageName: 'certificados');
    }

    public function updatedCertificateTypeFilter(): void
    {
        $this->resetPage(pageName: 'certificados');
    }

    public function openDocumentGenerationConfirmModal(string $type): void
    {
        $this->authorize('events.manage');

        if (! array_key_exists($type, $this->documentGenerationDefinitions())) {
            return;
        }

        $activeTask = EventoDocumentoProceso::query()
            ->where('evento_id', $this->evento->id)
            ->where('tipo', $type)
            ->whereIn('estado', ['pendiente', 'procesando'])
            ->latest('id')
            ->first();

        if ($activeTask) {
            session()->flash('error', 'Ya existe un proceso en ejecución para ' . $this->documentGenerationDefinitions()[$type]['short_label'] . '.');

            return;
        }

        $validationError = $this->validateDocumentGenerationRequest($type);

        if ($validationError !== null) {
            session()->flash('error', $validationError);

            return;
        }

        $this->documentGenerationType = $type;
        $this->showDocumentGenerationConfirmModal = true;
    }

    public function closeDocumentGenerationConfirmModal(): void
    {
        $this->showDocumentGenerationConfirmModal = false;
        $this->documentGenerationType = '';
    }

    public function confirmDocumentGeneration(): void
    {
        $this->authorize('events.manage');

        $type = $this->documentGenerationType;

        if ($type === '' || ! array_key_exists($type, $this->documentGenerationDefinitions())) {
            return;
        }

        $validationError = $this->validateDocumentGenerationRequest($type);

        if ($validationError !== null) {
            $this->closeDocumentGenerationConfirmModal();
            session()->flash('error', $validationError);

            return;
        }

        $definition = $this->documentGenerationDefinitions()[$type];
        $total = $this->documentGenerationTotal($type);

        if ($total <= 0) {
            $this->closeDocumentGenerationConfirmModal();
            session()->flash('error', $definition['empty_message']);

            return;
        }

        $task = EventoDocumentoProceso::query()
            ->firstOrNew([
                'evento_id' => $this->evento->id,
                'tipo' => $type,
            ]);

        $this->cleanupDocumentTaskFiles($task);

        $task->fill([
            'solicitado_por' => Auth::id(),
            'estado' => 'pendiente',
            'total' => $total,
            'procesados' => 0,
            'generados' => 0,
            'omitidos' => 0,
            'tamano_lote' => $definition['batch_size'],
            'ultimo_cursor_id' => null,
            'payload' => $this->documentGenerationPayload($type),
            'directorio_temporal' => '',
            'ruta_salida' => null,
            'mensaje_estado' => $definition['start_message'],
            'error_detalle' => null,
            'iniciado_at' => null,
            'completado_at' => null,
        ]);
        $task->save();

        try {
            $this->launchBackgroundDocumentProcessor();
        } catch (\Throwable $exception) {
            $this->failDocumentTask($task, 'No se pudo iniciar el proceso en segundo plano.');
            report($exception);
            session()->flash('error', 'No se pudo iniciar el proceso en segundo plano para generar el documento.');

            return;
        }

        $this->closeDocumentGenerationConfirmModal();

        session()->flash('message', 'Se inició la preparación en segundo plano de ' . $definition['short_label'] . '. Puedes seguir trabajando mientras el avance se actualiza en esta pantalla.');
    }

    public function processBackgroundDocumentTasks(): void
    {
        $task = EventoDocumentoProceso::query()
            ->where('evento_id', $this->evento->id)
            ->whereIn('estado', ['pendiente', 'procesando'])
            ->orderBy('updated_at')
            ->orderBy('id')
            ->first();

        if (! $task) {
            return;
        }

        try {
            if ($task->estado === 'pendiente') {
                $this->initializeDocumentTask($task);
            }

            match ($task->tipo) {
                self::DOCUMENT_TYPE_INVITATIONS => $this->processInvitationDocumentBatch($task),
                self::DOCUMENT_TYPE_STAFF_BADGES => $this->processStaffBadgeDocumentBatch($task),
                self::DOCUMENT_TYPE_REGISTRATION_BADGES => $this->processRegistrationBadgeDocumentBatch($task),
                self::DOCUMENT_TYPE_GENERAL_CERTIFICATES => $this->processGeneralCertificateDocumentBatch($task),
                default => $this->failDocumentTask($task, 'Tipo de proceso no soportado.'),
            };
        } catch (\Throwable $exception) {
            $this->failDocumentTask($task, $exception->getMessage());
            report($exception);
        }
    }

    public function downloadGeneratedDocumentTask(int $taskId)
    {
        $this->authorize('events.manage');

        $task = EventoDocumentoProceso::query()
            ->where('evento_id', $this->evento->id)
            ->findOrFail($taskId);

        if ($task->estado !== 'completado' || ! $task->ruta_salida) {
            session()->flash('error', 'El archivo aún no está disponible para descargarse.');

            return null;
        }

        $absolutePath = storage_path('app/' . $task->ruta_salida);

        if (! file_exists($absolutePath)) {
            session()->flash('error', 'No se encontró el PDF generado. Intenta crear el documento nuevamente.');

            return null;
        }

        return response()->streamDownload(function () use ($absolutePath): void {
            readfile($absolutePath);
        }, basename($absolutePath), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function cancelDocumentTask(int $taskId): void
    {
        $this->authorize('events.manage');

        $task = EventoDocumentoProceso::query()
            ->where('evento_id', $this->evento->id)
            ->findOrFail($taskId);

        if (! in_array($task->estado, ['pendiente', 'procesando'], true)) {
            session()->flash('error', 'El proceso ya no se puede cancelar.');

            return;
        }

        $this->cleanupDocumentTaskFiles($task);

        $task->estado = 'cancelado';
        $task->mensaje_estado = 'El proceso fue cancelado por el usuario.';
        $task->error_detalle = null;
        $task->ruta_salida = null;
        $task->directorio_temporal = null;
        $task->completado_at = now();
        $task->save();

        session()->flash('message', 'Se canceló el proceso correctamente.');
    }

    public function openGeneralCertificateGenerationModal(): void
    {
        $this->authorize('events.manage');

        if (! $this->evento->genera_diploma_participacion) {
            session()->flash('error', 'Este evento no está configurado para generar diplomas de participación general.');

            return;
        }

        if (! $this->generalParticipationCertificateDesignConfigured()) {
            session()->flash('error', 'Configura primero la plantilla de diplomas de participación general del evento.');

            return;
        }

        $total = $this->evento->registros()
            ->whereNotNull('persona_id')
            ->whereHas('persona')
            ->count();

        if ($total === 0) {
            session()->flash('error', 'No hay inscripciones registradas para generar certificados generales.');

            return;
        }

        $this->resetGeneralCertificateGenerationState();
        $this->generalCertificateGenerationTotal = $total;
        $this->generalCertificateGenerationStatus = 'Se generarán los certificados en lotes para evitar bloqueos del sistema.';
        $this->showGeneralCertificateGenerationModal = true;
    }

    public function closeGeneralCertificateGenerationModal(): void
    {
        if ($this->generalCertificateGenerationProcessing) {
            return;
        }

        $this->showGeneralCertificateGenerationModal = false;
        $this->resetGeneralCertificateGenerationState();
    }

    public function startGeneralCertificateGeneration(): void
    {
        $this->authorize('events.manage');

        if (! $this->showGeneralCertificateGenerationModal) {
            $this->openGeneralCertificateGenerationModal();

            if (! $this->showGeneralCertificateGenerationModal) {
                return;
            }
        }

        $this->generalCertificateGenerationProcessing = true;
        $this->generalCertificateGenerationCompleted = false;
        $this->generalCertificateGenerationProcessed = 0;
        $this->generalCertificateGenerationGenerated = 0;
        $this->generalCertificateGenerationSkipped = 0;
        $this->generalCertificateGenerationLastRecordId = 0;
        $this->generalCertificateGenerationStatus = 'Iniciando generación por lotes...';
    }

    public function processGeneralCertificateGenerationBatch(): void
    {
        if (! $this->generalCertificateGenerationProcessing) {
            return;
        }

        $records = $this->evento->registros()
            ->with(['persona.tipoPerfil', 'tipoPerfil', 'evento.precios.moneda'])
            ->where('id', '>', $this->generalCertificateGenerationLastRecordId)
            ->orderBy('id')
            ->limit($this->generalCertificateGenerationBatchSize)
            ->get();

        if ($records->isEmpty()) {
            $this->generalCertificateGenerationProcessing = false;
            $this->generalCertificateGenerationCompleted = true;
            $this->generalCertificateGenerationStatus = 'Proceso completado. Los certificados generales ya fueron generados.';
            $this->refreshEventRelations();

            session()->flash(
                'message',
                'Se generaron o actualizaron ' . $this->generalCertificateGenerationGenerated
                . ' certificados generales del evento.'
                . ($this->generalCertificateGenerationSkipped > 0 ? ' Se omitieron ' . $this->generalCertificateGenerationSkipped . ' registros sin persona válida.' : '')
            );

            return;
        }

        foreach ($records as $record) {
            $this->generalCertificateGenerationLastRecordId = (int) $record->id;
            $this->generalCertificateGenerationProcessed++;

            if (! $record->persona) {
                $this->generalCertificateGenerationSkipped++;
                continue;
            }

            $this->persistCertificatePdf('participacion_general', $record, null);
            $this->generalCertificateGenerationGenerated++;
        }

        $remaining = max(0, $this->generalCertificateGenerationTotal - $this->generalCertificateGenerationProcessed);
        $this->generalCertificateGenerationStatus = $remaining > 0
            ? 'Lote procesado. Quedan ' . $remaining . ' registros pendientes.'
            : 'Finalizando generación...';
    }

    public function updatedAttendanceParticipantLookup(string $value): void
    {
        if (trim($value) !== trim($this->attendanceParticipantSelectedLabel)) {
            $this->attendanceParticipantRegistroId = '';
        }
    }

    public function updatedListExportFormat(string $value): void
    {
        if ($value !== 'pdf') {
            $this->listExportDate = '';
            $this->listExportIncludeSignature = false;
        }

        $this->resetValidation('listExportDate', 'listExportIncludeSignature');
    }

    public function openGuestModal(): void
    {
        $this->resetGuestForm();
        $this->showGuestModal = true;
    }

    public function closeGuestModal(): void
    {
        $this->showGuestModal = false;
        $this->resetGuestForm();
    }

    public function editGuestInvitation(int $invitationId): void
    {
        $this->authorize('events.manage');

        $invitation = $this->evento->invitaciones()->findOrFail($invitationId);

        $this->resetGuestForm();
        $this->editingGuestId = $invitation->id;
        $this->guestName = $invitation->nombre_invitado;
        $this->guestEmail = $invitation->correo_invitado ?? '';
        $this->guestPhone = $invitation->telefono_invitado ?? '';
        $this->guestSlots = max(1, (int) $invitation->cupos);
        $this->showGuestModal = true;
    }

    public function openGuestListExportModal(): void
    {
        $this->authorize('events.manage');

        $this->resetListExportModal('invitados');
        $this->showListExportModal = true;
    }

    public function openStaffListExportModal(): void
    {
        $this->authorize('events.manage');

        $this->resetListExportModal('staff');
        $this->showListExportModal = true;
    }

    public function openRegistrationListExportModal(): void
    {
        $this->authorize('events.manage');

        $this->resetListExportModal('registros');
        $this->showListExportModal = true;
    }

    public function openAttendanceParticipantsModal(int $conferenceId): void
    {
        $this->authorize('events.manage');

        $conference = $this->evento->conferencias()->findOrFail($conferenceId);

        $this->selectedAttendanceConferenceId = $conference->id;
        $this->attendanceParticipantLookup = '';
        $this->attendanceParticipantRegistroId = '';
        $this->attendanceParticipantSelectedLabel = '';
        $this->attendanceParticipantSearch = '';
        $this->refreshAttendanceSelfQr($conference);
        $this->resetPage(pageName: 'asistenciaParticipantes');
        $this->showAttendanceParticipantsModal = true;
    }

    public function closeAttendanceParticipantsModal(): void
    {
        $this->showAttendanceParticipantsModal = false;
        $this->selectedAttendanceConferenceId = null;
        $this->attendanceParticipantLookup = '';
        $this->attendanceParticipantRegistroId = '';
        $this->attendanceParticipantSelectedLabel = '';
        $this->attendanceParticipantSearch = '';
        $this->attendanceSelfQrCode = '';
        $this->resetValidation();
    }

    public function closeListExportModal(): void
    {
        $this->showListExportModal = false;
        $this->resetValidation();
    }

    public function exportList()
    {
        $this->authorize('events.manage');

        $this->validate([
            'listExportContext' => 'required|in:invitados,staff,registros',
            'listExportFormat' => 'required|in:pdf,excel',
            'listExportDate' => $this->listExportFormat === 'pdf' ? 'required|date' : 'nullable',
            'listExportIncludeSignature' => 'nullable|boolean',
        ], [], [
            'listExportDate' => 'fecha del documento',
        ]);

        if ($this->listExportFormat === 'pdf') {
            return match ($this->listExportContext) {
                'invitados' => $this->downloadGuestListPdf($this->listExportDate),
                'staff' => $this->downloadStaffListPdf($this->listExportDate),
                default => $this->downloadRegistrationListPdf($this->listExportDate, $this->listExportIncludeSignature),
            };
        }

        return match ($this->listExportContext) {
            'invitados' => $this->downloadGuestListExcel(),
            'staff' => $this->downloadStaffListExcel(),
            default => $this->downloadRegistrationListExcel(),
        };
    }

    public function openInvitationPreview(int $invitationId): void
    {
        $this->authorize('events.manage');

        $invitation = $this->evento->invitaciones()->findOrFail($invitationId);
        $this->previewInvitationId = $invitation->id;
        $this->previewQrCode = QRCodeService::generateTextQRCode($invitation->codigo, 240);
        $this->previewMailTo = $this->buildInvitationMailto($invitation);
        $this->previewWhatsappUrl = $this->buildInvitationWhatsappUrl($invitation);
        $this->showInvitationPreviewModal = true;
    }

    public function downloadInvitationImage(): \Symfony\Component\HttpFoundation\StreamedResponse|null
    {
        $this->authorize('events.manage');

        $invitation = $this->selectedInvitation();

        if (! $invitation) {
            return null;
        }

        $png = $this->renderInvitationPng($invitation);
        $filename = 'invitacion-' . Str::slug($invitation->nombre_invitado ?: 'invitado') . '-' . strtolower($invitation->codigo) . '.png';

        return Response::streamDownload(function () use ($png): void {
            echo $png;
        }, $filename, [
            'Content-Type' => 'image/png',
        ]);
    }

    public function closeInvitationPreview(): void
    {
        $this->showInvitationPreviewModal = false;
        $this->previewInvitationId = null;
        $this->previewQrCode = '';
        $this->previewMailTo = '';
        $this->previewWhatsappUrl = '';
    }

    public function downloadGuestListPdf(?string $selectedDate = null)
    {
        $this->authorize('events.manage');

        $invitations = $this->guestInvitationsQuery()->get();

        if ($invitations->isEmpty()) {
            session()->flash('error', 'No hay invitados para exportar con los filtros actuales.');

            return null;
        }

        $pdf = PDF::loadView('pdf.evento-invitados-listado', [
            'evento' => $this->evento,
            'invitations' => $invitations,
            'selectedDate' => $selectedDate,
        ])->setPaper('letter', 'portrait');

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'listado-invitados-evento-' . $this->evento->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadGuestListExcel()
    {
        $this->authorize('events.manage');

        $invitations = $this->guestInvitationsQuery()->get();

        if ($invitations->isEmpty()) {
            session()->flash('error', 'No hay invitados para exportar con los filtros actuales.');

            return null;
        }

        $html = view('exports.evento-invitados-listado-excel', [
            'evento' => $this->evento,
            'invitations' => $invitations,
        ])->render();

        return response()->streamDownload(function () use ($html): void {
            echo $html;
        }, 'listado-invitados-evento-' . $this->evento->id . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function downloadInvitationsPdf()
    {
        $this->authorize('events.manage');

        $invitations = $this->evento->invitaciones()->orderBy('nombre_invitado')->get();

        if ($invitations->isEmpty()) {
            session()->flash('error', 'No hay invitaciones registradas para exportar.');

            return null;
        }

        $pdfOrientation = $this->invitationPdfOrientation();
        $paperDefinition = $this->invitationPdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->invitationPdfPageDimensions($paperDefinition, $pdfOrientation);

        $payloads = $invitations->map(function (EventoInvitacion $invitation) {
            return [
                'nombre_invitado' => $invitation->nombre_invitado,
                'codigo' => $invitation->codigo,
                'cupos' => $invitation->cupos,
                'image_data_uri' => 'data:image/png;base64,' . base64_encode($this->renderInvitationPng($invitation)),
            ];
        })->all();

        $pdf = PDF::loadView('pdf.evento-invitaciones-lote', [
            'evento' => $this->evento,
            'invitations' => $payloads,
            'pdfOrientation' => $pdfOrientation,
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'invitaciones-evento-' . $this->evento->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadStaffListPdf(?string $selectedDate = null)
    {
        $this->authorize('events.manage');

        $staffMembers = $this->staffMembersQuery()->get();

        if ($staffMembers->isEmpty()) {
            session()->flash('error', 'No hay miembros del staff para exportar con los filtros actuales.');

            return null;
        }

        $pdf = PDF::loadView('pdf.evento-staff-listado', [
            'evento' => $this->evento,
            'staffMembers' => $staffMembers,
            'selectedDate' => $selectedDate,
        ])->setPaper('letter', 'portrait');

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'listado-staff-evento-' . $this->evento->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadStaffListExcel()
    {
        $this->authorize('events.manage');

        $staffMembers = $this->staffMembersQuery()->get();

        if ($staffMembers->isEmpty()) {
            session()->flash('error', 'No hay miembros del staff para exportar con los filtros actuales.');

            return null;
        }

        $html = view('exports.evento-staff-listado-excel', [
            'evento' => $this->evento,
            'staffMembers' => $staffMembers,
        ])->render();

        return response()->streamDownload(function () use ($html): void {
            echo $html;
        }, 'listado-staff-evento-' . $this->evento->id . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function downloadStaffBadgesPdf()
    {
        $this->authorize('events.manage');

        $staffMembers = $this->evento->staffMembers()
            ->with('persona.user', 'persona.tipoPerfil')
            ->whereNotNull('perfil_completado_at')
            ->orderBy('nombre')
            ->get();

        if ($staffMembers->isEmpty()) {
            session()->flash('error', 'No hay miembros del staff con perfil completo para exportar gafetes.');

            return null;
        }

        $pdfOrientation = $this->staffBadgePdfOrientation();
        $paperDefinition = $this->staffBadgePdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->staffBadgePdfPageDimensions($paperDefinition, $pdfOrientation);

        $payloads = $staffMembers->map(function (EventoStaff $staffMember) {
            return [
                'nombre' => $staffMember->nombre,
                'codigo' => $this->staffBadgeCode($staffMember),
                'image_data_uri' => 'data:image/png;base64,' . base64_encode($this->renderStaffBadgePng($staffMember)),
            ];
        })->all();

        $pdf = PDF::loadView('pdf.evento-staff-gafetes-lote', [
            'evento' => $this->evento,
            'staffBadges' => $payloads,
            'pdfOrientation' => $pdfOrientation,
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'gafetes-staff-evento-' . $this->evento->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function saveGuestInvitation(): void
    {
        $this->authorize('events.manage');
        $isEditing = (bool) $this->editingGuestId;

        $validated = $this->validate([
            'guestName' => 'required|string|max:255',
            'guestEmail' => 'required|email|max:255',
            'guestPhone' => 'required|string|max:30',
            'guestSlots' => 'required|integer|min:1|max:20',
        ], [], [
            'guestName' => 'nombre del invitado',
            'guestEmail' => 'correo electrónico',
            'guestPhone' => 'número de teléfono',
            'guestSlots' => 'cupos',
        ]);

        if ($this->editingGuestId) {
            $invitation = $this->evento->invitaciones()->findOrFail($this->editingGuestId);
            $invitation->update([
                'nombre_invitado' => trim($validated['guestName']),
                'correo_invitado' => trim($validated['guestEmail']),
                'telefono_invitado' => trim($validated['guestPhone']),
                'cupos' => max($invitation->cupos_utilizados, (int) $validated['guestSlots']),
            ]);
        } else {
            EventoInvitacion::query()->create([
                'evento_id' => $this->evento->id,
                'codigo' => $this->generateInvitationCode(),
                'nombre_invitado' => trim($validated['guestName']),
                'correo_invitado' => trim($validated['guestEmail']),
                'telefono_invitado' => trim($validated['guestPhone']),
                'cupos' => $validated['guestSlots'],
                'cupos_utilizados' => 0,
                'activa' => true,
            ]);
        }

        $this->refreshEventRelations();

        $this->closeGuestModal();

        session()->flash('message', $isEditing
            ? 'Invitado actualizado correctamente.'
            : 'Invitado agregado correctamente. El envío por correo y WhatsApp se integrará en el siguiente paso.');
    }

    public function sendInvitationByEmail(): void
    {
        $this->authorize('events.manage');

        $invitation = $this->selectedInvitation();

        if (! $invitation || ! $invitation->correo_invitado) {
            session()->flash('error', 'La invitación no tiene correo electrónico configurado.');

            return;
        }

        $this->markInvitationAsSent($invitation, 'correo');
        $this->dispatch('open-external-link', url: $this->buildInvitationMailto($invitation), target: 'self');
    }

    public function sendInvitationByWhatsapp(): void
    {
        $this->authorize('events.manage');

        $invitation = $this->selectedInvitation();

        if (! $invitation || ! $invitation->telefono_invitado) {
            session()->flash('error', 'La invitación no tiene número de teléfono configurado.');

            return;
        }

        $this->markInvitationAsSent($invitation, 'whatsapp');
        $this->dispatch('open-external-link', url: $this->buildInvitationWhatsappUrl($invitation), target: 'blank');
    }

    public function openStaffModal(): void
    {
        $this->authorize('events.manage');

        $this->resetStaffForm();
        $this->showStaffModal = true;
    }

    public function closeStaffModal(): void
    {
        $this->showStaffModal = false;
        $this->resetStaffForm();
    }

    public function editStaffMember(int $staffId): void
    {
        $this->authorize('events.manage');

        $staffMember = $this->evento->staffMembers()->with('persona.user')->findOrFail($staffId);

        $this->resetStaffForm();
        $this->editingStaffId = $staffMember->id;
        $this->staffPersonaId = $staffMember->persona_id ? (string) $staffMember->persona_id : '';
        $this->staffName = $staffMember->nombre;
        $this->staffEmail = $staffMember->correo ?? '';
        $this->staffPhone = $staffMember->telefono ?? '';
        $this->showStaffModal = true;
    }

    public function updatedStaffPersonaId(): void
    {
        if ($this->staffPersonaId === '') {
            return;
        }

        $persona = Persona::query()->with('user')->find($this->staffPersonaId);

        if (! $persona) {
            return;
        }

        $this->staffName = trim($persona->nombre . ' ' . $persona->apellido);
        $this->staffEmail = $persona->correo ?? '';
        $this->staffPhone = $persona->telefono ?? '';
    }

    public function saveStaffMember(): void
    {
        $this->authorize('events.manage');
        $isEditing = (bool) $this->editingStaffId;

        $persona = $this->staffPersonaId !== ''
            ? Persona::query()->with('user')->findOrFail($this->staffPersonaId)
            : null;

        $rules = $persona
            ? [
                'staffPersonaId' => 'required|exists:personas,id',
            ]
            : [
                'staffName' => 'required|string|max:255',
                'staffEmail' => 'required|email|max:255',
                'staffPhone' => 'required|string|max:50',
            ];

        $this->validate($rules, [], [
            'staffPersonaId' => 'usuario registrado',
            'staffName' => 'nombre del miembro del staff',
            'staffEmail' => 'correo',
            'staffPhone' => 'telefono',
        ]);

        $existsQuery = $this->evento->staffMembers();

        if ($this->editingStaffId) {
            $existsQuery->where('id', '!=', $this->editingStaffId);
        }

        if ($persona) {
            $alreadyExists = (clone $existsQuery)->where('persona_id', $persona->id)->exists();
        } else {
            $alreadyExists = (clone $existsQuery)
                ->whereNull('persona_id')
                ->where('correo', trim($this->staffEmail))
                ->exists();
        }

        if ($alreadyExists) {
            $this->addError('staffPersonaId', 'Este miembro ya fue agregado al staff del evento.');

            return;
        }

        if ($this->editingStaffId) {
            $staffMember = $this->evento->staffMembers()->findOrFail($this->editingStaffId);
            $staffMember->update([
                'persona_id' => $persona?->id,
                'nombre' => $persona ? trim($persona->nombre . ' ' . $persona->apellido) : trim($this->staffName),
                'correo' => $persona?->correo ?: trim($this->staffEmail),
                'telefono' => $persona?->telefono ?: trim($this->staffPhone),
                'perfil_completado_at' => $staffMember->perfil_completado_at ?: ($persona?->user ? now() : null),
                'activo' => true,
            ]);
        } else {
            $staffMember = EventoStaff::query()->create([
                'evento_id' => $this->evento->id,
                'persona_id' => $persona?->id,
                'nombre' => $persona ? trim($persona->nombre . ' ' . $persona->apellido) : trim($this->staffName),
                'correo' => $persona?->correo ?: trim($this->staffEmail),
                'telefono' => $persona?->telefono ?: trim($this->staffPhone),
                'staff_access_token' => (string) Str::uuid(),
                'invitado_at' => now(),
                'perfil_completado_at' => $persona?->user ? now() : null,
                'activo' => true,
            ]);
        }

        if ($persona?->user && method_exists($persona->user, 'assignRole') && ! $persona->user->hasRole('staff-evento')) {
            $persona->user->assignRole('staff-evento');
        }

        $this->evento->load(['staffMembers.persona.user']);
        $this->closeStaffModal();
        if (! $isEditing && ! $persona?->user) {
            $this->openStaffLinkModal($staffMember->id);
        }

        session()->flash('message', $isEditing
            ? 'Miembro del staff actualizado correctamente.'
            : ($persona?->user
                ? 'Miembro del staff agregado correctamente. Ya tiene acceso al evento.'
                : 'Miembro del staff agregado. Comparte el enlace para que complete su perfil.'));
    }

    public function confirmDeleteGuest(int $invitationId): void
    {
        $this->authorize('events.manage');

        $invitation = $this->evento->invitaciones()->findOrFail($invitationId);
        $this->deleteContext = 'guest';
        $this->deleteRecordId = $invitation->id;
        $this->deleteRecordLabel = $invitation->nombre_invitado;
        $this->showDeleteConfirmModal = true;
    }

    public function confirmDeleteStaff(int $staffId): void
    {
        $this->authorize('events.manage');

        $staffMember = $this->evento->staffMembers()->findOrFail($staffId);
        $this->deleteContext = 'staff';
        $this->deleteRecordId = $staffMember->id;
        $this->deleteRecordLabel = $staffMember->nombre;
        $this->showDeleteConfirmModal = true;
    }

    public function closeDeleteConfirmModal(): void
    {
        $this->showDeleteConfirmModal = false;
        $this->deleteContext = '';
        $this->deleteRecordId = null;
        $this->deleteRecordLabel = '';
    }

    public function deleteSelectedRecord(): void
    {
        $this->authorize('events.manage');

        if (! $this->deleteRecordId || ! in_array($this->deleteContext, ['guest', 'staff', 'registration', 'attendance'], true)) {
            return;
        }

        if ($this->deleteContext === 'guest') {
            $invitation = $this->evento->invitaciones()->findOrFail($this->deleteRecordId);
            $invitation->delete();
            $message = 'Invitado eliminado correctamente.';
        } elseif ($this->deleteContext === 'staff') {
            $staffMember = $this->evento->staffMembers()->findOrFail($this->deleteRecordId);
            $staffMember->update(['activo' => false]);
            $staffMember->delete();
            $message = 'Miembro del staff eliminado correctamente.';
        } elseif ($this->deleteContext === 'registration') {
            $registrationRecord = $this->evento->registros()->findOrFail($this->deleteRecordId);
            $registrationRecord->delete();
            $message = 'Inscripción eliminada correctamente.';
        } else {
            $attendanceRecord = ConferenciaRegistroAsistencia::query()
                ->whereHas('conferencia', fn ($query) => $query->where('IdEvento', $this->evento->id))
                ->findOrFail($this->deleteRecordId);
            $attendanceRecord->delete();
            $message = 'Asistencia eliminada correctamente.';
        }

        $this->refreshEventRelations();
        $this->closeDeleteConfirmModal();
        session()->flash('message', $message);
    }

    public function openStaffLinkModal(int $staffId): void
    {
        $staffMember = $this->evento->staffMembers()->with('persona.user')->findOrFail($staffId);

        $this->previewStaffId = $staffMember->id;
        $this->previewStaffLink = $staffMember->onboarding_url ?? '';
        $this->showStaffLinkModal = true;
    }

    public function closeStaffLinkModal(): void
    {
        $this->showStaffLinkModal = false;
        $this->previewStaffId = null;
        $this->previewStaffLink = '';
    }

    public function openStaffBadgePreview(int $staffId): void
    {
        $this->authorize('events.manage');

        $staffMember = $this->evento->staffMembers()->with('persona.user')->findOrFail($staffId);

        abort_if(! $staffMember->perfil_completado_at, 403, 'El miembro del staff aún no ha completado su perfil.');

        $this->previewStaffId = $staffMember->id;
        $this->previewStaffBadgeQrCode = QRCodeService::generateTextQRCode($this->staffBadgeCode($staffMember), 240);
        $this->previewStaffBadgeMailTo = $this->buildStaffBadgeMailto($staffMember);
        $this->previewStaffBadgeWhatsappUrl = $this->buildStaffBadgeWhatsappUrl($staffMember);
        $this->previewStaffBadgeImageDataUri = 'data:image/png;base64,' . base64_encode($this->renderStaffBadgePng($staffMember));
        $this->showStaffBadgePreviewModal = true;
    }

    public function closeStaffBadgePreview(): void
    {
        $this->showStaffBadgePreviewModal = false;
        $this->previewStaffId = null;
        $this->previewStaffBadgeQrCode = '';
        $this->previewStaffBadgeMailTo = '';
        $this->previewStaffBadgeWhatsappUrl = '';
        $this->previewStaffBadgeImageDataUri = '';
    }

    public function downloadStaffBadgeImage(): \Symfony\Component\HttpFoundation\StreamedResponse|null
    {
        $this->authorize('events.manage');

        $staffMember = $this->selectedStaffMember();

        if (! $staffMember) {
            return null;
        }

        $png = $this->renderStaffBadgePng($staffMember);
        $filename = 'gafete-staff-' . Str::slug($staffMember->nombre ?: 'staff') . '.png';

        return Response::streamDownload(function () use ($png): void {
            echo $png;
        }, $filename, [
            'Content-Type' => 'image/png',
        ]);
    }

    public function sendStaffBadgeByEmail(): void
    {
        $this->authorize('events.manage');

        $staffMember = $this->selectedStaffMember();

        if (! $staffMember || ! $staffMember->correo) {
            session()->flash('error', 'El miembro del staff no tiene correo configurado.');

            return;
        }

        $this->dispatch('open-external-link', url: $this->buildStaffBadgeMailto($staffMember), target: 'self');
    }

    public function sendStaffBadgeByWhatsapp(): void
    {
        $this->authorize('events.manage');

        $staffMember = $this->selectedStaffMember();

        if (! $staffMember || ! $staffMember->telefono) {
            session()->flash('error', 'El miembro del staff no tiene teléfono configurado.');

            return;
        }

        $this->dispatch('open-external-link', url: $this->buildStaffBadgeWhatsappUrl($staffMember), target: 'blank');
    }

    public function downloadRegistrationListPdf(?string $selectedDate = null, bool $includeSignature = false)
    {
        $this->authorize('events.manage');

        $registrationRecords = $this->registrationRecordsQuery()
            ->get()
            ->sortBy(function ($registrationRecord) {
                $persona = $registrationRecord->persona;

                return mb_strtolower(trim(($persona?->nombre ?? '') . ' ' . ($persona?->apellido ?? '')));
            }, SORT_NATURAL)
            ->values();

        if ($registrationRecords->isEmpty()) {
            session()->flash('error', 'No hay inscripciones para exportar con los filtros actuales.');

            return null;
        }

        $pdf = PDF::loadView('pdf.evento-inscripciones-listado', [
            'evento' => $this->evento,
            'registrationRecords' => $registrationRecords,
            'selectedDate' => $selectedDate,
            'includeSignature' => $includeSignature,
        ])->setPaper('letter', 'portrait');

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'listado-inscripciones-evento-' . $this->evento->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadRegistrationListExcel()
    {
        $this->authorize('events.manage');

        $registrationRecords = $this->registrationRecordsQuery()
            ->get()
            ->sortBy(function ($registrationRecord) {
                $persona = $registrationRecord->persona;

                return mb_strtolower(trim(($persona?->nombre ?? '') . ' ' . ($persona?->apellido ?? '')));
            }, SORT_NATURAL)
            ->values();

        if ($registrationRecords->isEmpty()) {
            session()->flash('error', 'No hay inscripciones para exportar con los filtros actuales.');

            return null;
        }

        $html = view('exports.evento-inscripciones-listado-excel', [
            'evento' => $this->evento,
            'registrationRecords' => $registrationRecords,
        ])->render();

        return response()->streamDownload(function () use ($html): void {
            echo $html;
        }, 'listado-inscripciones-evento-' . $this->evento->id . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function openRegistrationBadgeGenerationModal(): void
    {
        $this->authorize('events.manage');

        $total = $this->registrationRecordsQuery()->count();

        if ($total === 0) {
            session()->flash('error', 'No hay inscripciones registradas para exportar gafetes.');

            return;
        }

        $this->resetRegistrationBadgeGenerationState();
        $this->registrationBadgeGenerationTotal = $total;
        $this->registrationBadgeGenerationStatus = 'Se generará el PDF de gafetes por lotes para evitar tiempos de espera y bloqueos.';
        $this->showRegistrationBadgeGenerationModal = true;
    }

    public function closeRegistrationBadgeGenerationModal(): void
    {
        if ($this->registrationBadgeGenerationProcessing) {
            return;
        }

        $this->showRegistrationBadgeGenerationModal = false;
        $this->resetRegistrationBadgeGenerationState();
    }

    public function startRegistrationBadgeGeneration(): void
    {
        $this->authorize('events.manage');

        if (! $this->showRegistrationBadgeGenerationModal) {
            $this->openRegistrationBadgeGenerationModal();

            if (! $this->showRegistrationBadgeGenerationModal) {
                return;
            }
        }

        $this->cleanupRegistrationBadgeTemporaryFiles();

        $this->registrationBadgeGenerationTempDirectory = 'tmp/registration-badges/evento-' . $this->evento->id . '-' . Str::uuid();
        Storage::disk('local')->makeDirectory($this->registrationBadgeGenerationTempDirectory);

        $this->registrationBadgeGenerationProcessing = true;
        $this->registrationBadgeGenerationCompleted = false;
        $this->registrationBadgeGenerationProcessed = 0;
        $this->registrationBadgeGenerationGenerated = 0;
        $this->registrationBadgeGenerationLastRecordId = 0;
        $this->registrationBadgeGenerationOutputPath = '';
        $this->registrationBadgeGenerationStatus = 'Iniciando la generación de lotes de gafetes...';
    }

    public function processRegistrationBadgeGenerationBatch(): void
    {
        if (! $this->registrationBadgeGenerationProcessing) {
            return;
        }

        $records = $this->registrationRecordsQuery()
            ->where('evento_registros.id', '>', $this->registrationBadgeGenerationLastRecordId)
            ->limit($this->registrationBadgeGenerationBatchSize)
            ->get();

        if ($records->isEmpty()) {
            $this->finalizeRegistrationBadgeGeneration();

            return;
        }

        $batchNumber = (int) floor($this->registrationBadgeGenerationProcessed / max(1, $this->registrationBadgeGenerationBatchSize)) + 1;
        $batchPdfPath = $this->buildRegistrationBadgeBatchPdf($records, $batchNumber);

        if ($batchPdfPath !== null) {
            $this->registrationBadgeGenerationGenerated += $records->count();
        }

        foreach ($records as $record) {
            $this->registrationBadgeGenerationLastRecordId = (int) $record->id;
            $this->registrationBadgeGenerationProcessed++;
        }

        $remaining = max(0, $this->registrationBadgeGenerationTotal - $this->registrationBadgeGenerationProcessed);
        $this->registrationBadgeGenerationStatus = $remaining > 0
            ? 'Lote generado. Quedan ' . $remaining . ' gafetes pendientes.'
            : 'Unificando el PDF final de gafetes...';
    }

    public function downloadGeneratedRegistrationBadgesPdf()
    {
        $this->authorize('events.manage');

        if (! $this->registrationBadgeGenerationCompleted || $this->registrationBadgeGenerationOutputPath === '') {
            session()->flash('error', 'El archivo final de gafetes aún no está listo.');

            return null;
        }

        $absolutePath = storage_path('app/' . $this->registrationBadgeGenerationOutputPath);

        if (! file_exists($absolutePath)) {
            session()->flash('error', 'No se encontró el PDF final generado. Intenta ejecutar nuevamente el proceso.');

            return null;
        }

        return response()->streamDownload(function () use ($absolutePath): void {
            readfile($absolutePath);
        }, basename($absolutePath), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function openRegistrationBadgePreview(int $registrationId): void
    {
        $this->authorize('events.manage');

        $registrationRecord = $this->evento->registros()->with('persona.tipoPerfil')->findOrFail($registrationId);

        $this->previewRegistrationId = $registrationRecord->id;
        $this->previewRegistrationBadgeQrCode = QRCodeService::generateTextQRCode($this->participantBadgeCode($registrationRecord), 240);
        $this->previewRegistrationBadgeImageDataUri = 'data:image/png;base64,' . base64_encode($this->renderParticipantBadgePng($registrationRecord));
        $this->showRegistrationBadgePreviewModal = true;
    }

    public function closeRegistrationBadgePreview(): void
    {
        $this->showRegistrationBadgePreviewModal = false;
        $this->previewRegistrationId = null;
        $this->previewRegistrationBadgeQrCode = '';
        $this->previewRegistrationBadgeImageDataUri = '';
    }

    public function downloadRegistrationBadgeImage(): \Symfony\Component\HttpFoundation\StreamedResponse|null
    {
        $this->authorize('events.manage');

        $registrationRecord = $this->selectedRegistrationRecord();

        if (! $registrationRecord) {
            return null;
        }

        $png = $this->renderParticipantBadgePng($registrationRecord);
        $fullName = trim(($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')) ?: 'participante';
        $filename = 'gafete-participante-' . Str::slug($fullName) . '.png';

        return Response::streamDownload(function () use ($png): void {
            echo $png;
        }, $filename, [
            'Content-Type' => 'image/png',
        ]);
    }

    public function generateAttendanceSelfCode(): void
    {
        $this->authorize('events.manage');

        $conference = $this->selectedAttendanceConference();

        if (! $conference) {
            session()->flash('error', 'Selecciona una conferencia válida para generar el QR de autoasistencia.');

            return;
        }

        do {
            $code = Str::upper(Str::random(5));
        } while (Conferencia::query()->where('codigo_auto_asistencia', $code)->exists());

        $conference->forceFill([
            'codigo_auto_asistencia' => $code,
            'codigo_auto_asistencia_generado_en' => now(),
        ])->save();

        $this->refreshEventRelations();
        $this->selectedAttendanceConferenceId = $conference->id;
        $this->refreshAttendanceSelfQr($conference->fresh());

        session()->flash('message', 'Código de autoasistencia generado correctamente.');
    }

    public function downloadAttendanceSelfQr(): \Symfony\Component\HttpFoundation\StreamedResponse|null
    {
        $this->authorize('events.manage');

        $conference = $this->selectedAttendanceConference();

        if (! $conference?->codigo_auto_asistencia) {
            session()->flash('error', 'Genera primero el código de autoasistencia.');

            return null;
        }

        $binary = base64_decode(QRCodeService::generateTextQRCode($this->attendanceSelfUrl($conference), 900));

        return Response::streamDownload(function () use ($binary): void {
            echo $binary;
        }, 'qr-asistencia-' . $conference->codigo_auto_asistencia . '.png', [
            'Content-Type' => 'image/png',
        ]);
    }

    public function addAttendanceParticipant(): void
    {
        $this->authorize('events.manage');

        $conference = $this->selectedAttendanceConference();

        if (! $conference) {
            session()->flash('error', 'Selecciona una conferencia válida para registrar asistencia.');

            return;
        }

        $validated = $this->validate([
            'attendanceParticipantRegistroId' => 'required|exists:evento_registros,id',
        ], [], [
            'attendanceParticipantRegistroId' => 'participante',
        ]);

        $registrationRecord = EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->with('persona')
            ->findOrFail($validated['attendanceParticipantRegistroId']);

        $alreadyRegistered = ConferenciaRegistroAsistencia::query()
            ->where('conferencia_id', $conference->id)
            ->where('evento_registro_id', $registrationRecord->id)
            ->exists();

        if ($alreadyRegistered) {
            $this->addError('attendanceParticipantRegistroId', 'Este participante ya fue agregado a la asistencia de esta conferencia.');

            return;
        }

        ConferenciaRegistroAsistencia::query()->create([
            'conferencia_id' => $conference->id,
            'evento_registro_id' => $registrationRecord->id,
            'checked_in_at' => now(),
        ]);

        $this->refreshEventRelations();
        $this->attendanceParticipantRegistroId = '';
        $this->attendanceParticipantLookup = '';
        $this->attendanceParticipantSelectedLabel = '';
        $this->resetPage(pageName: 'asistenciaParticipantes');

        $fullName = trim(($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')) ?: 'Participante';

        session()->flash('message', 'Asistencia registrada para ' . $fullName . '.');
    }

    public function selectAttendanceParticipant(int $registrationId): void
    {
        $this->authorize('events.manage');

        $candidate = EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->with('persona')
            ->findOrFail($registrationId);

        $label = trim(($candidate->persona?->nombre ?? '') . ' ' . ($candidate->persona?->apellido ?? '')) ?: 'Participante';
        $identifier = $candidate->persona?->dni ?: ($candidate->persona?->correo ?: 'Sin identificador');

        $this->attendanceParticipantRegistroId = (string) $candidate->id;
        $this->attendanceParticipantSelectedLabel = $label . ' · ' . $identifier;
        $this->attendanceParticipantLookup = $this->attendanceParticipantSelectedLabel;
        $this->resetValidation('attendanceParticipantRegistroId');
    }

    public function confirmDeleteAttendanceParticipant(int $attendanceId): void
    {
        $this->authorize('events.manage');

        $attendanceRecord = ConferenciaRegistroAsistencia::query()
            ->whereHas('conferencia', fn ($query) => $query->where('IdEvento', $this->evento->id))
            ->with('eventoRegistro.persona')
            ->findOrFail($attendanceId);

        $person = $attendanceRecord->eventoRegistro?->persona;

        $this->deleteContext = 'attendance';
        $this->deleteRecordId = $attendanceRecord->id;
        $this->deleteRecordLabel = trim(($person?->nombre ?? '') . ' ' . ($person?->apellido ?? '')) ?: 'asistencia';
        $this->showDeleteConfirmModal = true;
    }

    public function downloadAttendanceListPdf()
    {
        $this->authorize('events.manage');

        $conference = $this->selectedAttendanceConference();

        if (! $conference) {
            session()->flash('error', 'Selecciona una conferencia antes de descargar el listado de asistencia.');

            return null;
        }

        $attendanceRecords = ConferenciaRegistroAsistencia::query()
            ->where('conferencia_id', $conference->id)
            ->with(['eventoRegistro.persona.tipoPerfil', 'eventoRegistro.tipoPerfil'])
            ->orderByDesc('checked_in_at')
            ->orderByDesc('id')
            ->get();

        if ($attendanceRecords->isEmpty()) {
            session()->flash('error', 'Esta conferencia aún no tiene participantes con asistencia registrada.');

            return null;
        }

        $pdf = PDF::loadView('pdf.evento-conferencia-asistencia-listado', [
            'evento' => $this->evento,
            'conference' => $conference,
            'attendanceRecords' => $attendanceRecords,
        ])->setPaper('letter', 'portrait');

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'asistencia-' . Str::slug($conference->nombre ?: 'conferencia') . '-evento-' . $this->evento->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function generateGeneralParticipationCertificates(): void
    {
        $this->openGeneralCertificateGenerationModal();
    }

    public function generateConferenceParticipationCertificates(?int $conferenceId = null): void
    {
        $this->authorize('events.manage');

        if (! $this->participationCertificateDesignConfigured()) {
            session()->flash('error', 'Configura primero la plantilla de diplomas por conferencia.');

            return;
        }

        $conferenceIds = $conferenceId
            ? [$conferenceId]
            : $this->evento->conferencias()->pluck('id')->all();

        $attendanceRecords = ConferenciaRegistroAsistencia::query()
            ->whereIn('conferencia_id', $conferenceIds)
            ->whereHas('conferencia', fn ($query) => $query->where('IdEvento', $this->evento->id))
            ->with(['conferencia.tipoConferencia', 'eventoRegistro.persona.tipoPerfil', 'eventoRegistro.tipoPerfil'])
            ->orderBy('conferencia_id')
            ->orderBy('id')
            ->get();

        if ($attendanceRecords->isEmpty()) {
            session()->flash('error', 'No hay asistencias registradas para generar certificados por conferencia.');

            return;
        }

        $generated = 0;

        foreach ($attendanceRecords as $attendance) {
            if (! $attendance->eventoRegistro?->persona || ! $attendance->conferencia) {
                continue;
            }

            $this->persistCertificatePdf('diploma_conferencia', $attendance->eventoRegistro, $attendance->conferencia);
            $generated++;
        }

        $this->refreshEventRelations();

        session()->flash('message', 'Se generaron o actualizaron ' . $generated . ' certificados por conferencia.');
    }

    public function downloadGeneratedCertificate(int $certificateId)
    {
        $this->authorize('events.manage');

        $certificate = EventoCertificado::query()
            ->where('evento_id', $this->evento->id)
            ->findOrFail($certificateId);

        if (! Storage::disk('public')->exists($certificate->pdf_path)) {
            session()->flash('error', 'El archivo PDF del certificado no se encontró en almacenamiento.');

            return null;
        }

        return Storage::disk('public')->download(
            $certificate->pdf_path,
            basename($certificate->pdf_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    public function downloadAttendanceCertificate(int $attendanceId)
    {
        $this->authorize('events.manage');

        if (! $this->participationCertificateDesignConfigured()) {
            session()->flash('error', 'Este evento no tiene configurado un diploma por conferencia. Solo aplica el diploma general de participación del evento.');

            return null;
        }

        $attendance = $this->attendanceRecordForDownload($attendanceId);
        $conference = $attendance->conferencia;
        $fullName = trim(($attendance->eventoRegistro?->persona?->nombre ?? '') . ' ' . ($attendance->eventoRegistro?->persona?->apellido ?? '')) ?: 'participante';
        $pdfOrientation = $this->participationCertificatePdfOrientation();
        $paperDefinition = $this->participationCertificatePdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->participationCertificatePdfPageDimensions($paperDefinition, $pdfOrientation);

        $pdf = PDF::loadView('pdf.evento-conferencia-certificado-individual', [
            'evento' => $this->evento,
            'conference' => $conference,
            'participantName' => $fullName,
            'imageDataUri' => 'data:image/png;base64,' . base64_encode($this->renderParticipationCertificatePng($attendance)),
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'certificado-' . Str::slug($fullName) . '-' . Str::slug($conference?->nombre ?: 'conferencia') . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function downloadAttendanceCertificatesPdf()
    {
        $this->authorize('events.manage');

        if (! $this->participationCertificateDesignConfigured()) {
            session()->flash('error', 'Este evento no tiene configurado un diploma por conferencia. Solo aplica el diploma general de participación del evento.');

            return null;
        }

        $conference = $this->selectedAttendanceConference();

        if (! $conference) {
            session()->flash('error', 'Selecciona una conferencia antes de descargar los certificados.');

            return null;
        }

        $attendanceRecords = ConferenciaRegistroAsistencia::query()
            ->where('conferencia_id', $conference->id)
            ->with(['eventoRegistro.persona.tipoPerfil', 'eventoRegistro.tipoPerfil'])
            ->orderByDesc('checked_in_at')
            ->orderByDesc('id')
            ->get();

        if ($attendanceRecords->isEmpty()) {
            session()->flash('error', 'Esta conferencia aún no tiene participantes con asistencia registrada.');

            return null;
        }

        $pdfOrientation = $this->participationCertificatePdfOrientation();
        $paperDefinition = $this->participationCertificatePdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->participationCertificatePdfPageDimensions($paperDefinition, $pdfOrientation);

        $payloads = $attendanceRecords->map(function (ConferenciaRegistroAsistencia $attendance) {
            $fullName = trim(($attendance->eventoRegistro?->persona?->nombre ?? '') . ' ' . ($attendance->eventoRegistro?->persona?->apellido ?? '')) ?: 'Participante';

            return [
                'nombre' => $fullName,
                'codigo' => $this->participationCertificateCode($attendance),
                'image_data_uri' => 'data:image/png;base64,' . base64_encode($this->renderParticipationCertificatePng($attendance)),
            ];
        })->all();

        $pdf = PDF::loadView('pdf.evento-conferencia-certificados-lote', [
            'evento' => $this->evento,
            'conference' => $conference,
            'certificates' => $payloads,
            'pdfOrientation' => $pdfOrientation,
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, 'certificados-' . Str::slug($conference->nombre ?: 'conferencia') . '-evento-' . $this->evento->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function sendAttendanceCertificateByEmail(int $attendanceId): void
    {
        $this->authorize('events.manage');

        if (! $this->participationCertificateDesignConfigured()) {
            session()->flash('error', 'Este evento no tiene configurado un diploma por conferencia. Solo aplica el diploma general de participación del evento.');

            return;
        }

        $attendance = $this->attendanceRecordForDownload($attendanceId);
        $person = $attendance->eventoRegistro?->persona;

        if (! $person?->correo) {
            session()->flash('error', 'El participante no tiene correo configurado.');

            return;
        }

        $fullName = trim(($person->nombre ?? '') . ' ' . ($person->apellido ?? '')) ?: 'participante';
        $conferenceName = $attendance->conferencia?->nombre ?: 'la conferencia';

        $subject = rawurlencode('Certificado de participación - ' . $conferenceName);
        $body = rawurlencode(implode("\n", [
            'Hola ' . $fullName . ',',
            '',
            'Desde la administración del evento se generó tu certificado de participación para ' . $conferenceName . '.',
            'Descárgalo desde el sistema o solicítalo directamente al organizador si necesitas una nueva copia.',
            '',
            'Evento: ' . $this->evento->nombreevento,
            'Conferencia: ' . $conferenceName,
        ]));

        $this->dispatch('open-external-link', url: 'mailto:' . rawurlencode((string) $person->correo) . '?subject=' . $subject . '&body=' . $body, target: 'self');
        session()->flash('message', 'Se preparó el correo para enviar el certificado a ' . $fullName . '.');
    }

    public function sendAttendanceCertificateByWhatsapp(int $attendanceId): void
    {
        $this->authorize('events.manage');

        if (! $this->participationCertificateDesignConfigured()) {
            session()->flash('error', 'Este evento no tiene configurado un diploma por conferencia. Solo aplica el diploma general de participación del evento.');

            return;
        }

        $attendance = $this->attendanceRecordForDownload($attendanceId);
        $person = $attendance->eventoRegistro?->persona;

        if (! $person?->telefono) {
            session()->flash('error', 'El participante no tiene teléfono configurado.');

            return;
        }

        $phone = preg_replace('/\D+/', '', (string) $person->telefono);

        if ($phone === '') {
            session()->flash('error', 'El teléfono del participante no es válido para WhatsApp.');

            return;
        }

        $fullName = trim(($person->nombre ?? '') . ' ' . ($person->apellido ?? '')) ?: 'participante';
        $conferenceName = $attendance->conferencia?->nombre ?: 'la conferencia';
        $message = rawurlencode(implode("\n", [
            'Hola ' . $fullName . ',',
            'Tu certificado de participación ya fue generado para ' . $conferenceName . '.',
            'Evento: ' . $this->evento->nombreevento,
            'Puedes solicitar al organizador el archivo PDF si necesitas una nueva copia.',
        ]));

        $this->dispatch('open-external-link', url: 'https://wa.me/' . $phone . '?text=' . $message, target: 'blank');
        session()->flash('message', 'Se preparó el mensaje de WhatsApp para ' . $fullName . '.');
    }

    public function openRegistrationModal(): void
    {
        $this->authorize('events.manage');

        $this->resetRegistrationForm();
        $this->showRegistrationModal = true;
    }

    public function closeRegistrationModal(): void
    {
        $this->showRegistrationModal = false;
        $this->resetRegistrationForm();
    }

    public function editRegistration(int $registrationId): void
    {
        $this->authorize('events.manage');

        $registrationRecord = $this->evento->registros()->with('persona')->findOrFail($registrationId);
        $persona = $registrationRecord->persona;

        if (! $persona) {
            session()->flash('error', 'La inscripción no tiene datos personales asociados para editar.');

            return;
        }

        $this->resetRegistrationForm();
        $this->editingRegistrationId = $registrationRecord->id;
        [$legacyPrimerNombre, $legacySegundoNombre] = $this->splitRegistrationPersonName($persona->nombre);
        [$legacyPrimerApellido, $legacySegundoApellido] = $this->splitRegistrationPersonName($persona->apellido);

        $this->registrationPrimerNombre = (string) ($persona->primer_nombre ?: $legacyPrimerNombre);
        $this->registrationSegundoNombre = (string) ($persona->segundo_nombre ?: $legacySegundoNombre);
        $this->registrationPrimerApellido = (string) ($persona->primer_apellido ?: $legacyPrimerApellido);
        $this->registrationSegundoApellido = (string) ($persona->segundo_apellido ?: $legacySegundoApellido);
        $this->registrationCorreo = (string) ($persona->correo ?? '');
        $this->registrationTelefono = (string) ($persona->telefono ?? '');
        $this->registrationDni = (string) ($persona->dni ?? '');
        $this->registrationDireccion = (string) ($persona->direccion ?? '');
        $this->showRegistrationModal = true;
    }

    public function saveRegistrationData(): void
    {
        $this->authorize('events.manage');

        $this->validate([
            'registrationPrimerNombre' => 'required|string|max:100',
            'registrationSegundoNombre' => 'nullable|string|max:100',
            'registrationPrimerApellido' => 'required|string|max:100',
            'registrationSegundoApellido' => 'nullable|string|max:100',
            'registrationCorreo' => 'required|email|max:255',
            'registrationTelefono' => 'required|string|max:50',
            'registrationDni' => 'required|string|max:50',
            'registrationDireccion' => 'nullable|string|max:255',
        ], [], [
            'registrationPrimerNombre' => 'primer nombre',
            'registrationSegundoNombre' => 'segundo nombre',
            'registrationPrimerApellido' => 'primer apellido',
            'registrationSegundoApellido' => 'segundo apellido',
            'registrationCorreo' => 'correo',
            'registrationTelefono' => 'teléfono',
            'registrationDni' => 'DNI',
            'registrationDireccion' => 'dirección',
        ]);

        $registrationRecord = $this->evento->registros()->with('persona')->findOrFail($this->editingRegistrationId);
        $persona = $registrationRecord->persona;

        if (! $persona) {
            session()->flash('error', 'No se encontró la persona asociada a esta inscripción.');

            return;
        }

        Persona::query()->whereKey($persona->id)->update([
            'primer_nombre' => trim($this->registrationPrimerNombre),
            'segundo_nombre' => trim($this->registrationSegundoNombre) ?: null,
            'primer_apellido' => trim($this->registrationPrimerApellido),
            'segundo_apellido' => trim($this->registrationSegundoApellido) ?: null,
            'correo' => trim($this->registrationCorreo),
            'telefono' => trim($this->registrationTelefono),
            'dni' => trim($this->registrationDni),
            'direccion' => trim($this->registrationDireccion),
        ]);

        $this->refreshEventRelations();
        $this->closeRegistrationModal();

        session()->flash('message', 'Datos personales del inscrito actualizados correctamente.');
    }

    public function confirmDeleteRegistration(int $registrationId): void
    {
        $this->authorize('events.manage');

        $registrationRecord = $this->evento->registros()->with('persona')->findOrFail($registrationId);
        $this->deleteContext = 'registration';
        $this->deleteRecordId = $registrationRecord->id;
        $this->deleteRecordLabel = trim(($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')) ?: 'inscripción';
        $this->showDeleteConfirmModal = true;
    }

    public function getTabItemsProperty(): array
    {
        return [
            'invitados' => [
                'label' => 'Invitados',
                'description' => 'Invitaciones personalizadas y cupos asignados.',
                'count' => $this->evento->invitaciones->count(),
            ],
            'staff' => [
                'label' => 'Staff',
                'description' => 'Equipo organizador y personal operativo del evento.',
                'count' => $this->evento->staffMembers->count(),
            ],
            'inscripciones' => [
                'label' => 'Inscripciones',
                'description' => 'Participantes registrados y estado de sus pagos.',
                'count' => $this->evento->registros->count(),
            ],
            'asistencia' => [
                'label' => 'Asistencia',
                'description' => 'Control de ingreso y validación durante el evento.',
                'count' => $this->attendanceConferencesQuery()->count(),
            ],
            'certificados' => [
                'label' => 'Certificados',
                'description' => 'Generación centralizada y resguardo PDF de constancias.',
                'count' => $this->certificatesQuery()->count(),
            ],
        ];
    }

    public function documentGenerationDefinitions(): array
    {
        return [
            self::DOCUMENT_TYPE_INVITATIONS => [
                'label' => 'Invitaciones en PDF',
                'short_label' => 'las invitaciones',
                'description' => 'Se generará un PDF consolidado con todas las invitaciones del evento. El proceso se ejecutará por lotes y podrás seguir usando el sistema mientras termina.',
                'button_label' => 'Generar invitaciones',
                'download_label' => 'Guardar PDF',
                'batch_size' => 12,
                'start_message' => 'Preparando lotes de invitaciones...',
                'empty_message' => 'No hay invitaciones registradas para exportar.',
            ],
            self::DOCUMENT_TYPE_STAFF_BADGES => [
                'label' => 'Gafetes de staff en PDF',
                'short_label' => 'los gafetes del staff',
                'description' => 'Se generará un PDF consolidado con los gafetes del staff con perfil completo. El avance quedará visible debajo del botón.',
                'button_label' => 'Generar gafetes de staff',
                'download_label' => 'Guardar PDF',
                'batch_size' => 6,
                'start_message' => 'Preparando lotes de gafetes del staff...',
                'empty_message' => 'No hay miembros del staff con perfil completo para exportar gafetes.',
            ],
            self::DOCUMENT_TYPE_REGISTRATION_BADGES => [
                'label' => 'Gafetes de participantes en PDF',
                'short_label' => 'los gafetes de participantes',
                'description' => 'Se generará un PDF consolidado con los gafetes de acceso de las inscripciones del evento. El proceso correrá por lotes para evitar bloqueos.',
                'button_label' => 'Generar gafetes de participantes',
                'download_label' => 'Guardar PDF',
                'batch_size' => 6,
                'start_message' => 'Preparando lotes de gafetes de participantes...',
                'empty_message' => 'No hay inscripciones registradas para exportar gafetes.',
            ],
            self::DOCUMENT_TYPE_GENERAL_CERTIFICATES => [
                'label' => 'Diplomas generales en PDF',
                'short_label' => 'los diplomas generales',
                'description' => 'Se generarán los certificados de participación general y además un PDF consolidado que podrás guardar cuando termine el proceso.',
                'button_label' => 'Generar diplomas generales',
                'download_label' => 'Guardar PDF',
                'batch_size' => 8,
                'start_message' => 'Generando certificados generales y armando el PDF consolidado...',
                'empty_message' => 'No hay inscritos válidos para generar certificados generales.',
            ],
        ];
    }

    public function hasActiveDocumentTasks(): bool
    {
        return EventoDocumentoProceso::query()
            ->where('evento_id', $this->evento->id)
            ->whereIn('estado', ['pendiente', 'procesando'])
            ->exists();
    }

    public function latestDocumentTasksByType(): array
    {
        $tasks = EventoDocumentoProceso::query()
            ->where('evento_id', $this->evento->id)
            ->whereIn('tipo', array_keys($this->documentGenerationDefinitions()))
            ->orderByDesc('id')
            ->get()
            ->groupBy('tipo')
            ->map(fn ($group) => $group->first());

        return $tasks->all();
    }

    public function render()
    {
        $this->resumeStalledDocumentTasks();

        $guestInvitations = $this->guestInvitationsQuery()->paginate($this->guestPerPage, ['*'], 'invitados');
        $staffMembers = $this->staffMembersQuery()->paginate($this->staffPerPage, ['*'], 'staff');
        $registrationRecords = $this->registrationRecordsQuery()->paginate($this->registrationPerPage, ['*'], 'registros');
        $attendanceConferences = $this->attendanceConferencesQuery()->paginate($this->attendancePerPage, ['*'], 'asistencias');
        $attendanceParticipants = $this->selectedAttendanceConferenceId
            ? $this->attendanceParticipantsQuery()->paginate($this->attendanceParticipantPerPage, ['*'], 'asistenciaParticipantes')
            : null;
        $generatedCertificates = $this->certificatesQuery()->paginate($this->certificatePerPage, ['*'], 'certificados');
        $selectedStaffCandidate = $this->staffPersonaId !== ''
            ? Persona::query()->with('user')->find($this->staffPersonaId)
            : null;
        $selectedAttendanceConference = $this->selectedAttendanceConference();
        $documentGenerationDefinitions = $this->documentGenerationDefinitions();
        $latestDocumentTasks = $this->latestDocumentTasksByType();

        return view('livewire.Evento.gestionar-evento-publicado', [
            'guestInvitations' => $guestInvitations,
            'staffMembers' => $staffMembers,
            'registrationRecords' => $registrationRecords,
            'attendanceConferences' => $attendanceConferences,
            'attendanceParticipants' => $attendanceParticipants,
            'attendanceParticipantCandidates' => $this->attendanceParticipantCandidates(),
            'selectedAttendanceConference' => $selectedAttendanceConference,
            'staffCandidates' => $this->staffCandidates(),
            'selectedStaffCandidate' => $selectedStaffCandidate,
            'generatedCertificates' => $generatedCertificates,
            'certificateConferenceSummaries' => $this->certificateConferenceSummaries(),
            'documentGenerationDefinitions' => $documentGenerationDefinitions,
            'latestDocumentTasks' => $latestDocumentTasks,
            'hasActiveDocumentTasks' => ! empty(array_filter($latestDocumentTasks, fn ($task) => $task?->esta_activo)),
        ])
            ->layout('components.layouts.app');
    }

    public function selectedInvitation(): ?EventoInvitacion
    {
        if (! $this->previewInvitationId) {
            return null;
        }

        return $this->evento->invitaciones->firstWhere('id', $this->previewInvitationId);
    }

    public function selectedStaffMember(): ?EventoStaff
    {
        if (! $this->previewStaffId) {
            return null;
        }

        return $this->evento->staffMembers()->with('persona.user', 'persona.tipoPerfil')->find($this->previewStaffId);
    }

    public function selectedRegistrationRecord(): ?EventoRegistro
    {
        if (! $this->previewRegistrationId) {
            return null;
        }

        return $this->evento->registros()->with('persona.tipoPerfil', 'tipoPerfil', 'metodoPago')->find($this->previewRegistrationId);
    }

    public function participationCertificateDesign(): ?array
    {
        return ($this->evento->graphic_designs ?? [])['diploma_conferencia'] ?? null;
    }

    public function generalParticipationCertificateDesign(): ?array
    {
        return ($this->evento->graphic_designs ?? [])['participacion_general'] ?? null;
    }

    public function participationCertificateDesignConfigured(): bool
    {
        $design = $this->participationCertificateDesign();

        return (bool) (
            $design
            && ! empty($design['file'])
            && ! empty($design['page_size'])
            && ! empty($design['orientation'])
            && ! empty($design['font_size'])
            && ! empty($design['qr_size'])
        );
    }

    public function generalParticipationCertificateDesignConfigured(): bool
    {
        $design = $this->generalParticipationCertificateDesign();

        return (bool) (
            $design
            && ! empty($design['file'])
            && ! empty($design['page_size'])
            && ! empty($design['orientation'])
            && ! empty($design['font_size'])
            && ! empty($design['qr_size'])
        );
    }

    public function invitationDesign(): ?array
    {
        return ($this->evento->graphic_designs ?? [])['invitacion'] ?? null;
    }

    public function invitationDesignConfigured(): bool
    {
        $design = $this->invitationDesign();

        return (bool) (
            $design
            && ! empty($design['page_size'])
            && ! empty($design['orientation'])
            && ! empty($design['font_size'])
            && ! empty($design['qr_size'])
        );
    }

    public function invitationDesignUrl(): ?string
    {
        $file = $this->invitationDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        return Str::startsWith($file, ['http://', 'https://']) ? $file : asset($file);
    }

    public function invitationNameStyle(?EventoInvitacion $invitation): string
    {
        $name = trim((string) ($invitation?->nombre_invitado ?? 'Nombre del invitado'));
        $length = mb_strlen($name);
        $design = $this->invitationDesign() ?? [];
        $baseSize = (float) ($design['font_size'] ?? 6);

        if ($length > 18) {
            $baseSize -= min(2.5, ($length - 18) * 0.12);
        }

        if ($length > 32) {
            $baseSize -= min(1.5, ($length - 32) * 0.08);
        }

        return $this->designElementStyle(
            $this->invitationCoordinate('name', 'x'),
            $this->invitationCoordinate('name', 'y'),
            max(3, $baseSize),
            'invitation-name'
        );
    }

    public function designPreviewStyle(?string $pageSize, ?string $orientation): string
    {
        $ratio = self::PAGE_SIZE_RATIOS[$pageSize ?: ''] ?? self::PAGE_SIZE_RATIOS['Carta'];
        [$width, $height] = $ratio;

        if (($orientation ?: 'Vertical') === 'Horizontal') {
            [$width, $height] = [$height, $width];
        }

        return sprintf('aspect-ratio: %s / %s; container-type: inline-size;', $width, $height);
    }

    public function designElementStyle(float|int|string|null $x, float|int|string|null $y, int|string|null $size, string $type = 'name'): string
    {
        if ($x === null || $y === null) {
            return 'display:none;';
        }

        $left = max(0, min(100, (float) $x));
        $top = max(0, min(100, (float) $y));

        if ($type === 'qr') {
            $qrSize = max(12, min(28, (int) $size));

            return sprintf(
                'left: %1$.4f%%; top: %2$.4f%%; width: %3$d%%; aspect-ratio: 1 / 1; transform: translate(-50%%, -50%%);',
                $left,
                $top,
                $qrSize
            );
        }

        $fontSize = (float) $size;
        if ($fontSize > 12) {
            $fontSize = round($fontSize / 4, 1);
        }
        $fontSize = max(3, min(12, $fontSize));

        if ($type === 'invitation-name') {
            return sprintf(
                'left: %1$.4f%%; top: %2$.4f%%; width: 84%%; transform: translate(-50%%, -50%%); font-size: clamp(0.95rem, %3$.2fcqw, 4rem); line-height: 1; text-align: center; white-space: nowrap; color: #0f172a; font-weight: 800; text-shadow: 0 2px 18px rgba(255,255,255,0.65);',
                $left,
                $top,
                $fontSize
            );
        }

        return sprintf(
            'left: %1$.4f%%; top: %2$.4f%%; max-width: calc(100%% - 1.5rem); transform: translate(-50%%, -50%%); font-size: clamp(0.75rem, %3$.2fcqw, 4rem); line-height: 1.15; text-align: center;',
            $left,
            $top,
            $fontSize
        );
    }

    public function invitationQrCaptionStyle(): string
    {
        $design = $this->invitationDesign() ?? [];
        $x = $this->invitationCoordinate('qr', 'x');
        $y = $this->invitationCoordinate('qr', 'y');
        $size = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $captionTop = min(97, $y + ($size / 2) + 4.2);

        return sprintf(
            'left: %1$.4f%%; top: %2$.4f%%; width: min(26%%, 12rem); transform: translate(-50%%, 0); text-align: center; font-size: clamp(0.62rem, 1.2cqw, 0.95rem); line-height: 1.2; color: #0f172a; font-weight: 600; text-shadow: 0 1px 10px rgba(255,255,255,0.85);',
            $x,
            $captionTop
        );
    }

    public function invitationCoordinate(string $element, string $axis): float
    {
        $design = $this->invitationDesign() ?? [];
        $default = $element === 'qr'
            ? ['x' => 84, 'y' => 84]
            : ['x' => 50, 'y' => 70];

        if (array_key_exists($element . '_x', $design) && array_key_exists($element . '_y', $design)) {
            return (float) max(0, min(100, (float) ($design[$element . '_' . $axis] ?? $default[$axis])));
        }

        return (float) $default[$axis];
    }

    public function staffBadgeDesign(): ?array
    {
        return ($this->evento->graphic_designs ?? [])['gafete_staff'] ?? null;
    }

    public function staffBadgeDesignConfigured(): bool
    {
        $design = $this->staffBadgeDesign();

        return (bool) (
            $design
            && ! empty($design['page_size'])
            && ! empty($design['orientation'])
            && ! empty($design['font_size'])
            && ! empty($design['qr_size'])
        );
    }

    public function staffBadgeDesignUrl(): ?string
    {
        $file = $this->staffBadgeDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        return Str::startsWith($file, ['http://', 'https://']) ? $file : asset($file);
    }

    public function staffBadgeNameStyle(?EventoStaff $staffMember): string
    {
        $name = trim((string) ($staffMember?->nombre ?? 'Miembro del staff'));
        $length = mb_strlen($name);
        $design = $this->staffBadgeDesign() ?? [];
        $baseSize = (float) ($design['font_size'] ?? 6);

        if ($length > 18) {
            $baseSize -= min(2.5, ($length - 18) * 0.12);
        }

        if ($length > 32) {
            $baseSize -= min(1.5, ($length - 32) * 0.08);
        }

        return $this->designElementStyle(
            $this->staffBadgeCoordinate('name', 'x'),
            $this->staffBadgeCoordinate('name', 'y'),
            max(3, $baseSize),
            'invitation-name'
        );
    }

    public function staffBadgeQrCaptionStyle(): string
    {
        $design = $this->staffBadgeDesign() ?? [];
        $x = $this->staffBadgeCoordinate('qr', 'x');
        $y = $this->staffBadgeCoordinate('qr', 'y');
        $size = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $captionTop = min(97, $y + ($size / 2) + 4.2);

        return sprintf(
            'left: %1$.4f%%; top: %2$.4f%%; width: min(28%%, 12rem); transform: translate(-50%%, 0); text-align: center; font-size: clamp(0.58rem, 1.05cqw, 0.88rem); line-height: 1.2; color: #0f172a; font-weight: 700; text-shadow: 0 1px 10px rgba(255,255,255,0.85);',
            $x,
            $captionTop
        );
    }

    public function staffBadgeCoordinate(string $element, string $axis): float
    {
        $design = $this->staffBadgeDesign() ?? [];
        $default = $element === 'qr'
            ? ['x' => 50, 'y' => 79]
            : ['x' => 50, 'y' => 58];

        if (array_key_exists($element . '_x', $design) && array_key_exists($element . '_y', $design)) {
            return (float) max(0, min(100, (float) ($design[$element . '_' . $axis] ?? $default[$axis])));
        }

        return (float) $default[$axis];
    }

    private function renderStaffBadgePng(EventoStaff $staffMember, ?int $targetCanvasWidth = null): string
    {
        $design = $this->staffBadgeDesign() ?? [];
        [$canvasWidth, $canvasHeight] = $this->staffBadgeCanvasDimensions(
            $design['page_size'] ?? 'Carta',
            $design['orientation'] ?? 'Vertical',
            $targetCanvasWidth
        );
        $image = $this->makeStaffBadgeCanvas($canvasWidth, $canvasHeight);

        $nameCenterX = (int) round($canvasWidth * ($this->staffBadgeCoordinate('name', 'x') / 100));
        $nameCenterY = (int) round($canvasHeight * ($this->staffBadgeCoordinate('name', 'y') / 100));
        $nameMaxWidth = (int) round($canvasWidth * 0.84);
        $this->drawCenteredSingleLineText(
            $image,
            trim((string) $staffMember->nombre),
            $nameCenterX,
            $nameCenterY,
            $nameMaxWidth
        );

        $qrSizePercent = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $qrPixelSize = (int) round($canvasWidth * ($qrSizePercent / 100));
        $qrCenterX = (int) round($canvasWidth * ($this->staffBadgeCoordinate('qr', 'x') / 100));
        $qrCenterY = (int) round($canvasHeight * ($this->staffBadgeCoordinate('qr', 'y') / 100));
        $qrX = (int) round($qrCenterX - ($qrPixelSize / 2));
        $qrY = (int) round($qrCenterY - ($qrPixelSize / 2));

        $this->placeQrOnCanvas($image, $this->staffBadgeCode($staffMember), $qrX, $qrY, $qrPixelSize);

        $caption = 'Acceso staff';
        $captionY = (int) min($canvasHeight - 18, round($qrY + $qrPixelSize + max(24, $canvasHeight * 0.02)));
        $this->drawCenteredCaption($image, $caption, $qrCenterX, $captionY, max($qrPixelSize, 220));

        ob_start();
        imagepng($image);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }

    private function resetGuestForm(): void
    {
        $this->resetValidation();
        $this->editingGuestId = null;
        $this->guestName = '';
        $this->guestEmail = '';
        $this->guestPhone = '';
        $this->guestSlots = 1;
    }

    private function resetListExportModal(string $context): void
    {
        $this->resetValidation();
        $this->listExportContext = $context;
        $this->listExportFormat = 'pdf';
        $this->listExportDate = '';
        $this->listExportIncludeSignature = false;
    }

    public function listExportDateOptions(): array
    {
        $start = $this->evento->fechainicio?->copy();
        $end = $this->evento->fechafinal?->copy();

        if (! $start || ! $end) {
            return [];
        }

        $dates = [];
        $current = $start->copy();

        while ($current->lte($end)) {
            $dates[] = [
                'value' => $current->format('Y-m-d'),
                'label' => $current->format('d/m/Y'),
            ];
            $current->addDay();
        }

        return $dates;
    }

    private function generateInvitationCode(): string
    {
        do {
            $code = strtoupper(Str::random(10));
        } while (EventoInvitacion::query()->where('codigo', $code)->exists());

        return $code;
    }

    private function resetStaffForm(): void
    {
        $this->resetValidation();
        $this->editingStaffId = null;
        $this->staffLookup = '';
        $this->staffPersonaId = '';
        $this->staffName = '';
        $this->staffEmail = '';
        $this->staffPhone = '';
    }

    private function splitRegistrationPersonName(mixed $value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), 2, PREG_SPLIT_NO_EMPTY) ?: [];

        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    private function resetRegistrationForm(): void
    {
        $this->resetValidation();
        $this->editingRegistrationId = null;
        $this->registrationPrimerNombre = '';
        $this->registrationSegundoNombre = '';
        $this->registrationPrimerApellido = '';
        $this->registrationSegundoApellido = '';
        $this->registrationCorreo = '';
        $this->registrationTelefono = '';
        $this->registrationDni = '';
        $this->registrationDireccion = '';
    }

    private function refreshEventRelations(): void
    {
        $this->evento->load([
            'modalidad',
            'tipoEvento',
            'localidad',
            'invitaciones',
            'staffMembers.persona.user',
            'registros.persona',
            'registros.tipoPerfil',
            'registros.metodoPago',
            'conferencias.registroAsistencias',
        ]);
    }

    private function staffCandidates()
    {
        return Persona::query()
            ->with('user')
            ->when($this->staffLookup !== '', function ($query) {
                $search = trim($this->staffLookup);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->searchName($search)
                        ->orWhere('correo', 'like', '%' . $search . '%')
                        ->orWhere('dni', 'like', '%' . $search . '%');
                });
            })
            ->orderByFullName()
            ->limit(5)
            ->get();
    }

    private function staffMembersQuery()
    {
        return EventoStaff::query()
            ->where('evento_id', $this->evento->id)
            ->where('activo', true)
            ->with('persona.user')
            ->when($this->staffSearch !== '', function ($query) {
                $search = trim($this->staffSearch);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre', 'like', '%' . $search . '%')
                        ->orWhere('correo', 'like', '%' . $search . '%')
                        ->orWhere('telefono', 'like', '%' . $search . '%');
                });
            })
            ->when($this->staffStatusFilter === 'completos', fn ($query) => $query->whereNotNull('perfil_completado_at'))
            ->when($this->staffStatusFilter === 'pendientes', fn ($query) => $query->whereNull('perfil_completado_at'))
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    private function registrationRecordsQuery()
    {
        return EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->with('persona.tipoPerfil', 'persona.user', 'tipoPerfil', 'metodoPago')
            ->when($this->registrationSearch !== '', function ($query) {
                $search = trim($this->registrationSearch);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('persona', function ($personaQuery) use ($search) {
                        $personaQuery
                            ->searchName($search)
                            ->orWhere('correo', 'like', '%' . $search . '%')
                            ->orWhere('telefono', 'like', '%' . $search . '%')
                            ->orWhere('dni', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('tipoPerfil', fn ($profileQuery) => $profileQuery->where('tipoperfil', 'like', '%' . $search . '%'))
                    ->orWhereHas('metodoPago', fn ($paymentQuery) => $paymentQuery->where('nombre', 'like', '%' . $search . '%'))
                    ->orWhere('estado', 'like', '%' . $search . '%')
                    ->orWhere('estado_pago', 'like', '%' . $search . '%');
                });
            })
            ->when($this->registrationStatusFilter === 'pagadas', fn ($query) => $query->where('estado_pago', 'pagado'))
            ->when($this->registrationStatusFilter === 'pendientes', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery
                        ->whereNull('estado_pago')
                        ->orWhereNotIn('estado_pago', ['pagado', 'no_aplica']);
                });
            })
            ->when($this->registrationStatusFilter === 'gratuitas', fn ($query) => $query->where('estado_pago', 'no_aplica'))
            ->orderByDesc('created_at')
            ->orderByDesc('id');
    }

    private function attendanceConferencesQuery()
    {
        return Conferencia::query()
            ->where('IdEvento', $this->evento->id)
            ->with(['tipoConferencia', 'speakerPersona'])
            ->withCount('registroAsistencias')
            ->when($this->attendanceSearch !== '', function ($query) {
                $search = trim($this->attendanceSearch);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre', 'like', '%' . $search . '%')
                        ->orWhere('descripcion', 'like', '%' . $search . '%')
                        ->orWhere('lugar', 'like', '%' . $search . '%')
                        ->orWhereHas('tipoConferencia', fn ($typeQuery) => $typeQuery->where('tipo', 'like', '%' . $search . '%'))
                        ->orWhereHas('speakerPersona', function ($speakerQuery) use ($search) {
                            $speakerQuery->searchName($search);
                        });
                });
            })
            ->orderBy('fecha')
            ->orderBy('horaInicio')
            ->orderBy('id');
    }

    private function attendanceParticipantsQuery()
    {
        return ConferenciaRegistroAsistencia::query()
            ->where('conferencia_id', $this->selectedAttendanceConferenceId)
            ->with(['eventoRegistro.persona.tipoPerfil', 'eventoRegistro.persona.user', 'eventoRegistro.tipoPerfil'])
            ->when($this->attendanceParticipantSearch !== '', function ($query) {
                $search = trim($this->attendanceParticipantSearch);

                $query->whereHas('eventoRegistro.persona', function ($personaQuery) use ($search) {
                    $personaQuery
                        ->searchName($search)
                        ->orWhere('correo', 'like', '%' . $search . '%')
                        ->orWhere('telefono', 'like', '%' . $search . '%')
                        ->orWhere('dni', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('checked_in_at')
            ->orderByDesc('id');
    }

    private function certificatesQuery()
    {
        return EventoCertificado::query()
            ->where('evento_id', $this->evento->id)
            ->with(['persona.tipoPerfil', 'eventoRegistro.tipoPerfil', 'conferencia.tipoConferencia'])
            ->when($this->certificateSearch !== '', function ($query) {
                $search = trim($this->certificateSearch);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre_certificado', 'like', '%' . $search . '%')
                        ->orWhere('hash_unico', 'like', '%' . $search . '%')
                        ->orWhereHas('persona', function ($personaQuery) use ($search) {
                            $personaQuery
                                ->searchName($search)
                                ->orWhere('correo', 'like', '%' . $search . '%')
                                ->orWhere('dni', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('conferencia', fn ($conferenceQuery) => $conferenceQuery->where('nombre', 'like', '%' . $search . '%'));
                });
            })
            ->when($this->certificateTypeFilter === 'generales', fn ($query) => $query->where('tipo', 'participacion_general'))
            ->when($this->certificateTypeFilter === 'conferencia', fn ($query) => $query->where('tipo', 'diploma_conferencia'))
            ->orderByDesc('generated_at')
            ->orderByDesc('id');
    }

    private function certificateConferenceSummaries()
    {
        $generatedByConference = EventoCertificado::query()
            ->where('evento_id', $this->evento->id)
            ->where('tipo', 'diploma_conferencia')
            ->whereNotNull('conferencia_id')
            ->selectRaw('conferencia_id, count(*) as total')
            ->groupBy('conferencia_id')
            ->pluck('total', 'conferencia_id');

        return $this->evento->conferencias()
            ->with('tipoConferencia')
            ->withCount('registroAsistencias')
            ->orderBy('fecha')
            ->orderBy('horaInicio')
            ->get()
            ->map(function (Conferencia $conference) use ($generatedByConference) {
                $conference->generated_certificates_count = (int) ($generatedByConference[$conference->id] ?? 0);

                return $conference;
            });
    }

    private function attendanceParticipantCandidates()
    {
        if (! $this->selectedAttendanceConferenceId) {
            return collect();
        }

        $alreadyPresentIds = ConferenciaRegistroAsistencia::query()
            ->where('conferencia_id', $this->selectedAttendanceConferenceId)
            ->pluck('evento_registro_id');

        return EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->whereNotIn('id', $alreadyPresentIds)
            ->with('persona.tipoPerfil')
            ->when($this->attendanceParticipantLookup !== '', function ($query) {
                $search = trim($this->attendanceParticipantLookup);

                $query->whereHas('persona', function ($personaQuery) use ($search) {
                    $personaQuery
                        ->searchName($search)
                        ->orWhere('correo', 'like', '%' . $search . '%')
                        ->orWhere('telefono', 'like', '%' . $search . '%')
                        ->orWhere('dni', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
    }

    private function selectedAttendanceConference(): ?Conferencia
    {
        if (! $this->selectedAttendanceConferenceId) {
            return null;
        }

        return $this->evento->conferencias()
            ->with(['tipoConferencia', 'speakerPersona'])
            ->withCount('registroAsistencias')
            ->find($this->selectedAttendanceConferenceId);
    }

    private function refreshAttendanceSelfQr(?Conferencia $conference = null): void
    {
        $conference ??= $this->selectedAttendanceConference();

        $this->attendanceSelfQrCode = $conference?->codigo_auto_asistencia
            ? QRCodeService::generateTextQRCode($this->attendanceSelfUrl($conference), 360)
            : '';
    }

    private function attendanceSelfUrl(Conferencia $conference): string
    {
        return route('asistencia.auto', ['code' => $conference->codigo_auto_asistencia]);
    }

    private function canAccessEventManagement(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->can('events.manage')) {
            return true;
        }

        $personaId = $user->persona?->id;

        if (! $personaId || ! $user->can('staff.portal.access')) {
            return false;
        }

        return $this->evento->staffMembers()
            ->where('persona_id', $personaId)
            ->where('activo', true)
            ->exists();
    }

    private function guestInvitationsQuery()
    {
        return $this->evento->invitaciones()
            ->when($this->guestSearch !== '', function ($query) {
                $search = trim($this->guestSearch);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre_invitado', 'like', '%' . $search . '%')
                        ->orWhere('correo_invitado', 'like', '%' . $search . '%')
                        ->orWhere('telefono_invitado', 'like', '%' . $search . '%')
                        ->orWhere('codigo', 'like', '%' . $search . '%');
                });
            })
            ->when($this->guestDeliveryFilter === 'enviadas', fn ($query) => $query->whereNotNull('enviada_at'))
            ->when($this->guestDeliveryFilter === 'pendientes', fn ($query) => $query->whereNull('enviada_at'))
            ->orderByDesc('created_at');
    }

    private function buildInvitationMailto(EventoInvitacion $invitation): string
    {
        $subject = 'Invitación al evento ' . $this->evento->nombreevento;
        $bodyLines = [
            'Hola ' . $invitation->nombre_invitado . ',',
            '',
            'Has sido invitado(a) al evento "' . $this->evento->nombreevento . '".',
            'Código único de invitación: ' . $invitation->codigo,
            'Cupos asignados: ' . $invitation->cupos,
            'Fecha del evento: ' . $this->evento->fechainicio?->format('d/m/Y') . ' - ' . $this->evento->fechafinal?->format('d/m/Y'),
            'Lugar: ' . $this->evento->localidad_display,
            '',
            'Presenta esta invitación y el código QR el día del evento para validar tu acceso.',
        ];

        return 'mailto:' . rawurlencode((string) $invitation->correo_invitado)
            . '?subject=' . rawurlencode($subject)
            . '&body=' . rawurlencode(implode("\n", $bodyLines));
    }

    private function buildInvitationWhatsappUrl(EventoInvitacion $invitation): string
    {
        $phone = preg_replace('/\D+/', '', (string) $invitation->telefono_invitado);
        $message = implode("\n", [
            'Hola ' . $invitation->nombre_invitado . ',',
            'Has sido invitado(a) al evento "' . $this->evento->nombreevento . '".',
            'Código único de invitación: ' . $invitation->codigo,
            'Cupos asignados: ' . $invitation->cupos,
            'Presenta esta invitación y el QR el día del evento para validar tu acceso.',
        ]);

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }

    private function buildStaffBadgeMailto(EventoStaff $staffMember): string
    {
        $subject = 'Gafete de staff - ' . $this->evento->nombreevento;
        $bodyLines = [
            'Hola ' . $staffMember->nombre . ',',
            '',
            'Adjuntamos o compartimos la referencia de tu gafete como miembro del staff del evento "' . $this->evento->nombreevento . '".',
            'Código único de acceso: ' . $this->staffBadgeCode($staffMember),
            'Fecha del evento: ' . $this->evento->fechainicio?->format('d/m/Y') . ' - ' . $this->evento->fechafinal?->format('d/m/Y'),
            'Lugar: ' . $this->evento->localidad_display,
            '',
            'Presenta tu gafete o el código QR el día del evento para validar tu acceso como staff.',
        ];

        return 'mailto:' . rawurlencode((string) $staffMember->correo)
            . '?subject=' . rawurlencode($subject)
            . '&body=' . rawurlencode(implode("\n", $bodyLines));
    }

    private function buildStaffBadgeWhatsappUrl(EventoStaff $staffMember): string
    {
        $phone = preg_replace('/\D+/', '', (string) $staffMember->telefono);
        $message = implode("\n", [
            'Hola ' . $staffMember->nombre . ',',
            'Tu gafete de staff para "' . $this->evento->nombreevento . '" ya está listo.',
            'Código único de acceso: ' . $this->staffBadgeCode($staffMember),
            'Presenta tu QR el día del evento para validar tu acceso como staff.',
        ]);

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }

    private function staffBadgeCode(EventoStaff $staffMember): string
    {
        return 'STAFF-' . strtoupper(substr((string) $staffMember->staff_access_token, 0, 8));
    }

    private function staffBadgeCanvasDimensions(?string $pageSize, ?string $orientation, ?int $targetCanvasWidth = null): array
    {
        return $this->invitationCanvasDimensions($pageSize, $orientation, $targetCanvasWidth);
    }

    private function staffBadgeTemplateFilesystemPath(): ?string
    {
        $file = $this->staffBadgeDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        $path = public_path($file);

        return file_exists($path) ? $path : null;
    }

    private function makeStaffBadgeCanvas(int $canvasWidth, int $canvasHeight)
    {
        $image = imagecreatetruecolor($canvasWidth, $canvasHeight);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $backgroundPath = $this->staffBadgeTemplateFilesystemPath();
        $background = $backgroundPath ? $this->loadRasterImage($backgroundPath) : null;

        if ($background) {
            imagecopyresampled(
                $image,
                $background,
                0,
                0,
                0,
                0,
                $canvasWidth,
                $canvasHeight,
                imagesx($background),
                imagesy($background)
            );
            imagedestroy($background);

            return $image;
        }

        $ink = imagecolorallocate($image, 30, 41, 59);
        $violet = imagecolorallocate($image, 124, 92, 255);
        $violetSoft = imagecolorallocate($image, 237, 233, 254);
        $cyanSoft = imagecolorallocate($image, 224, 242, 254);
        $white = imagecolorallocate($image, 255, 255, 255);
        $soft = imagecolorallocate($image, 248, 250, 252);
        $line = imagecolorallocate($image, 203, 213, 225);

        imagefilledrectangle($image, 0, 0, $canvasWidth, $canvasHeight, $white);
        imagefilledrectangle($image, 0, 0, $canvasWidth, (int) round($canvasHeight * 0.14), $violetSoft);
        imagefilledellipse($image, (int) round($canvasWidth * 0.14), (int) round($canvasHeight * 0.12), (int) round($canvasWidth * 0.3), (int) round($canvasWidth * 0.3), $cyanSoft);
        imagefilledellipse($image, (int) round($canvasWidth * 0.86), (int) round($canvasHeight * 0.86), (int) round($canvasWidth * 0.4), (int) round($canvasWidth * 0.4), $violetSoft);
        imagefilledrectangle($image, (int) round($canvasWidth * 0.07), (int) round($canvasHeight * 0.23), (int) round($canvasWidth * 0.93), (int) round($canvasHeight * 0.91), $soft);
        imagerectangle($image, (int) round($canvasWidth * 0.07), (int) round($canvasHeight * 0.23), (int) round($canvasWidth * 0.93), (int) round($canvasHeight * 0.91), $line);

        $fontPath = $this->invitationFontPath();
        if ($fontPath) {
            imagettftext(
                $image,
                max(26, (int) round($canvasHeight * 0.03)),
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.11),
                $ink,
                $fontPath,
                'STAFF'
            );

            $eventName = Str::limit((string) $this->evento->nombreevento, 42, '...');
            $eventFontSize = $this->fittedFontSize($eventName, $fontPath, (int) round($canvasWidth * 0.76), 34, 18);
            $eventBox = imagettfbbox($eventFontSize, 0, $fontPath, $eventName);
            $eventHeight = (int) abs($eventBox[7] - $eventBox[1]);
            imagettftext(
                $image,
                $eventFontSize,
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.21) + $eventHeight,
                $violet,
                $fontPath,
                $eventName
            );

            $subtitle = 'Identificación oficial del equipo organizador';
            imagettftext(
                $image,
                max(16, (int) round($canvasHeight * 0.018)),
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.19),
                $ink,
                $fontPath,
                $subtitle
            );
        }

        return $image;
    }

    private function staffBadgePdfOrientation(): string
    {
        $orientation = strtolower((string) (($this->staffBadgeDesign()['orientation'] ?? 'Vertical')));

        return $orientation === 'vertical' ? 'portrait' : 'landscape';
    }

    private function staffBadgePdfPaperDefinition(): string|array
    {
        $design = $this->staffBadgeDesign() ?? [];
        $pageSize = $design['page_size'] ?? 'Carta';
        $orientation = $this->staffBadgePdfOrientation();

        if ($pageSize === 'Personalizado') {
            $dimensions = $this->staffBadgeTemplatePointDimensions();

            if ($dimensions !== null) {
                [$width, $height] = $dimensions;

                if ($orientation === 'landscape' && $height > $width) {
                    [$width, $height] = [$height, $width];
                }

                if ($orientation === 'portrait' && $width > $height) {
                    [$width, $height] = [$height, $width];
                }

                return [0, 0, $width, $height];
            }
        }

        return match ($pageSize) {
            'Carta' => 'letter',
            'Oficio' => 'folio',
            'A4' => 'a4',
            'A3' => 'a3',
            'Legal' => 'legal',
            default => 'letter',
        };
    }

    private function staffBadgeTemplatePointDimensions(): ?array
    {
        $path = $this->staffBadgeTemplateFilesystemPath();

        if (! $path) {
            return null;
        }

        $size = @getimagesize($path);

        if (! $size || empty($size[0]) || empty($size[1])) {
            return null;
        }

        $widthPx = (float) $size[0];
        $heightPx = (float) $size[1];
        $pointsPerPixel = 72 / 96;

        return [
            round($widthPx * $pointsPerPixel, 2),
            round($heightPx * $pointsPerPixel, 2),
        ];
    }

    private function staffBadgePdfPageDimensions(string|array $paperDefinition, string $orientation): array
    {
        return $this->invitationPdfPageDimensions($paperDefinition, $orientation);
    }

    public function participantBadgeDesign(): ?array
    {
        return ($this->evento->graphic_designs ?? [])['gafete_participante'] ?? null;
    }

    public function participantBadgeDesignConfigured(): bool
    {
        $design = $this->participantBadgeDesign();

        return (bool) (
            $design
            && ! empty($design['page_size'])
            && ! empty($design['orientation'])
            && ! empty($design['font_size'])
            && ! empty($design['qr_size'])
        );
    }

    public function participantBadgeDesignUrl(): ?string
    {
        $file = $this->participantBadgeDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        return Str::startsWith($file, ['http://', 'https://']) ? $file : asset($file);
    }

    public function participantBadgeNameStyle(?EventoRegistro $registrationRecord): string
    {
        $fullName = trim((string) (($registrationRecord?->persona?->nombre ?? '') . ' ' . ($registrationRecord?->persona?->apellido ?? '')));
        $name = $fullName !== '' ? $fullName : 'Participante';
        $length = mb_strlen($name);
        $design = $this->participantBadgeDesign() ?? [];
        $baseSize = (float) ($design['font_size'] ?? 6);

        if ($length > 18) {
            $baseSize -= min(2.5, ($length - 18) * 0.12);
        }

        if ($length > 32) {
            $baseSize -= min(1.5, ($length - 32) * 0.08);
        }

        return $this->designElementStyle(
            $this->participantBadgeCoordinate('name', 'x'),
            $this->participantBadgeCoordinate('name', 'y'),
            max(3, $baseSize),
            'invitation-name'
        );
    }

    public function participantBadgeQrCaptionStyle(): string
    {
        $design = $this->participantBadgeDesign() ?? [];
        $x = $this->participantBadgeCoordinate('qr', 'x');
        $y = $this->participantBadgeCoordinate('qr', 'y');
        $size = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $captionTop = min(97, $y + ($size / 2) + 4.2);

        return sprintf(
            'left: %1$.4f%%; top: %2$.4f%%; width: min(28%%, 12rem); transform: translate(-50%%, 0); text-align: center; font-size: clamp(0.58rem, 1.05cqw, 0.88rem); line-height: 1.2; color: #0f172a; font-weight: 700; text-shadow: 0 1px 10px rgba(255,255,255,0.85);',
            $x,
            $captionTop
        );
    }

    public function participantBadgeCoordinate(string $element, string $axis): float
    {
        $design = $this->participantBadgeDesign() ?? [];
        $default = $element === 'qr'
            ? ['x' => 50, 'y' => 79]
            : ['x' => 50, 'y' => 58];

        if (array_key_exists($element . '_x', $design) && array_key_exists($element . '_y', $design)) {
            return (float) max(0, min(100, (float) ($design[$element . '_' . $axis] ?? $default[$axis])));
        }

        return (float) $default[$axis];
    }

    private function participantBadgeCode(EventoRegistro $registrationRecord): string
    {
        return 'REG-' . $this->evento->id . '-' . $registrationRecord->id;
    }

    private function renderParticipantBadgePng(EventoRegistro $registrationRecord, ?int $targetCanvasWidth = null): string
    {
        $design = $this->participantBadgeDesign() ?? [];
        [$canvasWidth, $canvasHeight] = $this->participantBadgeCanvasDimensions(
            $design['page_size'] ?? 'Carta',
            $design['orientation'] ?? 'Vertical',
            $targetCanvasWidth
        );
        $image = $this->makeParticipantBadgeCanvas($canvasWidth, $canvasHeight);

        $fullName = trim((string) (($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')));
        $name = $fullName !== '' ? $fullName : 'Participante';

        $nameCenterX = (int) round($canvasWidth * ($this->participantBadgeCoordinate('name', 'x') / 100));
        $nameCenterY = (int) round($canvasHeight * ($this->participantBadgeCoordinate('name', 'y') / 100));
        $nameMaxWidth = (int) round($canvasWidth * 0.84);
        $this->drawCenteredSingleLineText($image, $name, $nameCenterX, $nameCenterY, $nameMaxWidth);

        $qrSizePercent = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $qrPixelSize = (int) round($canvasWidth * ($qrSizePercent / 100));
        $qrCenterX = (int) round($canvasWidth * ($this->participantBadgeCoordinate('qr', 'x') / 100));
        $qrCenterY = (int) round($canvasHeight * ($this->participantBadgeCoordinate('qr', 'y') / 100));
        $qrX = (int) round($qrCenterX - ($qrPixelSize / 2));
        $qrY = (int) round($qrCenterY - ($qrPixelSize / 2));

        $this->placeQrOnCanvas($image, $this->participantBadgeCode($registrationRecord), $qrX, $qrY, $qrPixelSize);

        $caption = 'Acceso participante';
        $captionY = (int) min($canvasHeight - 18, round($qrY + $qrPixelSize + max(24, $canvasHeight * 0.02)));
        $this->drawCenteredCaption($image, $caption, $qrCenterX, $captionY, max($qrPixelSize, 220));

        ob_start();
        imagepng($image);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }

    private function participantBadgeCanvasDimensions(?string $pageSize, ?string $orientation, ?int $targetCanvasWidth = null): array
    {
        return $this->invitationCanvasDimensions($pageSize, $orientation, $targetCanvasWidth);
    }

    private function participantBadgeTemplateFilesystemPath(): ?string
    {
        $file = $this->participantBadgeDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        $path = public_path($file);

        return file_exists($path) ? $path : null;
    }

    private function makeParticipantBadgeCanvas(int $canvasWidth, int $canvasHeight)
    {
        $image = imagecreatetruecolor($canvasWidth, $canvasHeight);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $backgroundPath = $this->participantBadgeTemplateFilesystemPath();
        $background = $backgroundPath ? $this->loadRasterImage($backgroundPath) : null;

        if ($background) {
            imagecopyresampled(
                $image,
                $background,
                0,
                0,
                0,
                0,
                $canvasWidth,
                $canvasHeight,
                imagesx($background),
                imagesy($background)
            );
            imagedestroy($background);

            return $image;
        }

        $ink = imagecolorallocate($image, 15, 23, 42);
        $violet = imagecolorallocate($image, 124, 92, 255);
        $violetSoft = imagecolorallocate($image, 245, 243, 255);
        $cyanSoft = imagecolorallocate($image, 224, 242, 254);
        $white = imagecolorallocate($image, 255, 255, 255);
        $soft = imagecolorallocate($image, 248, 250, 252);
        $line = imagecolorallocate($image, 203, 213, 225);

        imagefilledrectangle($image, 0, 0, $canvasWidth, $canvasHeight, $white);
        imagefilledellipse($image, (int) round($canvasWidth * 0.14), (int) round($canvasHeight * 0.12), (int) round($canvasWidth * 0.3), (int) round($canvasWidth * 0.3), $violetSoft);
        imagefilledellipse($image, (int) round($canvasWidth * 0.86), (int) round($canvasHeight * 0.9), (int) round($canvasWidth * 0.38), (int) round($canvasWidth * 0.38), $cyanSoft);
        imagefilledrectangle($image, (int) round($canvasWidth * 0.07), (int) round($canvasHeight * 0.18), (int) round($canvasWidth * 0.93), (int) round($canvasHeight * 0.93), $soft);
        imagerectangle($image, (int) round($canvasWidth * 0.07), (int) round($canvasHeight * 0.18), (int) round($canvasWidth * 0.93), (int) round($canvasHeight * 0.93), $line);

        $fontPath = $this->invitationFontPath();
        if ($fontPath) {
            imagettftext(
                $image,
                max(20, (int) round($canvasHeight * 0.024)),
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.1),
                $violet,
                $fontPath,
                'PARTICIPANTE'
            );

            $eventName = Str::limit((string) $this->evento->nombreevento, 42, '...');
            $eventFontSize = $this->fittedFontSize($eventName, $fontPath, (int) round($canvasWidth * 0.76), 30, 16);
            $eventBox = imagettfbbox($eventFontSize, 0, $fontPath, $eventName);
            $eventHeight = (int) abs($eventBox[7] - $eventBox[1]);
            imagettftext(
                $image,
                $eventFontSize,
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.16) + $eventHeight,
                $ink,
                $fontPath,
                $eventName
            );

            $subtitle = 'Gafete oficial de acceso al evento';
            imagettftext(
                $image,
                max(14, (int) round($canvasHeight * 0.016)),
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.15),
                $ink,
                $fontPath,
                $subtitle
            );
        }

        return $image;
    }

    private function participantBadgePdfOrientation(): string
    {
        $orientation = strtolower((string) (($this->participantBadgeDesign()['orientation'] ?? 'Vertical')));

        return $orientation === 'vertical' ? 'portrait' : 'landscape';
    }

    private function participantBadgePdfPaperDefinition(): string|array
    {
        $design = $this->participantBadgeDesign() ?? [];
        $pageSize = $design['page_size'] ?? 'Carta';
        $orientation = $this->participantBadgePdfOrientation();

        if ($pageSize === 'Personalizado') {
            $dimensions = $this->participantBadgeTemplatePointDimensions();

            if ($dimensions !== null) {
                [$width, $height] = $dimensions;

                if ($orientation === 'landscape' && $height > $width) {
                    [$width, $height] = [$height, $width];
                }

                if ($orientation === 'portrait' && $width > $height) {
                    [$width, $height] = [$height, $width];
                }

                return [0, 0, $width, $height];
            }
        }

        return match ($pageSize) {
            'Carta' => 'letter',
            'Oficio' => 'folio',
            'A4' => 'a4',
            'A3' => 'a3',
            'Legal' => 'legal',
            default => 'letter',
        };
    }

    private function participantBadgeTemplatePointDimensions(): ?array
    {
        $path = $this->participantBadgeTemplateFilesystemPath();

        if (! $path) {
            return null;
        }

        $size = @getimagesize($path);

        if (! $size || empty($size[0]) || empty($size[1])) {
            return null;
        }

        $widthPx = (float) $size[0];
        $heightPx = (float) $size[1];
        $pointsPerPixel = 72 / 96;

        return [
            round($widthPx * $pointsPerPixel, 2),
            round($heightPx * $pointsPerPixel, 2),
        ];
    }

    private function participantBadgePdfPageDimensions(string|array $paperDefinition, string $orientation): array
    {
        return $this->invitationPdfPageDimensions($paperDefinition, $orientation);
    }

    private function certificateDesignByType(string $type): ?array
    {
        return match ($type) {
            'participacion_general' => $this->generalParticipationCertificateDesign(),
            'diploma_conferencia' => $this->participationCertificateDesign(),
            default => null,
        };
    }

    private function certificateTemplateFilesystemPathByType(string $type): ?string
    {
        $file = $this->certificateDesignByType($type)['file'] ?? null;

        if (! $file) {
            return null;
        }

        $path = public_path($file);

        return file_exists($path) ? $path : null;
    }

    private function certificateCanvasDimensionsByType(string $type): array
    {
        $design = $this->certificateDesignByType($type) ?? [];

        return $this->invitationCanvasDimensions(
            $design['page_size'] ?? 'Carta',
            $design['orientation'] ?? 'Horizontal'
        );
    }

    private function certificateCoordinateByType(string $type, string $element, string $axis): float
    {
        $design = $this->certificateDesignByType($type) ?? [];
        $default = $element === 'name'
            ? ['x' => 50, 'y' => 53]
            : ['x' => 82, 'y' => 78];

        if (array_key_exists($element . '_x', $design) && array_key_exists($element . '_y', $design)) {
            return (float) max(0, min(100, (float) ($design[$element . '_' . $axis] ?? $default[$axis])));
        }

        return (float) $default[$axis];
    }

    private function certificatePdfOrientationByType(string $type): string
    {
        $design = $this->certificateDesignByType($type) ?? [];
        $orientation = strtolower((string) ($design['orientation'] ?? 'Horizontal'));

        return $orientation === 'vertical' ? 'portrait' : 'landscape';
    }

    private function certificatePdfPaperDefinitionByType(string $type): string|array
    {
        $design = $this->certificateDesignByType($type) ?? [];
        $pageSize = $design['page_size'] ?? 'Carta';
        $orientation = $this->certificatePdfOrientationByType($type);

        if ($pageSize === 'Personalizado') {
            $dimensions = $this->certificateTemplatePointDimensionsByType($type);

            if ($dimensions !== null) {
                [$width, $height] = $dimensions;

                if ($orientation === 'landscape' && $height > $width) {
                    [$width, $height] = [$height, $width];
                }

                if ($orientation === 'portrait' && $width > $height) {
                    [$width, $height] = [$height, $width];
                }

                return [0, 0, $width, $height];
            }
        }

        return match ($pageSize) {
            'Carta' => 'letter',
            'Oficio' => 'folio',
            'A4' => 'a4',
            'A3' => 'a3',
            'Legal' => 'legal',
            default => 'letter',
        };
    }

    private function certificateTemplatePointDimensionsByType(string $type): ?array
    {
        $path = $this->certificateTemplateFilesystemPathByType($type);

        if (! $path) {
            return null;
        }

        $size = @getimagesize($path);

        if (! $size || empty($size[0]) || empty($size[1])) {
            return null;
        }

        $widthPx = (float) $size[0];
        $heightPx = (float) $size[1];
        $pointsPerPixel = 72 / 96;

        return [
            round($widthPx * $pointsPerPixel, 2),
            round($heightPx * $pointsPerPixel, 2),
        ];
    }

    private function renderGeneratedCertificatePng(string $type, EventoRegistro $registrationRecord, ?Conferencia $conference, string $hash): string
    {
        $design = $this->certificateDesignByType($type) ?? [];
        [$canvasWidth, $canvasHeight] = $this->certificateCanvasDimensionsByType($type);
        $image = $this->makeGeneratedCertificateCanvas($type, $canvasWidth, $canvasHeight);

        $fullName = trim((string) (($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')));
        $name = $fullName !== '' ? $fullName : 'Participante';

        $nameCenterX = (int) round($canvasWidth * ($this->certificateCoordinateByType($type, 'name', 'x') / 100));
        $nameCenterY = (int) round($canvasHeight * ($this->certificateCoordinateByType($type, 'name', 'y') / 100));
        $nameMaxWidth = (int) round($canvasWidth * 0.82);
        $fontPercent = max(4, min(12, (float) ($design['font_size'] ?? 6)));
        $initialFontSize = max(18, (int) round($canvasHeight * ($fontPercent / 100)));
        $this->drawCenteredSingleLineText($image, $name, $nameCenterX, $nameCenterY, $nameMaxWidth, $initialFontSize);

        $qrSizePercent = max(10, min(26, (int) ($design['qr_size'] ?? 16)));
        $qrBase = strtolower((string) ($design['orientation'] ?? 'Horizontal')) === 'vertical' ? $canvasWidth : min($canvasWidth, $canvasHeight);
        $qrPixelSize = (int) round($qrBase * ($qrSizePercent / 100));
        $qrCenterX = (int) round($canvasWidth * ($this->certificateCoordinateByType($type, 'qr', 'x') / 100));
        $qrCenterY = (int) round($canvasHeight * ($this->certificateCoordinateByType($type, 'qr', 'y') / 100));
        $qrX = (int) round($qrCenterX - ($qrPixelSize / 2));
        $qrY = (int) round($qrCenterY - ($qrPixelSize / 2));

        $payload = implode('|', array_filter([
            'EVENTO:' . $this->evento->id,
            'TIPO:' . $type,
            'REGISTRO:' . $registrationRecord->id,
            $conference ? 'CONFERENCIA:' . $conference->id : null,
            'HASH:' . $hash,
        ]));

        $this->placeQrOnCanvas($image, $payload, $qrX, $qrY, $qrPixelSize);

        $captionY = (int) min($canvasHeight - 18, round($qrY + $qrPixelSize + max(24, $canvasHeight * 0.018)));
        $this->drawCenteredCaption($image, 'Hash: ' . $hash, $qrCenterX, $captionY, max($qrPixelSize + 100, 280));

        $fontPath = $this->invitationFontPath();
        if ($fontPath) {
            $footer = $type === 'participacion_general'
                ? 'Documento preparado para firma digital · Participación general'
                : 'Documento preparado para firma digital · Participación en conferencia';
            $footerBox = imagettfbbox(max(12, (int) round($canvasHeight * 0.012)), 0, $fontPath, $footer);
            $footerWidth = abs($footerBox[2] - $footerBox[0]);
            imagettftext(
                $image,
                max(12, (int) round($canvasHeight * 0.012)),
                0,
                (int) max(24, round(($canvasWidth - $footerWidth) / 2)),
                (int) min($canvasHeight - 24, $canvasHeight * 0.965),
                imagecolorallocate($image, 71, 85, 105),
                $fontPath,
                $footer
            );
        }

        ob_start();
        imagepng($image);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }

    private function makeGeneratedCertificateCanvas(string $type, int $canvasWidth, int $canvasHeight)
    {
        $image = imagecreatetruecolor($canvasWidth, $canvasHeight);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $backgroundPath = $this->certificateTemplateFilesystemPathByType($type);
        $background = $backgroundPath ? $this->loadRasterImage($backgroundPath) : null;

        if ($background) {
            imagecopyresampled(
                $image,
                $background,
                0,
                0,
                0,
                0,
                $canvasWidth,
                $canvasHeight,
                imagesx($background),
                imagesy($background)
            );
            imagedestroy($background);

            return $image;
        }

        return $this->makeParticipationCertificateCanvas($canvasWidth, $canvasHeight);
    }

    private function persistCertificatePdf(string $type, EventoRegistro $registrationRecord, ?Conferencia $conference = null): EventoCertificado
    {
        $certificateQuery = EventoCertificado::withTrashed()
            ->where('evento_id', $this->evento->id)
            ->where('persona_id', $registrationRecord->persona_id)
            ->where('evento_registro_id', $registrationRecord->id)
            ->where('tipo', $type);

        if ($conference) {
            $certificateQuery->where('conferencia_id', $conference->id);
        } else {
            $certificateQuery->whereNull('conferencia_id');
        }

        $certificate = $certificateQuery->first() ?? new EventoCertificado();

        if ($certificate->trashed()) {
            $certificate->restore();
        }

        $hash = $certificate->hash_unico ?: strtoupper(Str::random(20));
        $participantName = trim((string) (($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? ''))) ?: 'Participante';
        $pngBinary = $this->renderGeneratedCertificatePng($type, $registrationRecord, $conference, $hash);

        $paperDefinition = $this->certificatePdfPaperDefinitionByType($type);
        $orientation = $this->certificatePdfOrientationByType($type);
        [$pageWidthPt, $pageHeightPt] = $this->invitationPdfPageDimensions($paperDefinition, $orientation);
        $title = $type === 'participacion_general'
            ? 'Certificado general - ' . $participantName
            : 'Certificado por conferencia - ' . $participantName;

        $pdf = PDF::loadView('pdf.evento-certificado-generado', [
            'title' => $title,
            'imageDataUri' => 'data:image/png;base64,' . base64_encode($pngBinary),
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $orientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        $pdfBinary = $pdf->output();
        $fileName = Str::slug($participantName) . '-' . Str::slug($conference?->nombre ?: 'participacion-general') . '-' . strtolower($hash) . '.pdf';
        $path = 'certificados/evento-' . $this->evento->id . '/' . $type . '/' . $fileName;
        Storage::disk('public')->put($path, $pdfBinary);

        $certificate->fill([
            'evento_id' => $this->evento->id,
            'persona_id' => $registrationRecord->persona_id,
            'evento_registro_id' => $registrationRecord->id,
            'conferencia_id' => $conference?->id,
            'tipo' => $type,
            'nombre_certificado' => $title,
            'hash_unico' => $hash,
            'pdf_path' => $path,
            'generated_at' => now(),
            'metadata' => [
                'event_name' => $this->evento->nombreevento,
                'participant_name' => $participantName,
                'conference_name' => $conference?->nombre,
                'pdf_sha256' => hash('sha256', $pdfBinary),
                'signature_ready' => true,
                'certificate_type' => $type,
            ],
        ]);
        $certificate->save();

        return $certificate;
    }

    private function attendanceRecordForDownload(int $attendanceId): ConferenciaRegistroAsistencia
    {
        return ConferenciaRegistroAsistencia::query()
            ->whereHas('conferencia', fn ($query) => $query->where('IdEvento', $this->evento->id))
            ->with(['conferencia.tipoConferencia', 'eventoRegistro.persona.tipoPerfil'])
            ->findOrFail($attendanceId);
    }

    private function validateDocumentGenerationRequest(string $type): ?string
    {
        return match ($type) {
            self::DOCUMENT_TYPE_INVITATIONS => $this->invitationDesignConfigured()
                ? null
                : 'Configura primero la plantilla de invitaciones del evento.',
            self::DOCUMENT_TYPE_STAFF_BADGES => $this->staffBadgeDesignConfigured()
                ? null
                : 'Configura primero la plantilla de gafete del staff del evento.',
            self::DOCUMENT_TYPE_REGISTRATION_BADGES => $this->participantBadgeDesignConfigured()
                ? null
                : 'Configura primero la plantilla de gafete de participantes del evento.',
            self::DOCUMENT_TYPE_GENERAL_CERTIFICATES => ! $this->evento->genera_diploma_participacion
                ? 'Este evento no está configurado para generar diplomas de participación general.'
                : ($this->generalParticipationCertificateDesignConfigured()
                    ? null
                    : 'Configura primero la plantilla de diplomas de participación general del evento.'),
            default => 'Tipo de documento no soportado.',
        };
    }

    private function documentGenerationTotal(string $type): int
    {
        return match ($type) {
            self::DOCUMENT_TYPE_INVITATIONS => (int) $this->evento->invitaciones()->count(),
            self::DOCUMENT_TYPE_STAFF_BADGES => (int) $this->evento->staffMembers()->where('activo', true)->whereNotNull('perfil_completado_at')->count(),
            self::DOCUMENT_TYPE_REGISTRATION_BADGES => (int) $this->evento->registros()->count(),
            self::DOCUMENT_TYPE_GENERAL_CERTIFICATES => (int) $this->evento->registros()->whereNotNull('persona_id')->whereHas('persona')->count(),
            default => 0,
        };
    }

    private function documentGenerationPayload(string $type): array
    {
        return match ($type) {
            self::DOCUMENT_TYPE_INVITATIONS => [
                'paper' => $this->invitationPdfPaperDefinition(),
                'orientation' => $this->invitationPdfOrientation(),
            ],
            self::DOCUMENT_TYPE_STAFF_BADGES => [
                'paper' => $this->staffBadgePdfPaperDefinition(),
                'orientation' => $this->staffBadgePdfOrientation(),
            ],
            self::DOCUMENT_TYPE_REGISTRATION_BADGES => [
                'paper' => $this->participantBadgePdfPaperDefinition(),
                'orientation' => $this->participantBadgePdfOrientation(),
            ],
            self::DOCUMENT_TYPE_GENERAL_CERTIFICATES => [
                'paper' => $this->certificatePdfPaperDefinitionByType('participacion_general'),
                'orientation' => $this->certificatePdfOrientationByType('participacion_general'),
            ],
            default => [],
        };
    }

    private function initializeDocumentTask(EventoDocumentoProceso $task): void
    {
        $task->directorio_temporal = 'tmp/documentos-evento/' . $task->tipo . '-evento-' . $this->evento->id . '-' . Str::uuid();
        Storage::disk('local')->makeDirectory($task->directorio_temporal);

        $task->estado = 'procesando';
        $task->iniciado_at = now();
        $task->mensaje_estado = $this->documentGenerationDefinitions()[$task->tipo]['start_message'] ?? 'Iniciando proceso...';
        $task->save();
    }

    private function processInvitationDocumentBatch(EventoDocumentoProceso $task): void
    {
        $records = EventoInvitacion::query()
            ->where('evento_id', $this->evento->id)
            ->when($task->ultimo_cursor_id, fn ($query) => $query->where('id', '>', $task->ultimo_cursor_id))
            ->orderBy('id')
            ->limit($task->tamano_lote)
            ->get();

        if ($records->isEmpty()) {
            $this->finalizeDocumentTask($task, 'invitaciones-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $batchNumber = (int) floor($task->procesados / max(1, $task->tamano_lote)) + 1;
        $this->buildInvitationBatchPdfForTask($task, $records, $batchNumber);

        foreach ($records as $record) {
            $task->ultimo_cursor_id = (int) $record->id;
            $task->procesados++;
            $task->generados++;
        }

        if ((int) $task->procesados >= (int) $task->total) {
            $task->save();
            $this->finalizeDocumentTask($task, 'invitaciones-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $task->mensaje_estado = 'Lote de invitaciones generado. Pendientes: ' . max(0, $task->total - $task->procesados) . '.';
        $task->save();
    }

    private function processStaffBadgeDocumentBatch(EventoDocumentoProceso $task): void
    {
        $records = EventoStaff::query()
            ->where('evento_id', $this->evento->id)
            ->where('activo', true)
            ->whereNotNull('perfil_completado_at')
            ->with('persona.user', 'persona.tipoPerfil')
            ->when($task->ultimo_cursor_id, fn ($query) => $query->where('id', '>', $task->ultimo_cursor_id))
            ->orderBy('id')
            ->limit($task->tamano_lote)
            ->get();

        if ($records->isEmpty()) {
            $this->finalizeDocumentTask($task, 'gafetes-staff-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $batchNumber = (int) floor($task->procesados / max(1, $task->tamano_lote)) + 1;
        $this->buildStaffBadgeBatchPdfForTask($task, $records, $batchNumber);

        foreach ($records as $record) {
            $task->ultimo_cursor_id = (int) $record->id;
            $task->procesados++;
            $task->generados++;
        }

        if ((int) $task->procesados >= (int) $task->total) {
            $task->save();
            $this->finalizeDocumentTask($task, 'gafetes-staff-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $task->mensaje_estado = 'Lote de gafetes del staff generado. Pendientes: ' . max(0, $task->total - $task->procesados) . '.';
        $task->save();
    }

    private function processRegistrationBadgeDocumentBatch(EventoDocumentoProceso $task): void
    {
        $records = EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->with('persona.tipoPerfil', 'persona.user', 'tipoPerfil', 'metodoPago')
            ->when($task->ultimo_cursor_id, fn ($query) => $query->where('id', '>', $task->ultimo_cursor_id))
            ->orderBy('id')
            ->limit($task->tamano_lote)
            ->get();

        if ($records->isEmpty()) {
            $this->finalizeDocumentTask($task, 'gafetes-participantes-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $batchNumber = (int) floor($task->procesados / max(1, $task->tamano_lote)) + 1;
        $this->buildRegistrationBadgeBatchPdfForTask($task, $records, $batchNumber);

        foreach ($records as $record) {
            $task->ultimo_cursor_id = (int) $record->id;
            $task->procesados++;
            $task->generados++;
        }

        if ((int) $task->procesados >= (int) $task->total) {
            $task->save();
            $this->finalizeDocumentTask($task, 'gafetes-participantes-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $task->mensaje_estado = 'Lote de gafetes de participantes generado. Pendientes: ' . max(0, $task->total - $task->procesados) . '.';
        $task->save();
    }

    private function processGeneralCertificateDocumentBatch(EventoDocumentoProceso $task): void
    {
        $records = EventoRegistro::query()
            ->where('evento_id', $this->evento->id)
            ->whereNotNull('persona_id')
            ->whereHas('persona')
            ->with('persona.tipoPerfil', 'tipoPerfil')
            ->when($task->ultimo_cursor_id, fn ($query) => $query->where('id', '>', $task->ultimo_cursor_id))
            ->orderBy('id')
            ->limit($task->tamano_lote)
            ->get();

        if ($records->isEmpty()) {
            $this->refreshEventRelations();
            $this->finalizeDocumentTask($task, 'certificados-generales-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $batchNumber = (int) floor($task->procesados / max(1, $task->tamano_lote)) + 1;
        $generatedPaths = [];

        foreach ($records as $record) {
            if (! $record->persona) {
                $task->omitidos++;
                $task->procesados++;
                $task->ultimo_cursor_id = (int) $record->id;

                continue;
            }

            $certificate = $this->persistCertificatePdf('participacion_general', $record, null);
            $task->procesados++;
            $task->generados++;
            $task->ultimo_cursor_id = (int) $record->id;

            $absolutePath = storage_path('app/public/' . $certificate->pdf_path);

            if (file_exists($absolutePath)) {
                $generatedPaths[] = $absolutePath;
            }
        }

        if ($generatedPaths !== []) {
            $relativePath = $task->directorio_temporal . '/batch-' . str_pad((string) $batchNumber, 4, '0', STR_PAD_LEFT) . '.pdf';
            $this->mergePdfFiles($generatedPaths, storage_path('app/' . $relativePath));
        }

        if ((int) $task->procesados >= (int) $task->total) {
            $task->save();
            $this->refreshEventRelations();
            $this->finalizeDocumentTask($task, 'certificados-generales-evento-' . $this->evento->id . '.pdf');

            return;
        }

        $task->mensaje_estado = 'Lote de certificados generales procesado. Pendientes: ' . max(0, $task->total - $task->procesados) . '.';
        $task->save();
    }

    private function buildInvitationBatchPdfForTask(EventoDocumentoProceso $task, $records, int $batchNumber): void
    {
        $pdfOrientation = $this->invitationPdfOrientation();
        $paperDefinition = $this->invitationPdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->invitationPdfPageDimensions($paperDefinition, $pdfOrientation);

        $payloads = $records->map(function (EventoInvitacion $invitation) {
            return [
                'nombre_invitado' => $invitation->nombre_invitado,
                'codigo' => $invitation->codigo,
                'cupos' => $invitation->cupos,
                'image_data_uri' => 'data:image/png;base64,' . base64_encode($this->renderInvitationPng($invitation)),
            ];
        })->all();

        $pdf = PDF::loadView('pdf.evento-invitaciones-lote', [
            'evento' => $this->evento,
            'invitations' => $payloads,
            'pdfOrientation' => $pdfOrientation,
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        $relativePath = $task->directorio_temporal . '/batch-' . str_pad((string) $batchNumber, 4, '0', STR_PAD_LEFT) . '.pdf';
        Storage::disk('local')->put($relativePath, $pdf->output());
    }

    private function buildStaffBadgeBatchPdfForTask(EventoDocumentoProceso $task, $records, int $batchNumber): void
    {
        $pdfOrientation = $this->staffBadgePdfOrientation();
        $paperDefinition = $this->staffBadgePdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->staffBadgePdfPageDimensions($paperDefinition, $pdfOrientation);

        $payloads = $records->map(function (EventoStaff $staffMember) {
            return [
                'nombre' => $staffMember->nombre,
                'codigo' => $this->staffBadgeCode($staffMember),
                'image_data_uri' => 'data:image/png;base64,' . base64_encode($this->renderStaffBadgePng($staffMember, 1000)),
            ];
        })->all();

        $pdf = PDF::loadView('pdf.evento-staff-gafetes-lote', [
            'evento' => $this->evento,
            'staffBadges' => $payloads,
            'pdfOrientation' => $pdfOrientation,
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        $relativePath = $task->directorio_temporal . '/batch-' . str_pad((string) $batchNumber, 4, '0', STR_PAD_LEFT) . '.pdf';
        Storage::disk('local')->put($relativePath, $pdf->output());
    }

    private function buildRegistrationBadgeBatchPdfForTask(EventoDocumentoProceso $task, $records, int $batchNumber): void
    {
        $pdfOrientation = $this->participantBadgePdfOrientation();
        $paperDefinition = $this->participantBadgePdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->participantBadgePdfPageDimensions($paperDefinition, $pdfOrientation);

        $payloads = $records->map(function (EventoRegistro $registrationRecord) {
            return [
                'nombre' => trim(($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')) ?: 'Participante',
                'codigo' => $this->participantBadgeCode($registrationRecord),
                'image_data_uri' => 'data:image/png;base64,' . base64_encode($this->renderParticipantBadgePng($registrationRecord, 1000)),
            ];
        })->all();

        $pdf = PDF::loadView('pdf.evento-inscripciones-gafetes-lote', [
            'evento' => $this->evento,
            'participantBadges' => $payloads,
            'pdfOrientation' => $pdfOrientation,
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        $relativePath = $task->directorio_temporal . '/batch-' . str_pad((string) $batchNumber, 4, '0', STR_PAD_LEFT) . '.pdf';
        Storage::disk('local')->put($relativePath, $pdf->output());
    }

    private function finalizeDocumentTask(EventoDocumentoProceso $task, string $outputFileName): void
    {
        $batchFiles = collect(Storage::disk('local')->files($task->directorio_temporal))
            ->filter(fn (string $path) => Str::endsWith($path, '.pdf') && ! Str::endsWith($path, '/' . $outputFileName))
            ->sort()
            ->values()
            ->all();

        if ($batchFiles === []) {
            $this->failDocumentTask($task, 'No se pudo construir ningún lote de PDF para este proceso.');

            return;
        }

        $outputRelativePath = 'documentos-generados/evento-' . $this->evento->id . '/' . $outputFileName;
        Storage::disk('local')->makeDirectory(dirname($outputRelativePath));
        $outputAbsolutePath = storage_path('app/' . $outputRelativePath);
        $inputPaths = array_map(fn (string $relativePath) => storage_path('app/' . $relativePath), $batchFiles);

        $this->mergePdfFiles($inputPaths, $outputAbsolutePath);

        $task->estado = 'completado';
        $task->ruta_salida = $outputRelativePath;
        $task->mensaje_estado = 'El PDF fue generado correctamente y ya está listo para guardarse.';
        $task->completado_at = now();
        $task->save();

        if (! app()->runningInConsole()) {
            session()->flash('message', 'Se completó la generación de ' . ($this->documentGenerationDefinitions()[$task->tipo]['short_label'] ?? 'los documentos') . '.');
        }
    }

    private function failDocumentTask(EventoDocumentoProceso $task, string $message): void
    {
        $task->estado = 'fallido';
        $task->error_detalle = $message;
        $task->mensaje_estado = 'El proceso falló. ' . $message;
        $task->completado_at = now();
        $task->save();

        if (! app()->runningInConsole()) {
            session()->flash('error', $task->mensaje_estado);
        } else {
            Log::error('Fallo en proceso de documentos del evento', [
                'evento_id' => $this->evento->id,
                'task_id' => $task->id,
                'tipo' => $task->tipo,
                'mensaje' => $task->mensaje_estado,
            ]);
        }
    }

    private function mergePdfFiles(array $absolutePaths, string $outputAbsolutePath): void
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '1024M');

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        foreach ($absolutePaths as $absolutePath) {
            if (! file_exists($absolutePath)) {
                continue;
            }

            $pageCount = $pdf->setSourceFile($absolutePath);

            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                $templateId = $pdf->importPage($pageNumber);
                $size = $pdf->getTemplateSize($templateId);
                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        $directory = dirname($outputAbsolutePath);

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $pdf->Output('F', $outputAbsolutePath);
    }

    private function cleanupDocumentTaskFiles(EventoDocumentoProceso $task): void
    {
        if ($task->directorio_temporal && Storage::disk('local')->exists($task->directorio_temporal)) {
            Storage::disk('local')->deleteDirectory($task->directorio_temporal);
        }

        if ($task->ruta_salida && Storage::disk('local')->exists($task->ruta_salida)) {
            Storage::disk('local')->delete($task->ruta_salida);
        }
    }

    private function launchBackgroundDocumentProcessor(): void
    {
        $logPath = storage_path('logs/evento-document-task-' . $this->evento->id . '.log');
        $phpBinary = $this->phpCliBinary();

        $command = sprintf(
            'nohup %s -d memory_limit=1024M %s evento:process-document-tasks %d --no-interaction >> %s 2>&1 &',
            escapeshellarg($phpBinary),
            escapeshellarg(base_path('artisan')),
            (int) $this->evento->id,
            escapeshellarg($logPath)
        );

        exec($command);
    }

    private function resumeStalledDocumentTasks(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        $task = EventoDocumentoProceso::query()
            ->where('evento_id', $this->evento->id)
            ->whereIn('estado', ['pendiente', 'procesando'])
            ->where('updated_at', '<', now()->subMinutes(2))
            ->orderBy('updated_at')
            ->orderBy('id')
            ->first();

        if (! $task) {
            return;
        }

        $runningHeartbeat = Cache::get('evento-document-task-running-' . $this->evento->id);

        if (is_numeric($runningHeartbeat) && (int) $runningHeartbeat >= now()->subSeconds(90)->timestamp) {
            return;
        }

        $lock = Cache::lock('evento-document-task-resume-' . $this->evento->id, 75);

        if (! $lock->get()) {
            return;
        }

        try {
            $task->mensaje_estado = 'Se detectó una pausa en el proceso. Reintentando en segundo plano...';
            $task->save();
            $this->launchBackgroundDocumentProcessor();
        } catch (\Throwable $exception) {
            report($exception);
        } finally {
            optional($lock)->release();
        }
    }

    private function phpCliBinary(): string
    {
        $binary = PHP_BINARY;
        $binaryName = strtolower(basename($binary));

        if (str_contains($binaryName, 'php') && ! str_contains($binaryName, 'fpm') && ! str_contains($binaryName, 'cgi')) {
            return $binary;
        }

        $cliCandidate = rtrim(PHP_BINDIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'php';

        if (is_file($cliCandidate) && is_executable($cliCandidate)) {
            return $cliCandidate;
        }

        return 'php';
    }

    private function resetGeneralCertificateGenerationState(): void
    {
        $this->generalCertificateGenerationProcessing = false;
        $this->generalCertificateGenerationCompleted = false;
        $this->generalCertificateGenerationTotal = 0;
        $this->generalCertificateGenerationProcessed = 0;
        $this->generalCertificateGenerationGenerated = 0;
        $this->generalCertificateGenerationSkipped = 0;
        $this->generalCertificateGenerationLastRecordId = 0;
        $this->generalCertificateGenerationStatus = '';
    }

    private function resetRegistrationBadgeGenerationState(): void
    {
        $this->registrationBadgeGenerationProcessing = false;
        $this->registrationBadgeGenerationCompleted = false;
        $this->registrationBadgeGenerationTotal = 0;
        $this->registrationBadgeGenerationProcessed = 0;
        $this->registrationBadgeGenerationGenerated = 0;
        $this->registrationBadgeGenerationLastRecordId = 0;
        $this->registrationBadgeGenerationStatus = '';
        $this->registrationBadgeGenerationTempDirectory = '';
        $this->registrationBadgeGenerationOutputPath = '';
    }

    private function buildRegistrationBadgeBatchPdf($records, int $batchNumber): ?string
    {
        if ($records->isEmpty()) {
            return null;
        }

        $pdfOrientation = $this->participantBadgePdfOrientation();
        $paperDefinition = $this->participantBadgePdfPaperDefinition();
        [$pageWidthPt, $pageHeightPt] = $this->participantBadgePdfPageDimensions($paperDefinition, $pdfOrientation);

        $payloads = $records->map(function (EventoRegistro $registrationRecord) {
            return [
                'nombre' => trim(($registrationRecord->persona?->nombre ?? '') . ' ' . ($registrationRecord->persona?->apellido ?? '')) ?: 'Participante',
                'codigo' => $this->participantBadgeCode($registrationRecord),
                'image_data_uri' => 'data:image/png;base64,' . base64_encode($this->renderParticipantBadgePng($registrationRecord)),
            ];
        })->all();

        $pdf = PDF::loadView('pdf.evento-inscripciones-gafetes-lote', [
            'evento' => $this->evento,
            'participantBadges' => $payloads,
            'pdfOrientation' => $pdfOrientation,
            'pageWidthPt' => $pageWidthPt,
            'pageHeightPt' => $pageHeightPt,
        ])->setPaper($paperDefinition, $pdfOrientation);

        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);

        $relativePath = $this->registrationBadgeGenerationTempDirectory . '/batch-' . str_pad((string) $batchNumber, 4, '0', STR_PAD_LEFT) . '.pdf';
        Storage::disk('local')->put($relativePath, $pdf->output());

        return $relativePath;
    }

    private function finalizeRegistrationBadgeGeneration(): void
    {
        $batchFiles = collect(Storage::disk('local')->files($this->registrationBadgeGenerationTempDirectory))
            ->filter(fn (string $path) => Str::endsWith($path, '.pdf'))
            ->sort()
            ->values();

        if ($batchFiles->isEmpty()) {
            $this->registrationBadgeGenerationProcessing = false;
            $this->registrationBadgeGenerationCompleted = false;
            $this->registrationBadgeGenerationStatus = 'No se pudieron generar los lotes de gafetes.';
            session()->flash('error', 'No fue posible generar los lotes de gafetes del evento.');

            return;
        }

        $outputRelativePath = $this->registrationBadgeGenerationTempDirectory . '/gafetes-participantes-evento-' . $this->evento->id . '.pdf';
        $outputAbsolutePath = storage_path('app/' . $outputRelativePath);

        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        foreach ($batchFiles as $batchFile) {
            $absoluteBatchPath = storage_path('app/' . $batchFile);
            $pageCount = $pdf->setSourceFile($absoluteBatchPath);

            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                $templateId = $pdf->importPage($pageNumber);
                $size = $pdf->getTemplateSize($templateId);
                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        $pdf->Output('F', $outputAbsolutePath);
        $this->registrationBadgeGenerationOutputPath = $outputRelativePath;
        $this->registrationBadgeGenerationProcessing = false;
        $this->registrationBadgeGenerationCompleted = true;
        $this->registrationBadgeGenerationStatus = 'El PDF final de gafetes está listo para descargarse.';

        session()->flash('message', 'Se generó el PDF consolidado de ' . $this->registrationBadgeGenerationGenerated . ' gafetes del evento.');
    }

    private function cleanupRegistrationBadgeTemporaryFiles(): void
    {
        if ($this->registrationBadgeGenerationTempDirectory !== '' && Storage::disk('local')->exists($this->registrationBadgeGenerationTempDirectory)) {
            Storage::disk('local')->deleteDirectory($this->registrationBadgeGenerationTempDirectory);
        }
    }

    private function participationCertificateCode(ConferenciaRegistroAsistencia $attendance): string
    {
        return 'CERT-' . $this->evento->id . '-' . $attendance->conferencia_id . '-' . $attendance->id;
    }

    private function renderParticipationCertificatePng(ConferenciaRegistroAsistencia $attendance): string
    {
        $design = $this->participationCertificateDesign() ?? [];
        [$canvasWidth, $canvasHeight] = $this->participationCertificateCanvasDimensions(
            $design['page_size'] ?? 'Carta',
            $design['orientation'] ?? 'Horizontal'
        );
        $image = $this->makeParticipationCertificateCanvas($canvasWidth, $canvasHeight);

        $fullName = trim((string) (($attendance->eventoRegistro?->persona?->nombre ?? '') . ' ' . ($attendance->eventoRegistro?->persona?->apellido ?? '')));
        $name = $fullName !== '' ? $fullName : 'Participante';

        $nameCenterX = (int) round($canvasWidth * ($this->participationCertificateCoordinate('name', 'x') / 100));
        $nameCenterY = (int) round($canvasHeight * ($this->participationCertificateCoordinate('name', 'y') / 100));
        $nameMaxWidth = (int) round($canvasWidth * 0.82);
        $fontPercent = max(4, min(12, (float) ($design['font_size'] ?? 6)));
        $initialFontSize = max(18, (int) round($canvasHeight * ($fontPercent / 100)));
        $this->drawCenteredSingleLineText($image, $name, $nameCenterX, $nameCenterY, $nameMaxWidth, $initialFontSize);

        $qrSizePercent = max(10, min(26, (int) ($design['qr_size'] ?? 16)));
        $qrBase = strtolower((string) ($design['orientation'] ?? 'Horizontal')) === 'vertical' ? $canvasWidth : min($canvasWidth, $canvasHeight);
        $qrPixelSize = (int) round($qrBase * ($qrSizePercent / 100));
        $qrCenterX = (int) round($canvasWidth * ($this->participationCertificateCoordinate('qr', 'x') / 100));
        $qrCenterY = (int) round($canvasHeight * ($this->participationCertificateCoordinate('qr', 'y') / 100));
        $qrX = (int) round($qrCenterX - ($qrPixelSize / 2));
        $qrY = (int) round($qrCenterY - ($qrPixelSize / 2));

        $this->placeQrOnCanvas($image, $this->participationCertificateCode($attendance), $qrX, $qrY, $qrPixelSize);
        $caption = 'Verificación del certificado';
        $captionY = (int) min($canvasHeight - 18, round($qrY + $qrPixelSize + max(24, $canvasHeight * 0.018)));
        $this->drawCenteredCaption($image, $caption, $qrCenterX, $captionY, max($qrPixelSize + 60, 220));

        ob_start();
        imagepng($image);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }

    private function participationCertificateCanvasDimensions(?string $pageSize, ?string $orientation): array
    {
        return $this->invitationCanvasDimensions($pageSize, $orientation);
    }

    private function participationCertificateCoordinate(string $element, string $axis): float
    {
        $design = $this->participationCertificateDesign() ?? [];
        $default = $element === 'name'
            ? ['x' => 50, 'y' => 53]
            : ['x' => 82, 'y' => 78];

        if (array_key_exists($element . '_x', $design) && array_key_exists($element . '_y', $design)) {
            return (float) max(0, min(100, (float) ($design[$element . '_' . $axis] ?? $default[$axis])));
        }

        return (float) $default[$axis];
    }

    private function participationCertificateTemplateFilesystemPath(): ?string
    {
        $file = $this->participationCertificateDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        $path = public_path($file);

        return file_exists($path) ? $path : null;
    }

    private function makeParticipationCertificateCanvas(int $canvasWidth, int $canvasHeight)
    {
        $image = imagecreatetruecolor($canvasWidth, $canvasHeight);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $backgroundPath = $this->participationCertificateTemplateFilesystemPath();
        $background = $backgroundPath ? $this->loadRasterImage($backgroundPath) : null;

        if ($background) {
            imagecopyresampled(
                $image,
                $background,
                0,
                0,
                0,
                0,
                $canvasWidth,
                $canvasHeight,
                imagesx($background),
                imagesy($background)
            );
            imagedestroy($background);

            return $image;
        }

        $white = imagecolorallocate($image, 255, 255, 255);
        $slate = imagecolorallocate($image, 15, 23, 42);
        $violet = imagecolorallocate($image, 124, 92, 255);
        $softViolet = imagecolorallocate($image, 245, 243, 255);
        $softBlue = imagecolorallocate($image, 239, 246, 255);
        $line = imagecolorallocate($image, 203, 213, 225);

        imagefilledrectangle($image, 0, 0, $canvasWidth, $canvasHeight, $white);
        imagefilledellipse($image, (int) round($canvasWidth * 0.15), (int) round($canvasHeight * 0.18), (int) round($canvasWidth * 0.32), (int) round($canvasWidth * 0.32), $softViolet);
        imagefilledellipse($image, (int) round($canvasWidth * 0.9), (int) round($canvasHeight * 0.82), (int) round($canvasWidth * 0.38), (int) round($canvasWidth * 0.38), $softBlue);
        imagerectangle($image, (int) round($canvasWidth * 0.04), (int) round($canvasHeight * 0.06), (int) round($canvasWidth * 0.96), (int) round($canvasHeight * 0.94), $line);

        $fontPath = $this->invitationFontPath();
        if ($fontPath) {
            imagettftext(
                $image,
                max(18, (int) round($canvasHeight * 0.028)),
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.12),
                $violet,
                $fontPath,
                'CERTIFICADO DE PARTICIPACIÓN'
            );

            $eventName = Str::limit((string) $this->evento->nombreevento, 70, '...');
            $eventFontSize = $this->fittedFontSize($eventName, $fontPath, (int) round($canvasWidth * 0.78), 22, 12);
            imagettftext(
                $image,
                $eventFontSize,
                0,
                (int) round($canvasWidth * 0.08),
                (int) round($canvasHeight * 0.18),
                $slate,
                $fontPath,
                $eventName
            );
        }

        return $image;
    }

    private function participationCertificatePdfOrientation(): string
    {
        $orientation = strtolower((string) (($this->participationCertificateDesign()['orientation'] ?? 'Horizontal')));

        return $orientation === 'vertical' ? 'portrait' : 'landscape';
    }

    private function participationCertificatePdfPaperDefinition(): string|array
    {
        $design = $this->participationCertificateDesign() ?? [];
        $pageSize = $design['page_size'] ?? 'Carta';
        $orientation = $this->participationCertificatePdfOrientation();

        if ($pageSize === 'Personalizado') {
            $dimensions = $this->participationCertificateTemplatePointDimensions();

            if ($dimensions !== null) {
                [$width, $height] = $dimensions;

                if ($orientation === 'landscape' && $height > $width) {
                    [$width, $height] = [$height, $width];
                }

                if ($orientation === 'portrait' && $width > $height) {
                    [$width, $height] = [$height, $width];
                }

                return [0, 0, $width, $height];
            }
        }

        return match ($pageSize) {
            'Carta' => 'letter',
            'Oficio' => 'folio',
            'A4' => 'a4',
            'A3' => 'a3',
            'Legal' => 'legal',
            default => 'letter',
        };
    }

    private function participationCertificateTemplatePointDimensions(): ?array
    {
        $path = $this->participationCertificateTemplateFilesystemPath();

        if (! $path) {
            return null;
        }

        $size = @getimagesize($path);

        if (! $size || empty($size[0]) || empty($size[1])) {
            return null;
        }

        $widthPx = (float) $size[0];
        $heightPx = (float) $size[1];
        $pointsPerPixel = 72 / 96;

        return [
            round($widthPx * $pointsPerPixel, 2),
            round($heightPx * $pointsPerPixel, 2),
        ];
    }

    private function participationCertificatePdfPageDimensions(string|array $paperDefinition, string $orientation): array
    {
        return $this->invitationPdfPageDimensions($paperDefinition, $orientation);
    }

    private function markInvitationAsSent(EventoInvitacion $invitation, string $channel): void
    {
        $now = Carbon::now();
        $payload = [
            'enviada_at' => $invitation->enviada_at ?? $now,
            'ultimo_canal_envio' => $channel,
        ];

        if ($channel === 'correo') {
            $payload['correo_enviado_at'] = $now;
        }

        if ($channel === 'whatsapp') {
            $payload['whatsapp_enviado_at'] = $now;
        }

        $invitation->update($payload);

        $this->evento->load([
            'modalidad',
            'tipoEvento',
            'localidad',
            'invitaciones',
            'registros.persona',
            'registros.tipoPerfil',
            'conferencias',
        ]);

        session()->flash('message', 'Invitación marcada como enviada por ' . $channel . '.');
    }

    private function renderInvitationSvg(EventoInvitacion $invitation): string
    {
        $design = $this->invitationDesign() ?? [];
        [$svgWidth, $svgHeight] = $this->invitationCanvasDimensions($design['page_size'] ?? 'Carta', $design['orientation'] ?? 'Horizontal');
        $backgroundImage = $this->svgImageHrefFromInvitationTemplate();
        $qrBase64 = QRCodeService::generateTextQRCode($invitation->codigo, 360);
        $nameX = $svgWidth * ($this->invitationCoordinate('name', 'x') / 100);
        $nameY = $svgHeight * ($this->invitationCoordinate('name', 'y') / 100);
        $qrSizePercent = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $qrPixelSize = $svgWidth * ($qrSizePercent / 100);
        $qrX = ($svgWidth * ($this->invitationCoordinate('qr', 'x') / 100)) - ($qrPixelSize / 2);
        $qrY = ($svgHeight * ($this->invitationCoordinate('qr', 'y') / 100)) - ($qrPixelSize / 2);
        $captionY = $qrY + $qrPixelSize + max(18, $svgHeight * 0.024);
        $qrCenterX = $qrX + ($qrPixelSize / 2);
        $nameFontSize = $this->invitationNameSvgFontSize($invitation);
        $eventTitle = htmlspecialchars($this->evento->nombreevento, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $guestName = htmlspecialchars($invitation->nombre_invitado, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $cuposLabel = htmlspecialchars('Válido por ' . $invitation->cupos . ' ' . Str::plural('cupo', $invitation->cupos), ENT_QUOTES | ENT_XML1, 'UTF-8');

        $backgroundLayer = $backgroundImage
            ? '<image href="' . $backgroundImage . '" x="0" y="0" width="' . $svgWidth . '" height="' . $svgHeight . '" preserveAspectRatio="xMidYMid meet" />'
            : '
                <defs>
                    <linearGradient id="fallbackBg" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#0f172a"/>
                        <stop offset="50%" stop-color="#312e81"/>
                        <stop offset="100%" stop-color="#2563eb"/>
                    </linearGradient>
                </defs>
                <rect x="0" y="0" width="' . $svgWidth . '" height="' . $svgHeight . '" fill="url(#fallbackBg)" />
                <text x="' . ($svgWidth * 0.08) . '" y="' . ($svgHeight * 0.18) . '" fill="#ffffff" font-size="' . ($svgHeight * 0.04) . '" font-weight="700" letter-spacing="5">INVITACIÓN ESPECIAL</text>
                <text x="' . ($svgWidth * 0.08) . '" y="' . ($svgHeight * 0.28) . '" fill="#ffffff" font-size="' . ($svgHeight * 0.07) . '" font-weight="800">' . $eventTitle . '</text>
            ';

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="{$svgWidth}" height="{$svgHeight}" viewBox="0 0 {$svgWidth} {$svgHeight}">
    {$backgroundLayer}
    <text x="{$nameX}" y="{$nameY}" text-anchor="middle" dominant-baseline="middle" fill="#0f172a" font-size="{$nameFontSize}" font-weight="800" font-family="Arial, Helvetica, sans-serif" lengthAdjust="spacingAndGlyphs">{$guestName}</text>
    <image href="data:image/png;base64,{$qrBase64}" x="{$qrX}" y="{$qrY}" width="{$qrPixelSize}" height="{$qrPixelSize}" preserveAspectRatio="xMidYMid meet" />
    <text x="{$qrCenterX}" y="{$captionY}" text-anchor="middle" fill="#0f172a" font-size="24" font-weight="700" font-family="Arial, Helvetica, sans-serif">{$cuposLabel}</text>
</svg>
SVG;
    }

    private function renderInvitationPng(EventoInvitacion $invitation): string
    {
        $design = $this->invitationDesign() ?? [];
        [$canvasWidth, $canvasHeight] = $this->invitationCanvasDimensions($design['page_size'] ?? 'Carta', $design['orientation'] ?? 'Horizontal');
        $image = $this->makeInvitationCanvas($canvasWidth, $canvasHeight);

        $nameCenterX = (int) round($canvasWidth * ($this->invitationCoordinate('name', 'x') / 100));
        $nameCenterY = (int) round($canvasHeight * ($this->invitationCoordinate('name', 'y') / 100));
        $nameMaxWidth = (int) round($canvasWidth * 0.84);
        $this->drawCenteredSingleLineText(
            $image,
            trim((string) $invitation->nombre_invitado),
            $nameCenterX,
            $nameCenterY,
            $nameMaxWidth
        );

        $qrSizePercent = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $qrPixelSize = (int) round($canvasWidth * ($qrSizePercent / 100));
        $qrCenterX = (int) round($canvasWidth * ($this->invitationCoordinate('qr', 'x') / 100));
        $qrCenterY = (int) round($canvasHeight * ($this->invitationCoordinate('qr', 'y') / 100));
        $qrX = (int) round($qrCenterX - ($qrPixelSize / 2));
        $qrY = (int) round($qrCenterY - ($qrPixelSize / 2));

        $this->placeQrOnCanvas($image, $invitation->codigo, $qrX, $qrY, $qrPixelSize);

        $caption = 'Valido por ' . $invitation->cupos . ' ' . Str::plural('cupo', $invitation->cupos);
        $captionY = (int) min($canvasHeight - 18, round($qrY + $qrPixelSize + max(24, $canvasHeight * 0.022)));
        $this->drawCenteredCaption($image, $caption, $qrCenterX, $captionY, max($qrPixelSize, 260));

        ob_start();
        imagepng($image);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }

    private function invitationCanvasDimensions(?string $pageSize, ?string $orientation, ?int $targetCanvasWidth = null): array
    {
        $ratio = self::PAGE_SIZE_RATIOS[$pageSize ?: ''] ?? self::PAGE_SIZE_RATIOS['Carta'];
        [$width, $height] = $ratio;

        if (($orientation ?: 'Vertical') === 'Horizontal') {
            [$width, $height] = [$height, $width];
        }

        $canvasWidth = max(900, (int) ($targetCanvasWidth ?? 1600));
        $canvasHeight = (int) round(($height / $width) * $canvasWidth);

        return [$canvasWidth, $canvasHeight];
    }

    private function svgImageHrefFromInvitationTemplate(): ?string
    {
        $file = $this->invitationDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        $relativePath = public_path($file);

        if (! file_exists($relativePath)) {
            return null;
        }

        $contents = file_get_contents($relativePath);
        $mime = mime_content_type($relativePath) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }

    private function invitationNameSvgFontSize(EventoInvitacion $invitation): int
    {
        $name = trim((string) $invitation->nombre_invitado);
        $length = mb_strlen($name);
        $size = 76;

        if ($length > 18) {
            $size -= min(22, ($length - 18) * 2);
        }

        if ($length > 30) {
            $size -= min(14, ($length - 30));
        }

        return max(30, $size);
    }

    private function makeInvitationCanvas(int $canvasWidth, int $canvasHeight)
    {
        $image = imagecreatetruecolor($canvasWidth, $canvasHeight);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $backgroundPath = $this->invitationTemplateFilesystemPath();
        $background = $backgroundPath ? $this->loadRasterImage($backgroundPath) : null;

        if ($background) {
            imagecopyresampled(
                $image,
                $background,
                0,
                0,
                0,
                0,
                $canvasWidth,
                $canvasHeight,
                imagesx($background),
                imagesy($background)
            );
            imagedestroy($background);

            return $image;
        }

        $dark = imagecolorallocate($image, 15, 23, 42);
        $mid = imagecolorallocate($image, 49, 46, 129);
        $light = imagecolorallocate($image, 37, 99, 235);

        for ($y = 0; $y < $canvasHeight; $y++) {
            $ratio = $y / max(1, $canvasHeight - 1);
            $r = (int) round(15 + ((37 - 15) * $ratio));
            $g = (int) round(23 + ((99 - 23) * $ratio));
            $b = (int) round(42 + ((235 - 42) * $ratio));
            $line = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $canvasWidth, $y, $line);
        }

        imagefilledellipse($image, (int) ($canvasWidth * 0.82), (int) ($canvasHeight * 0.14), (int) ($canvasWidth * 0.55), (int) ($canvasHeight * 0.32), $mid);
        imagefilledellipse($image, (int) ($canvasWidth * 0.22), (int) ($canvasHeight * 0.84), (int) ($canvasWidth * 0.48), (int) ($canvasHeight * 0.24), $light);

        return $image;
    }

    private function invitationTemplateFilesystemPath(): ?string
    {
        $file = $this->invitationDesign()['file'] ?? null;

        if (! $file) {
            return null;
        }

        $path = public_path($file);

        return file_exists($path) ? $path : null;
    }

    private function invitationTemplateDataUri(): ?string
    {
        $path = $this->invitationTemplateFilesystemPath();

        if (! $path) {
            return null;
        }

        $mime = mime_content_type($path) ?: '';

        if (! Str::startsWith($mime, 'image/')) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($path));
    }

    private function invitationPdfOrientation(): string
    {
        $orientation = strtolower((string) (($this->invitationDesign()['orientation'] ?? 'Horizontal')));

        return $orientation === 'vertical' ? 'portrait' : 'landscape';
    }

    private function invitationPdfPaperDefinition(): string|array
    {
        $design = $this->invitationDesign() ?? [];
        $pageSize = $design['page_size'] ?? 'Carta';
        $orientation = $this->invitationPdfOrientation();

        if ($pageSize === 'Personalizado') {
            $dimensions = $this->invitationTemplatePointDimensions();

            if ($dimensions !== null) {
                [$width, $height] = $dimensions;

                if ($orientation === 'landscape' && $height > $width) {
                    [$width, $height] = [$height, $width];
                }

                if ($orientation === 'portrait' && $width > $height) {
                    [$width, $height] = [$height, $width];
                }

                return [0, 0, $width, $height];
            }
        }

        return match ($pageSize) {
            'Carta' => 'letter',
            'Oficio' => 'folio',
            'A4' => 'a4',
            'A3' => 'a3',
            'Legal' => 'legal',
            default => 'letter',
        };
    }

    private function invitationTemplatePointDimensions(): ?array
    {
        $path = $this->invitationTemplateFilesystemPath();

        if (! $path) {
            return null;
        }

        $size = @getimagesize($path);

        if (! $size || empty($size[0]) || empty($size[1])) {
            return null;
        }

        $widthPx = (float) $size[0];
        $heightPx = (float) $size[1];
        $pointsPerPixel = 72 / 96;

        return [
            round($widthPx * $pointsPerPixel, 2),
            round($heightPx * $pointsPerPixel, 2),
        ];
    }

    private function invitationPdfPageDimensions(string|array $paperDefinition, string $orientation): array
    {
        if (is_array($paperDefinition) && count($paperDefinition) === 4) {
            return [
                (float) $paperDefinition[2],
                (float) $paperDefinition[3],
            ];
        }

        $dimensions = match ($paperDefinition) {
            'letter' => [612.0, 792.0],
            'folio' => [612.0, 936.0],
            'a4' => [595.28, 841.89],
            'a3' => [841.89, 1190.55],
            'legal' => [612.0, 1008.0],
            default => [612.0, 792.0],
        };

        if ($orientation === 'landscape') {
            return [$dimensions[1], $dimensions[0]];
        }

        return $dimensions;
    }

    private function loadRasterImage(string $path)
    {
        $mime = mime_content_type($path) ?: '';

        return match ($mime) {
            'image/png' => @imagecreatefrompng($path),
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => @imagecreatefromstring((string) file_get_contents($path)),
        };
    }

    private function drawCenteredSingleLineText($image, string $text, int $centerX, int $centerY, int $maxWidth, ?int $maxFontSize = null, int $minFontSize = 26): void
    {
        $fontPath = $this->invitationFontPath();
        $text = trim($text) !== '' ? trim($text) : 'Invitado';
        $color = imagecolorallocate($image, 15, 23, 42);

        if (! $fontPath) {
            $font = 5;
            $width = imagefontwidth($font) * strlen($text);
            $height = imagefontheight($font);
            imagestring($image, $font, (int) round($centerX - ($width / 2)), (int) round($centerY - ($height / 2)), $text, $color);

            return;
        }

        $fontSize = $this->fittedFontSize($text, $fontPath, $maxWidth, $maxFontSize ?? 96, $minFontSize);
        $box = imagettfbbox($fontSize, 0, $fontPath, $text);
        $minX = min($box[0], $box[2], $box[4], $box[6]);
        $maxX = max($box[0], $box[2], $box[4], $box[6]);
        $minY = min($box[1], $box[3], $box[5], $box[7]);
        $maxY = max($box[1], $box[3], $box[5], $box[7]);
        $textWidth = (int) round($maxX - $minX);
        $textHeight = (int) round($maxY - $minY);
        $x = (int) round($centerX - ($textWidth / 2) - $minX);
        $y = (int) round($centerY + ($textHeight / 2) - $maxY);

        imagettftext($image, $fontSize, 0, $x, $y, $color, $fontPath, $text);
    }

    private function drawCenteredCaption($image, string $text, int $centerX, int $baselineY, int $maxWidth): void
    {
        $fontPath = $this->invitationFontPath();
        $color = imagecolorallocate($image, 15, 23, 42);

        if (! $fontPath) {
            $font = 3;
            $width = imagefontwidth($font) * strlen($text);
            imagestring($image, $font, (int) round($centerX - ($width / 2)), $baselineY, $text, $color);

            return;
        }

        $fontSize = $this->fittedFontSize($text, $fontPath, $maxWidth, 28, 14);
        $box = imagettfbbox($fontSize, 0, $fontPath, $text);
        $minX = min($box[0], $box[2], $box[4], $box[6]);
        $maxX = max($box[0], $box[2], $box[4], $box[6]);
        $textWidth = (int) round($maxX - $minX);
        imagettftext($image, $fontSize, 0, (int) round($centerX - ($textWidth / 2) - $minX), $baselineY, $color, $fontPath, $text);
    }

    private function placeQrOnCanvas($image, string $code, int $x, int $y, int $size): void
    {
        $qrBase64 = QRCodeService::generateTextQRCode($code, max(280, $size * 3));
        $qrImage = @imagecreatefromstring(base64_decode($qrBase64));

        if (! $qrImage) {
            return;
        }

        imagecopyresampled(
            $image,
            $qrImage,
            $x,
            $y,
            0,
            0,
            $size,
            $size,
            imagesx($qrImage),
            imagesy($qrImage)
        );

        imagedestroy($qrImage);
    }

    private function fittedFontSize(string $text, string $fontPath, int $maxWidth, int $start, int $min): int
    {
        for ($size = $start; $size >= $min; $size--) {
            $box = imagettfbbox($size, 0, $fontPath, $text);
            $width = (int) abs($box[2] - $box[0]);

            if ($width <= $maxWidth) {
                return $size;
            }
        }

        return $min;
    }

    private function invitationFontPath(): ?string
    {
        $candidates = [
            '/System/Library/Fonts/Supplemental/Arial Bold.ttf',
            '/Library/Fonts/Arial Unicode.ttf',
            public_path('fonts/GreatVibes-Regular.ttf'),
        ];

        foreach ($candidates as $path) {
            if ($path && file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    private function invitationPdfNameStyle(EventoInvitacion $invitation): string
    {
        $fontSize = $this->invitationNamePdfFontSize($invitation);

        return sprintf(
            'position:absolute; left:%1$.4f%%; top:%2$.4f%%; width:84%%; transform:translate(-50%%,-50%%); text-align:center; white-space:nowrap; font-size:%3$dpx; line-height:1; font-weight:800; color:#0f172a;',
            $this->invitationCoordinate('name', 'x'),
            $this->invitationCoordinate('name', 'y'),
            $fontSize
        );
    }

    private function invitationPdfQrStyle(): string
    {
        $design = $this->invitationDesign() ?? [];
        $qrSize = max(12, min(28, (int) ($design['qr_size'] ?? 18)));

        return sprintf(
            'position:absolute; left:%1$.4f%%; top:%2$.4f%%; width:%3$d%%; aspect-ratio:1/1; transform:translate(-50%%,-50%%);',
            $this->invitationCoordinate('qr', 'x'),
            $this->invitationCoordinate('qr', 'y'),
            $qrSize
        );
    }

    private function invitationPdfCaptionStyle(): string
    {
        $design = $this->invitationDesign() ?? [];
        $y = $this->invitationCoordinate('qr', 'y');
        $qrSize = max(12, min(28, (int) ($design['qr_size'] ?? 18)));
        $captionTop = min(97, $y + ($qrSize / 2) + 4.2);

        return sprintf(
            'position:absolute; left:%1$.4f%%; top:%2$.4f%%; width:26%%; transform:translate(-50%%,0); text-align:center; font-size:10px; line-height:1.2; font-weight:700; color:#0f172a;',
            $this->invitationCoordinate('qr', 'x'),
            $captionTop
        );
    }

    private function invitationNamePdfFontSize(EventoInvitacion $invitation): int
    {
        $length = mb_strlen(trim((string) $invitation->nombre_invitado));
        $size = 28;

        if ($length > 18) {
            $size -= min(8, (int) floor(($length - 18) * 0.5));
        }

        if ($length > 32) {
            $size -= min(5, (int) floor(($length - 32) * 0.35));
        }

        return max(12, $size);
    }
}
