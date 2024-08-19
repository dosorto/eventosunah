<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Asistencia;
use App\Services\QRCodeService;


class VistaDiplomas extends Component
{
    public $qrcode;
    public $persona;
    public $asistencia;
    public $conferencia;

    public $evento;
    
    public $diploma;

    public function mount(Asistencia $asistencia)
    {
        $this->asistencia = $asistencia;
        $this->persona = $asistencia->suscripcion->persona;
        $this->evento = $asistencia->suscripcion->conferencia->evento;
        $this->conferencia = $asistencia->suscripcion->conferencia;
        $this->diploma = $asistencia->suscripcion->conferencia->evento->diploma;

       
        // obtener el diploma asociado a la persona y la conferencia

        if ($this->validacionesPersonaConferencia()) {
            $this->qrcode = QRCodeService::generateTextQRCode(
                config('app.url') . '/validarDiploma/' . $asistencia->diplomaGenerado->uuid
            );
        } else {
            return redirect()->route('conferencias-inscritas');
        }
    }

    // validar que la persona este inscrita en la conferencia
    // y ademas que la persona haya asistido a la conferencia
    public function validacionesPersonaConferencia()
    {
        return true;
    }

    public function render()
    {
        return view('livewire.vista-diplomas');
    }
}