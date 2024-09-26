<?php

namespace App\Http\Controllers;
use App\Models\Asistencia;
use App\Models\DiplomaGenerado;

class ValidarDiplomaController extends Controller
{
    public $persona;
    public $conferencia;
    public $codigoDiploma;
    public $asistencia;
    public $uuid;

   
    public function validarDiploma($uuid)
    {
        $this->uuid = $uuid;
        $diploma = DiplomaGenerado::where('uuid', $this->uuid)->first();
        if ($diploma) {
            $asistencia = Asistencia::where('id', $diploma->id)->first(); // Acceder al primer elemento de la colección
            if ($asistencia) {
                $this->persona = $asistencia->suscripcion->persona;
                $this->conferencia = $asistencia->suscripcion->conferencia;
                $this->codigoDiploma = $asistencia->suscripcion->conferencia->evento->diploma;
                $this->asistencia = $diploma;

                return view('livewire.validar-diploma', [
                    'persona' => $this->persona,
                    'conferencia' => $this->conferencia,
                    'uuid' => $this->uuid,
                    'asistencia' => $this->asistencia,
                ]);
            }
        }
        return redirect()->back()->with('error', 'Diploma no encontrado.');
    }
}