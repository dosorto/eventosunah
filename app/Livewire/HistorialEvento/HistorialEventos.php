<?php

namespace App\Livewire\HistorialEvento;

use Livewire\Component;
use App\Models\Asistencia;
use Illuminate\Support\Facades\Auth;
use App\Models\DiplomaGenerado;
use Illuminate\Support\Str;
use App\Services\QRCodeService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Livewire\WithFileUploads;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Storage;


class HistorialEventos extends Component
{
    use WithFileUploads;

    public $asistencia;
    public $uuidDiploma;
    public $eventos = [];
    public $search = '';
    

    public function mount()
    {
        $this->loadEventos();
    }

    // validar que la persona este inscrita en la conferencia
    // y ademas que la persona haya asistido a la conferencia
    public function validacionesPersonaConferencia()
    {
        return true;
    }

    public function loadEventos()
    {
        $personaId = Auth::user()->persona->id;
    
        $inscripciones = Inscripcion::whereHas('evento', function ($query) use ($personaId) {
                $query->where('IdPersona', $personaId);
            })
            ->with('evento')
            ->get()
            ->map(function ($inscripcion) {
                return [
                    'inscripcion_id' => $inscripcion->id,
                    'evento' => $inscripcion->evento,
                ];
            })
            ->unique('evento.id');
    
        $this->eventos = $inscripciones;
    }

    public function descargarDiploma($IdAsistencia)
    {
        // Buscar la asistencia correspondiente al IdAsistencia
        $asistencia = Asistencia::find($IdAsistencia);

        if (!$asistencia) {
            // Manejar el caso en que no se encuentre la asistencia
            session()->flash('error', 'No se encontró la asistencia correspondiente al IdAsistencia');
            return;
        }

        // Buscar la entrada en la tabla DiplomaGenerado
        $diplomaGenerado = DiplomaGenerado::where('IdAsistencia', $IdAsistencia)->first();

        if (!$diplomaGenerado) {
            // Manejar el caso en que no se encuentre el diploma generado
            session()->flash('error', 'No se encontró el diploma generado correspondiente al IdAsistencia');
            return;
        }

        $uuidDiploma = $diplomaGenerado->uuid;

        // Generar el código QR
        $qrcode = QRCodeService::generateTextQRCode(
            config('app.url') . '/validarDiploma/' . $uuidDiploma
        );

        // Generar el PDF del diploma
        $pdf = PDF::loadView('livewire.diploma-evento', [
            'Nombre' => $asistencia->suscripcion->persona->nombre,
            'Apellido' => $asistencia->suscripcion->persona->apellido,
            'FechaInicio' => $asistencia->suscripcion->conferencia->evento->fechainicio,
            'Organizador' => $asistencia->suscripcion->conferencia->evento->organizador,
            'Evento' => $asistencia->suscripcion->conferencia->evento->nombreevento,
            'NombreFirma1' => $asistencia->suscripcion->conferencia->evento->diploma->NombreFirma1,
            'NombreFirma2' => $asistencia->suscripcion->conferencia->evento->diploma->NombreFirma2,
            'Titulo1' => $asistencia->suscripcion->conferencia->evento->diploma->Titulo1,
            'Titulo2' => $asistencia->suscripcion->conferencia->evento->diploma->Titulo2,
            'Plantilla' => $asistencia->suscripcion->conferencia->evento->diploma->Plantilla,
            'Firma1' => $asistencia->suscripcion->conferencia->evento->diploma->Firma1,
            'Firma2' => $asistencia->suscripcion->conferencia->evento->diploma->Firma2,
            'Sello1' => $asistencia->suscripcion->conferencia->evento->diploma->Sello1,
            'Sello2' => $asistencia->suscripcion->conferencia->evento->diploma->Sello2,
            'uuid' => $uuidDiploma,
            'qrcode' => $qrcode,
        ])->setPaper('a4', 'landscape');

        // Configurar opciones adicionales para evitar distorsiones y asegurar color
        $dompdf = $pdf->getDomPDF();
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isFontSubsettingEnabled', true);
        $dompdf->set_option('isCssFloatEnabled', true);

        // Guardar el PDF temporalmente
        $pdfPath = sprintf(
            'diplomas/Diploma_%s%s_%s.pdf',
            $asistencia->suscripcion->persona->nombre,
            $asistencia->suscripcion->persona->apellido,
            $uuidDiploma
        );
        Storage::put($pdfPath, $pdf->output());

        // Descargar el PDF
        return response()->download(storage_path('app/' . $pdfPath))->deleteFileAfterSend(true);
    }

    public function render()
{
    $eventos = collect($this->eventos)->filter(function ($item) {
        return str_contains(strtolower($item['evento']->nombreevento), strtolower($this->search)) ||
            str_contains(strtolower($item['evento']->id), strtolower($this->search)) ||
            str_contains(strtolower($item['evento']->organizador), strtolower($this->search)) ||
            str_contains(strtolower($item['evento']->modalidad->modalidad), strtolower($this->search)) ||
            str_contains(strtolower($item['evento']->fechainicio), strtolower($this->search)) ||
            str_contains(strtolower($item['evento']->estado), strtolower($this->search));
    });

    return view('livewire.HistorialEvento.historial-eventos', [
        'eventos' => $eventos,
    ]);
}
}