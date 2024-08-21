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
    public $asistencia;
    public $uuid;
    public function mount($uuid)
{
    $this->uuid = $uuid;
    $diploma = DiplomaGenerado::where('uuid', $this->uuid)->first();

    if ($diploma) {
        $asistencia = $diploma->asistencias->first(); // Acceder al primer elemento de la colección

        if ($asistencia) {
            $this->persona = $asistencia->suscripcion->persona;
            $this->conferencia = $asistencia->suscripcion->conferencia;
            $this->codigoDiploma = $asistencia->suscripcion->conferencia->evento->diploma;
            $this->asistencia = $diploma;
        }
    }
}

    public function render()
    {
        return view('livewire.validar-diploma', [
            'persona' => $this->persona,
            'conferencia' => $this->conferencia,
            'uuid' => $this->uuid,
            'asistencia' => $this->asistencia,
        ]);
    }
}
