<?php

namespace App\Livewire\Participante;

use App\Models\Evento;
use App\Models\EventoRegistro;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class EventosDisponibles extends Component
{
    use WithPagination;

    public string $search = '';

    protected string $paginationTheme = 'tailwind';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $personaId = Auth::user()?->persona?->id;

        $registeredEventIds = $personaId
            ? EventoRegistro::query()
                ->where('persona_id', $personaId)
                ->pluck('evento_id')
                ->all()
            : [];

        $eventos = Evento::query()
            ->with(['modalidad', 'tipoEvento'])
            ->published()
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombreevento', 'like', '%' . $search . '%')
                        ->orWhere('descripcion', 'like', '%' . $search . '%')
                        ->orWhere('organizador', 'like', '%' . $search . '%')
                        ->orWhere('localidad_nombre', 'like', '%' . $search . '%')
                        ->orWhereHas('localidad', function ($localidadQuery) use ($search) {
                            $localidadQuery->where('localidad', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('tipoEvento', function ($tipoQuery) use ($search) {
                            $tipoQuery->where('tipo', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderByRaw('CASE WHEN fechainicio >= CURDATE() THEN 0 ELSE 1 END')
            ->orderBy('fechainicio')
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('livewire.participante.eventos-disponibles', [
            'eventos' => $eventos,
            'registeredEventIds' => $registeredEventIds,
        ])->layout('components.layouts.app');
    }
}
