<?php
namespace App\Livewire;

use App\Models\Asistencia;
use Livewire\Component;

class ValidarDiploma extends Component
{
    public $persona;
    public $conferencia;
    public $codigoDiploma;
    public $id;

    public $asistencia;
    public function mount($id)
    {
        $this->id = $id;
        $asistencia = Asistencia::find($this->id);

        if ($asistencia) {
            $this->persona = $asistencia->suscripcion->persona;
            $this->conferencia = $asistencia->suscripcion->conferencia;
            $this->codigoDiploma = $asistencia->suscripcion->conferencia->evento->diploma;
            $this->asistencia = $asistencia;
        }
    }

    public function render()
    {
        return view('livewire.validar-diploma', [
            'persona' => $this->persona,
            'conferencia' => $this->conferencia,
            'codigoDiploma' => $this->codigoDiploma,
        ]);
    }
}