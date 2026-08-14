<?php

namespace App\Livewire\Asistencia;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PortalAsistencia extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public string $search = '';

    protected string $paginationTheme = 'tailwind';

    public function mount(): void
    {
        abort_unless($this->canUseAttendancePortal(), 403);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openEvent(int $eventoId): void
    {
        $evento = $this->eventsQuery()->findOrFail($eventoId);

        $this->redirectRoute('asistencia.evento', ['evento' => $evento->id], navigate: true);
    }

    public function render()
    {
        $eventos = $this->eventsQuery()->paginate(10);

        return view('livewire.asistencia.portal-asistencia', [
            'eventos' => $eventos,
        ])->layout('components.layouts.app');
    }

    private function canUseAttendancePortal(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->can('events.manage')
            || $user->can('attendances.manage')
            || $user->can('staff.attendance.scan');
    }

    private function eventsQuery(): Builder
    {
        $user = Auth::user();
        $personaId = $user?->persona?->id;

        return Evento::query()
            ->with(['tipoEvento', 'modalidad'])
            ->withCount('conferencias')
            ->whereHas('conferencias')
            ->when(
                $user && ! $user->can('events.manage') && ! $user->can('attendances.manage'),
                fn (Builder $query) => $query->whereHas('staffMembers', function (Builder $staffQuery) use ($personaId) {
                    $staffQuery
                        ->where('persona_id', $personaId)
                        ->where('activo', true);
                })
            )
            ->when($this->search !== '', function (Builder $query) {
                $search = trim($this->search);

                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery
                        ->where('nombreevento', 'like', '%' . $search . '%')
                        ->orWhere('organizador', 'like', '%' . $search . '%')
                        ->orWhere('localidad_nombre', 'like', '%' . $search . '%')
                        ->orWhereHas('tipoEvento', fn (Builder $tipoQuery) => $tipoQuery->where('tipo', 'like', '%' . $search . '%'));
                });
            })
            ->orderBy('fechainicio')
            ->orderBy('nombreevento');
    }
}
