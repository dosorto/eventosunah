<?php

namespace App\Livewire\VistaConferencia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conferencia;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class VistasConferencias extends Component
{
    use WithPagination;

    public $evento;
    public $conferencias;
    public $showConfirmModal = false;
    public $selectedConferencia;

    public function mount(Evento $evento)
    {
        $this->evento = $evento;
        $this->conferencias = $evento->conferencias;
    }

    public function render()
    {
        return view('livewire.VistaConferencia.vista-conferencias');
    }

    public function confirmInscription($conferenciaId)
    {
        $this->selectedConferencia = $conferenciaId;
        $conferencia = Conferencia::find($conferenciaId);

        // Verificar si el evento es pagado
        if ($conferencia && $conferencia->estado === 'Pagado') {
            // Verificar la suscripción del usuario
            $suscripcion = Auth::user()->persona->suscripciones()
                ->where('IdConferencia', $conferencia->id)
                ->first();

            // Redirigir a la vista si no está inscrito o su estado es Pendiente o Rechazado
            if (!$suscripcion || in_array($suscripcion->status, ['Pendiente', 'Rechazado'])) {
                return view('livewire.VistaConferencia.vista-conferencias');
            }

            // Aquí puedes agregar cualquier otra lógica si es necesario
        } else {
            // Si no es pagado, redirigir a la vista de conferencias
            return view('livewire.VistaConferencia.vista-conferencias');
        }
    }
}
