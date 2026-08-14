<?php

namespace App\Livewire\Asistencia;

use App\Models\Conferencia;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventoAsistencia extends Component
{
    use AuthorizesRequests;

    public Evento $evento;
    public string $search = '';

    public function mount(Evento $evento): void
    {
        abort_unless($this->canAccessEvent($evento), 403);

        $this->evento = $evento->load(['tipoEvento', 'modalidad']);
    }

    public function openConference(int $conferenciaId): void
    {
        $conference = $this->conferenceCollection()->firstWhere('id', $conferenciaId);

        abort_unless($conference, 404);

        $this->redirectRoute('asistencia.conferencia', ['conferencia' => $conference->id], navigate: true);
    }

    public function render()
    {
        $conferencias = $this->conferenceCollection()
            ->groupBy(function (Conferencia $conference) {
                if (! $conference->fecha) {
                    return 'sin-fecha';
                }

                return Carbon::parse($conference->fecha)->format('Y-m-d');
            });

        return view('livewire.asistencia.evento-asistencia', [
            'conferenciasPorDia' => $conferencias,
            'totalConferencias' => $conferencias->flatten()->count(),
        ])->layout('components.layouts.app');
    }

    private function canAccessEvent(Evento $evento): bool
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

        return $evento->staffMembers()
            ->where('persona_id', $personaId)
            ->where('activo', true)
            ->exists();
    }

    private function conferenceCollection(): Collection
    {
        return $this->evento->conferencias()
            ->with(['tipoConferencia', 'speakerPersona'])
            ->withCount('registroAsistencias')
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre', 'like', '%' . $search . '%')
                        ->orWhere('descripcion', 'like', '%' . $search . '%')
                        ->orWhere('lugar', 'like', '%' . $search . '%')
                        ->orWhereHas('tipoConferencia', fn ($tipoQuery) => $tipoQuery->where('tipo', 'like', '%' . $search . '%'))
                        ->orWhereHas('speakerPersona', function ($speakerQuery) use ($search) {
                            $speakerQuery->searchName($search);
                        });
                });
            })
            ->orderBy('fecha')
            ->orderBy('horaInicio')
            ->get();
    }
}
