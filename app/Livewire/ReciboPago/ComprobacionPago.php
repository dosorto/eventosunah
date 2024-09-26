<?php

namespace App\Livewire\ReciboPago;

use App\Models\Inscripcion;
use Livewire\Component;
use Livewire\WithPagination;
use App\Notifications\ComprobanteRechazado;
use Illuminate\Support\Facades\Notification;

class ComprobacionPago extends Component
{
    use WithPagination;

    public $search = '';
    public $evento_id;
    public $modalOpen = false;
    public $modalMessage = '';

    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;

    protected $paginationTheme = 'tailwind';

    public function mount($evento = null)
    {
        if ($evento) {
            $this->evento_id = $evento;
        } else {
            session()->flash('error', 'Evento no encontrado.');
            return redirect()->route('eventos.index');
        }
    }

    public function marcarComprobado($inscripcionId)
    {
        // Actualizar el estado de la inscripción
        Inscripcion::where('id', $inscripcionId)->update(['Status' => 'Aceptado']);

        // Mensaje de éxito
        $this->modalMessage = 'Comprobación hecha correctamente.';
        $this->modalOpen = true;
    }

    /* public function rechazarComprobacion($inscripcionId)
     {
         Inscripcion::where('id', $inscripcionId)->update(['Status' => 'Rechazado']);
         $this->modalMessage = 'Comprobación rechazada correctamente.';
         $this->modalOpen = true;
     }
 */
    public function marcarTodos($status)
    {
        $inscripciones = Inscripcion::where('IdEvento', $this->evento_id)->get();

        if ($inscripciones->isEmpty()) {
            $this->modalMessage = 'No hay inscripciones para comprobar.';
            $this->modalOpen = true;
            return;
        }

        foreach ($inscripciones as $inscripcion) {
            Inscripcion::where('id', $inscripcion->id)->update(['Status' => $status]);
        }

        $this->modalMessage = 'Todas las comprobaciones fueron marcadas como ' . $status . '.';
        $this->modalOpen = true;
    }


    public function render()
    {
        $inscripciones = Inscripcion::with(['persona', 'evento', 'recibo'])
            ->where('IdEvento', $this->evento_id)
            ->whereHas('persona', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%');
            })
            ->paginate(8);

        return view('livewire.ReciboPagos.comprobacionPago', [
            'inscripciones' => $inscripciones,
            'evento_id' => $this->evento_id,

        ]);
    }

    public function delete()
    {
        if ($this->confirmingDelete) {
            $inscripcion = Inscripcion::find($this->IdAEliminar);

            if (!$inscripcion) {
                session()->flash('error', 'Incripción no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            // Enviar notificación por correo electrónico
            Notification::route('mail', $inscripcion->persona->correo)
                ->notify(new ComprobanteRechazado($inscripcion));


            $inscripcion->delete();
            session()->flash('message', 'Inscripción rechazada! Se ha enviado un correo electrónico a ' . $inscripcion->persona->nombre .' '. $inscripcion->persona->apellido .' ('. $inscripcion->persona->correo . ') notificandole.');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $comprobacion = Inscripcion::find($id);

        if (!$comprobacion) {
            session()->flash('error', 'Incripción no encontrada.');
            $this->confirmingDelete = true;
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $comprobacion->persona->nombre . ' ' . $comprobacion->persona->apellido;
        $this->confirmingDelete = true;
    }
}