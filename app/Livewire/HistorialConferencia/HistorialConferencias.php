<?php

namespace App\Livewire\HistorialConferencia;

use Livewire\Component;
use App\Models\Asistencia;
use Illuminate\Support\Facades\Auth;
use App\Models\DiplomaGenerado;
use Illuminate\Support\Str;
use App\Services\QRCodeService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

use Illuminate\Support\Facades\Storage;


class HistorialConferencias extends Component
{

    public $asistencia;
    public $uuidDiploma;
    public $conferencias = [];
    public $search = '';
    public $diploma;
    public $qrcode;
    public $persona;
    public $conferencia;
    public $evento;

    public function mount()
    {
        $this->loadConferencias();
    }

    // validar que la persona este inscrita en la conferencia
    // y ademas que la persona haya asistido a la conferencia
    public function validacionesPersonaConferencia()
    {
        return true;
    }

    public function loadConferencias()
    {
        $personaId = Auth::user()->persona->id;

        $suscripciones = Asistencia::where('asistencia', 1)
            ->whereHas('suscripcion', function ($query) use ($personaId) {
                $query->where('IdPersona', $personaId);
            })
            ->with('suscripcion.conferencia.evento', 'suscripcion.conferencia.conferencista.persona')
            ->get()
            ->map(function ($asistencia) {
                return [
                    'asistencia_id' => $asistencia->id,
                    'conferencia' => $asistencia->suscripcion->conferencia
                ];
            })
            ->unique('conferencia.id');

        $this->conferencias = $suscripciones;
    }

    public function descargarDiploma($IdAsistencia)
    {
        // Buscar la asistencia correspondiente al IdAsistencia
        $asistencia = Asistencia::find($IdAsistencia);

        if ($asistencia) {
            // Crear o actualizar la entrada en la tabla DiplomaGenerado
            $diplomaGenerado = DiplomaGenerado::firstOrCreate(
                ['IdAsistencia' => $IdAsistencia],
                ['uuid' =>  \Str::uuid()]
            );
            $uuidDiploma = $diplomaGenerado->uuid;

            $qrcode =QRCodeService::generateTextQRCode(
                config('app.url') . '/validarDiploma/' . $uuidDiploma
            );
            // Generar el PDF del diploma
            $pdf = PDF::loadView('livewire.descargarDiploma', [
                'asistencia' => $asistencia,
                'uuid' => $uuidDiploma,
                'qrcode' => $qrcode
            ])->setPaper('a4', 'landscape');

            // Configurar opciones adicionales para evitar distorsiones y asegurar color
            $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
            $pdf->getDomPDF()->set_option('isPhpEnabled', true);
            $pdf->getDomPDF()->set_option('isFontSubsettingEnabled', true);
          //  $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
            $pdf->getDomPDF()->set_option('isCssFloatEnabled', true);

            // Guardar el PDF temporalmente
            $pdfPath = 'diplomas/diploma_' . $uuidDiploma . '.pdf';
            Storage::put($pdfPath, $pdf->output());

            // Descargar el PDF
            return response()->download(storage_path('app/' . $pdfPath))->deleteFileAfterSend(true);
        } else {
            // Manejar el caso en que no se encuentre la asistencia
            session()->flash('error', 'No se encontró la asistencia correspondiente al IdAsistencia');
        }
    }

    public function render()
    {

        $conferencias = collect($this->conferencias)->filter(function ($item) {
            return str_contains(strtolower($item['conferencia']->nombre), strtolower($this->search)) ||
                str_contains(strtolower($item['conferencia']->evento->nombreevento), strtolower($this->search)) ||
                str_contains(strtolower($item['conferencia']->conferencista->persona->nombre), strtolower($this->search)) ||
                str_contains(strtolower($item['conferencia']->conferencista->persona->apellido), strtolower($this->search));
        });
        return view('livewire.HistorialConferencia.historial-conferencias', [
            'conferencias' => $this->conferencias,
        ]);
    }
}