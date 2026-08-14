<?php

namespace App\Livewire\Participante;

use App\Models\EventoRegistro;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MisPagos extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $personaId = Auth::user()?->persona?->id;

        $pagos = EventoRegistro::query()
            ->with(['evento.precios.moneda', 'metodoPago', 'tipoPerfil'])
            ->when($personaId, fn ($query) => $query->where('persona_id', $personaId), fn ($query) => $query->whereRaw('1 = 0'))
            ->where(function ($query) {
                $query
                    ->whereNotNull('metodo_pago_id')
                    ->orWhere('precio_aplicado', '>', 0);
            })
            ->when($this->search !== '', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery
                        ->whereHas('evento', function ($eventoQuery) {
                            $eventoQuery->where('nombreevento', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('referencia_pago', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.participante.mis-pagos', [
            'pagos' => $pagos,
        ])->layout('components.layouts.app');
    }
}
