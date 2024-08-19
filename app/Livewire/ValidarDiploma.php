<?php
namespace App\Livewire;

use App\Models\Asistencia;
use App\Models\DiplomaGenerado;
use Livewire\Component;

class ValidarDiploma extends Component
{
    public $persona;
    public $conferencia;
    public $codigoDiploma;
    public $id;
    public $asistencia;

    public $uuid;
    public function mount($id)
    {
        $this->id = $id;
        $diploma =DiplomaGenerado::where('IdAsistencia', $id)->first();
      


        if ($diploma) {
            $this->persona = $diploma->asistencias->suscripcion->persona;
            $this->conferencia = $diploma->asistencias->suscripcion->conferencia;
            $this->codigoDiploma = $diploma->asistencias->suscripcion->conferencia->evento->diploma;
            $this->asistencia = $diploma->asistencias;
            $this->uuid = $diploma->uuid;
        }
    }

    public function render()
    {
        return view('livewire.validar-diploma', [
            'persona' => $this->persona,
            'conferencia' => $this->conferencia,
            'uuid' => $this->uuid,
        ]);
    }
}
