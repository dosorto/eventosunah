<?php

namespace App\Livewire\Gafete;
use App\Services\QRCodeService;
use App\Models\Evento;
use Livewire\Component;

class Gafetes extends Component
{
    public $evento;

    public function mount(Evento $evento)
    {
        $this->evento = $evento;
    }

    public function render()
    {
        // Generar el código QR
        $qrcode = QRCodeService::generateTextQRCode(
            config('app.url') . '/reporteqr/' . $this->evento->id
        );
        return view('livewire.gafete', [
            'qrcode' => $qrcode,
        ]);
    }
}
