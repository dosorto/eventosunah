<?php

namespace App\Livewire\VistaConferencia;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Conferencia;
use App\Models\Conferencista;
use App\Models\Evento;
use Illuminate\Support\Facades\Auth;

class VistaConferencias extends Component
{
    use WithPagination;

    public $evento;
    public $conferencias;
    public $showConfirmModal = false;
    public $selectedConferencia;


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
        $this->showConfirmModal = true;
    }

    public $SuscripciónYaRealizada = false;

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
}



    public function cancel()
    {
        $this->showConfirmModal = false;
        $this->selectedConferencia = null;
    }
}
