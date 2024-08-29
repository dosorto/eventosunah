<?php

namespace App\Livewire\VistaConferencia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conferencia;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class VistaConferencias extends Component
{
    use WithPagination;

    public $evento;
    public $conferencias;
    public $showConfirmModal = false;
    public $selectedConferencia;
    public $showPaymentModal = false; 
    public $SuscripciónYaRealizada = false;

    public function mount(Evento $evento)
    {
        $this->evento = $evento;
        $this->conferencias =  $evento->conferencias;
    }

    public function render()
    {
        Auth::user()->persona->suscripciones;
        return view('livewire.VistaConferencia.vista-conferencia');
    }

    public function confirmInscription($conferenciaId)
    {
        $this->selectedConferencia = $conferenciaId;
        $conferencia = Conferencia::find($conferenciaId);

        if ($conferencia && $conferencia->estado === 'Pagado') {
            $this->showPaymentModal = true;
        } else {
            $this->showConfirmModal = true;
        }
    }

    public function inscribirse()
    {
        $conferencia = Conferencia::find($this->selectedConferencia);

        if ($conferencia) {
            // Verificar si ya está suscrito a la conferencia
            $suscripcionExistente = Auth::user()->persona->suscripciones()
                ->where('IdConferencia', $conferencia->id)
                ->exists();

            if ($suscripcionExistente) {
                $this->SuscripciónYaRealizada = true; 
            } else {
                // Crear la suscripción si no existe
                Auth::user()->persona->suscripciones()->updateOrCreate([
                    'IdConferencia' => $conferencia->id,
                    'created_by' => Auth::id()
                ]);

                session()->flash('success', 'Te has inscrito a la conferencia.');
            }
        }

        $this->showConfirmModal = false;
        $this->selectedConferencia = null;
        $this->showPaymentModal = false; // Asegúrate de cerrar el modal de pago si estaba abierto
    }

    public function cancel()
    {
        $this->showConfirmModal = false;
        $this->showPaymentModal = false; // Asegúrate de cerrar el modal de pago si estaba abierto
        $this->selectedConferencia = null;
    }

    public function redirectToPayment()
    {
        // Redirige a la página de pago
        return redirect()->route('pago.conferencia', ['conferencia' => $this->selectedConferencia]);
    }
}
