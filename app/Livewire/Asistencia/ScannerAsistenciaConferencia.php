<?php

namespace App\Livewire\Asistencia;

use App\Models\Conferencia;
use App\Models\ConferenciaRegistroAsistencia;
use App\Models\EventoRegistro;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ScannerAsistenciaConferencia extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public Conferencia $conferencia;
    public string $manualCode = '';
    public string $search = '';
    public string $feedbackType = 'info';
    public string $feedbackMessage = '';
    public array $lastAttendance = [];
    public ?int $pendingRegistrationId = null;
    public array $pendingParticipant = [];

    protected string $paginationTheme = 'tailwind';

    public function mount(Conferencia $conferencia): void
    {
        $conferencia->load(['evento.tipoEvento', 'evento.modalidad', 'speakerPersona']);

        abort_unless($this->canAccessConference($conferencia), 403);

        $this->conferencia = $conferencia;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function submitManualCode(): void
    {
        $this->processQrCode($this->manualCode);
    }

    public function processQrCode(string $code): void
    {
        abort_unless($this->canAccessConference($this->conferencia), 403);

        $payload = strtoupper(trim($code));
        $this->manualCode = '';
        $this->pendingRegistrationId = null;
        $this->pendingParticipant = [];

        if ($payload === '') {
            $this->setFeedback('error', 'Escanea o escribe un código QR válido.');

            return;
        }

        if (! preg_match('/^REG-(\d+)-(\d+)$/', $payload, $matches)) {
            if (str_starts_with($payload, 'STAFF-')) {
                $this->setFeedback('warning', 'Ese código corresponde a un gafete de staff. Debes escanear el gafete del participante.');

                return;
            }

            $this->setFeedback('error', 'El código leído no corresponde a un gafete de participante válido.');

            return;
        }

        $eventId = (int) $matches[1];
        $registrationId = (int) $matches[2];

        if ($eventId !== (int) $this->conferencia->IdEvento) {
            $this->setFeedback('error', 'El participante pertenece a otro evento.');

            return;
        }

        $registration = EventoRegistro::query()
            ->with(['persona.tipoPerfil'])
            ->where('evento_id', $this->conferencia->IdEvento)
            ->find($registrationId);

        if (! $registration || ! $registration->persona) {
            $this->setFeedback('error', 'No se encontró un participante válido para este evento.');

            return;
        }

        $attendance = ConferenciaRegistroAsistencia::query()->firstOrNew([
            'conferencia_id' => $this->conferencia->id,
            'evento_registro_id' => $registration->id,
        ]);

        $alreadyRegistered = $attendance->exists;
        $fullName = trim(($registration->persona->nombre ?? '') . ' ' . ($registration->persona->apellido ?? ''));

        $participantSnapshot = [
            'nombre' => $fullName !== '' ? $fullName : 'Participante',
            'dni' => $registration->persona->dni ?: 'Sin identidad',
            'telefono' => $registration->persona->telefono ?: 'Sin teléfono',
            'perfil' => $registration->tipoPerfil?->tipoperfil ?: 'Sin perfil',
            'correo' => $registration->persona->correo ?: 'Sin correo',
            'hora' => ($attendance->checked_in_at ?? $attendance->created_at)?->format('d/m/Y h:i a'),
            'ya_registrado' => $alreadyRegistered,
        ];

        if ($alreadyRegistered) {
            $this->lastAttendance = $participantSnapshot;
            $this->setFeedback('warning', 'Este participante ya tenía asistencia registrada en esta conferencia.');
            $this->dispatch('attendance-scan-resolved');

            return;
        }

        $this->pendingRegistrationId = $registration->id;
        $this->pendingParticipant = $participantSnapshot;
        $this->setFeedback('info', 'Participante identificado. Revisa los datos y confirma el registro de asistencia.');
        $this->dispatch('attendance-scan-resolved');
    }

    public function confirmAttendance(): void
    {
        abort_unless($this->canAccessConference($this->conferencia), 403);

        if (! $this->pendingRegistrationId) {
            $this->setFeedback('error', 'Primero debes escanear un participante válido.');

            return;
        }

        $registration = EventoRegistro::query()
            ->with(['persona.tipoPerfil'])
            ->where('evento_id', $this->conferencia->IdEvento)
            ->find($this->pendingRegistrationId);

        if (! $registration || ! $registration->persona) {
            $this->pendingRegistrationId = null;
            $this->pendingParticipant = [];
            $this->setFeedback('error', 'El participante escaneado ya no está disponible para este evento.');

            return;
        }

        $attendance = ConferenciaRegistroAsistencia::query()->firstOrNew([
            'conferencia_id' => $this->conferencia->id,
            'evento_registro_id' => $registration->id,
        ]);

        $alreadyRegistered = $attendance->exists;

        if (! $alreadyRegistered) {
            $attendance->checked_in_at = now();
            $attendance->save();
        }

        $this->lastAttendance = [
            ...$this->pendingParticipant,
            'hora' => ($alreadyRegistered ? ($attendance->checked_in_at ?? $attendance->created_at) : now())?->format('d/m/Y h:i a'),
            'ya_registrado' => $alreadyRegistered,
        ];

        $participantName = $this->lastAttendance['nombre'] ?? 'Participante';
        $this->pendingRegistrationId = null;
        $this->pendingParticipant = [];

        $this->setFeedback(
            $alreadyRegistered ? 'warning' : 'success',
            $alreadyRegistered
                ? 'Este participante ya tenía asistencia registrada en esta conferencia.'
                : 'Asistencia registrada correctamente para ' . $participantName . '.'
        );

        $this->resetPage();
        $this->dispatch('attendance-confirmed');
    }

    public function clearPendingParticipant(): void
    {
        $this->pendingRegistrationId = null;
        $this->pendingParticipant = [];
    }

    public function render()
    {
        $attendances = $this->attendancesQuery()->paginate(12);

        return view('livewire.asistencia.scanner-asistencia-conferencia', [
            'attendances' => $attendances,
            'attendanceCount' => $this->attendancesQuery()->count(),
        ])->layout('components.layouts.app');
    }

    private function canAccessConference(Conferencia $conferencia): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->can('events.manage') || $user->can('attendances.manage')) {
            return true;
        }

        $personaId = $user->persona?->id;

        if (! $personaId || ! $user->can('staff.attendance.scan')) {
            return false;
        }

        return $conferencia->evento
            ->staffMembers()
            ->where('persona_id', $personaId)
            ->where('activo', true)
            ->exists();
    }

    private function attendancesQuery(): Builder
    {
        return ConferenciaRegistroAsistencia::query()
            ->with(['eventoRegistro.persona.tipoPerfil'])
            ->where('conferencia_id', $this->conferencia->id)
            ->when($this->search !== '', function (Builder $query) {
                $search = trim($this->search);

                $query->whereHas('eventoRegistro.persona', function (Builder $personQuery) use ($search) {
                    $personQuery
                        ->searchName($search)
                        ->orWhere('dni', 'like', '%' . $search . '%')
                        ->orWhere('telefono', 'like', '%' . $search . '%')
                        ->orWhere('correo', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('checked_in_at')
            ->orderByDesc('id');
    }

    private function setFeedback(string $type, string $message): void
    {
        $this->feedbackType = $type;
        $this->feedbackMessage = $message;
    }
}
