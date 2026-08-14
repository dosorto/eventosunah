<?php

namespace App\Livewire\Participante;

use App\Models\EventoRegistro;
use App\Services\ParticipantBadgeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MiHistorial extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(ParticipantBadgeService $badgeService)
    {
        $personaId = Auth::user()?->persona?->id;

        $registros = EventoRegistro::query()
            ->with(['evento.modalidad', 'tipoPerfil'])
            ->when($personaId, fn ($query) => $query->where('persona_id', $personaId), fn ($query) => $query->whereRaw('1 = 0'))
            ->when($this->search !== '', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery
                        ->whereHas('evento', function ($eventoQuery) {
                            $eventoQuery
                                ->where('nombreevento', 'like', '%' . $this->search . '%')
                                ->orWhere('organizador', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('tipoPerfil', function ($tipoPerfilQuery) {
                            $tipoPerfilQuery->where('tipoperfil', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.participante.mi-historial', [
            'registros' => $registros,
            'badgeService' => $badgeService,
        ])->layout('components.layouts.app');
    }
}
