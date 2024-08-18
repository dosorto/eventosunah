<?php

namespace App\Livewire\Asistencia;

use App\Models\Asistencia;
use Livewire\Component;
use Livewire\WithPagination;

class AsistenciasConferencias extends Component
{
    use WithPagination;

    public $search = '';
    public $conferencia_id; // Para filtrar por conferencia

    public function mount($conferencia = null)
    {
        $this->conferencia_id = $conferencia;
    }

    public function render()
    {
        $asistencias = Asistencia::with('suscripcion')
            ->when($this->conferencia_id, function ($query) {
                $query->whereHas('suscripcion', function ($query) {
                    $query->where('IdConferencia', $this->conferencia_id);
                });
            })
            ->where(function ($query) {
                if ($this->search) {
                    $query->whereHas('suscripcion', function ($query) {
                        $query->where('persona.nombre', 'like', '%' . $this->search . '%')
                              ->orWhere('persona.apellido', 'like', '%' . $this->search . '%')
                              ->orWhere('conferencia.nombre', 'like', '%' . $this->search . '%');
                    });
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate(8);

        return view('livewire.asistencia.asistencias-Conferencia', [
            'asistencias' => $asistencias,
        ]);
    }
}
