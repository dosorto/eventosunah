<?php
namespace App\Livewire;

use App\Models\Asistencia;
use Livewire\Component;

class ValidarDiploma extends Component
{
    public $persona;
    public $conferencia;

    public function mount($id)
    {
        $asistencia = Asistencia::find($id);

        if ($asistencia) {
            $this->persona = $asistencia->persona;
            $this->conferencia = $asistencia->conferencia;
        } else {
            // Redirigir a una página de error si no se encuentra el registro de asistencia
            return redirect()->route('conferencias-inscritas');
        }
    }

    public function render()
    {
        return view('livewire.validar-diploma', [
            'persona' => $this->persona,
            'conferencia' => $this->conferencia,
        ]);
    }
}
