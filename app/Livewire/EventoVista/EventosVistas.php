<?php

namespace App\Livewire\EventoVista;

use App\Models\Modalidad;
use App\Models\Localidad;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Evento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EventosVistas extends Component
{
    use WithPagination;

    public $nombreevento, $descripcion, $organizador, $idmodalidad, $idlocalidad, $evento_id, $search;
    public $isOpen = 0;
    public $showConfirmModal = false;
    public $selectedConferencia;
    public $showPaymentModal = false; 
    public $SuscripciónYaRealizada = false;
    public function render()
    {
        $Eventos = Evento::with('modalidad', 'localidad')
            ->where('nombreevento', 'like', '%' . $this->search . '%')
            ->where('fechaFinal', '>=', Carbon::today())
            ->orderBy('id', 'DESC')
            ->paginate(9);

        return view('livewire.EventoVista.eventos-vista', ['Eventos' => $Eventos]);
    }

    public $modalidades, $localidades;

    public function mount()
    {
        $this->modalidades = Modalidad::all();
        $this->localidades = Localidad::all();
    }
    public function confirmInscription($EventoId)
    {
        $this->selectedEvento= $EventoId;
        $evento = Evento::find($EventoId);

        if ($evento  && $evento ->estado === 'Pagado') {
            $this->showPaymentModal = true;
        } else {
            $this->showConfirmModal = true;
        }
    }

    public function inscribirse()
    {
        $evento = Evento::find($this->selectedEvento);

        if ($evento) {
            // Verificar si ya está suscrito a la conferencia
            $suscripcionExistente = Auth::user()->persona->inscripciones()
                ->where('IdEvento', $evento->id)
                ->exists();

            if ($suscripcionExistente) {
                $this->SuscripciónYaRealizada = true; 
            } else {
                // Crear la suscripción si no existe
                Auth::user()->persona->inscripciones()->updateOrCreate([
                    'IdEvento' => $evento->id,
                    'created_by' => Auth::id()
                ]);

                session()->flash('success', 'Te has inscrito al evento.');
            }
        }

        $this->showConfirmModal = false;
        $this->selectedConferencia = null;
        // Asegúrate de cerrar el modal de pago si estaba abierto
    }

    public function cancel()
    {
        $this->showConfirmModal = false;
        // Asegúrate de cerrar el modal de pago si estaba abierto
        $this->selectedConferencia = null;
    }
}

