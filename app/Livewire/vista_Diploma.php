<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conferencia;
use App\Models\Persona;
use App\Services\QRCodeService;

class vista_Diploma extends Component
{
    public $qrcode;
    public $persona;
    public $conferencia;

    public function mount($idConferencia, $idPersona)
    {
        // Obtener la conferencia y la persona usando los IDs proporcionados
        $this->conferencia = Conferencia::find($idConferencia);
        $this->persona = Persona::find($idPersona);

        // Validar si la conferencia y la persona existen
        if ($this->conferencia && $this->persona) {
            // Generar el código QR
            $this->qrcode = QRCodeService::generateTextQRCode(
                config('app.url') . '/validarDiploma/' . $idConferencia . '/' . $idPersona
            );
        } else {
            // Redireccionar a la vista de error si la conferencia o persona no existen
            return redirect()->route('conferencias-inscritas')->with('error', 'Conferencia o persona no encontrada.');
        }
    }

    public function render()
    {
        return view('livewire.vista-Diploma');
    }
}
