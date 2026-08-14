<?php

namespace App\Livewire\Pago;

use App\Models\Evento;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PortalPagos extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = 'todos';

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('events.manage'), 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $events = Evento::query()
            ->withCount([
                'registros',
                'registros as pagos_pendientes_count' => fn ($query) => $query->whereIn('estado_pago', ['pendiente_confirmacion', 'pendiente_pasarela', 'pendiente_efectivo']),
                'registros as pagos_aprobados_count' => fn ($query) => $query->whereIn('estado_pago', ['pagado', 'no_aplica']),
            ])
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombreevento', 'like', '%' . $search . '%')
                        ->orWhere('organizador', 'like', '%' . $search . '%')
                        ->orWhere('localidad_nombre', 'like', '%' . $search . '%');
                });
            })
            ->when($this->statusFilter === 'publicados', fn ($query) => $query->where('estado', 'publicado'))
            ->when($this->statusFilter === 'pagados', fn ($query) => $query->where('tipo_acceso', 'pagada'))
            ->when($this->statusFilter === 'gratuitos', fn ($query) => $query->where('tipo_acceso', 'gratuita'))
            ->orderByDesc('published_at')
            ->orderByDesc('fechainicio')
            ->paginate(12);

        return view('livewire.pago.portal-pagos', [
            'events' => $events,
        ])->layout('components.layouts.app');
    }
}
