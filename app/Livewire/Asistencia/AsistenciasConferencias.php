<?php

namespace App\Livewire\Asistencia;

use App\Models\Asistencia;
use App\Models\Suscripcion;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader\PdfReader;
use setasign\Fpdi\PdfParser\StreamReader;
use App\Models\DiplomaGenerado;
use App\Services\QRCodeService;
use setasign\Fpdi\TcpdfFpdi;
use Illuminate\Support\Str;

class AsistenciasConferencias extends Component
{
    use WithPagination;

    public $search = '';
    public $conferencia_id;
    public $modalOpen = false;
    public $modalMessage = '';

    protected $paginationTheme = 'tailwind';

    public function mount($conferencia = null)
    {
        if ($conferencia) {
            $this->conferencia_id = $conferencia;
        } else {
            session()->flash('error', 'Conferencia no encontrada.');
            return redirect()->route('conferencias.index');
        }
    }

    public function marcarAsistencia($suscripcionId)
    {
        // Crear o actualizar la asistencia
        $asistencia = Asistencia::updateOrCreate(
            ['IdSuscripcion' => $suscripcionId],
            ['Fecha' => now(), 'Asistencia' => 1]
        );

        // Crear o actualizar el diploma generado
        DiplomaGenerado::updateOrCreate(
            ['IdAsistencia' => $asistencia->id],
            ['uuid' => Str::uuid()]
        );

        // Mensaje de éxito
        $this->modalMessage = 'Asistencia marcada correctamente.';
        $this->modalOpen = true;
    }

    public function marcarAusencia($suscripcionId)
    {
        Asistencia::updateOrCreate(
            ['IdSuscripcion' => $suscripcionId],
            ['Fecha' => now(), 'Asistencia' => 0]
        );
        $this->modalMessage = 'Ausencia marcada correctamente.';
        $this->modalOpen = true;
    }

    public function marcarTodos()
    {
        $suscripciones = Suscripcion::where('IdConferencia', $this->conferencia_id)->get();

        if ($suscripciones->isEmpty()) {
            $this->modalMessage = 'No hay suscripciones para marcar asistencia.';
            $this->modalOpen = true;
            return;
        }

        foreach ($suscripciones as $suscripcion) {
            $asistencia = Asistencia::updateOrCreate(
                ['IdSuscripcion' => $suscripcion->id],
                ['Fecha' => now(), 'Asistencia' => 1]
            );

            // Crear un nuevo registro en la tabla DiplomasGenerados
            DiplomaGenerado::updateOrCreate(
                ['IdAsistencia' => $asistencia->id],
                ['uuid' => Str::uuid()],
            );
        }

        $this->modalMessage = 'Todas las asistencias fueron marcadas.';
        $this->modalOpen = true;
    }

    public function descargarDiplomas($conferenciaId)
    {
        // Obtener las personas con asistencia 1 para la conferencia específica
        $asistentes = Asistencia::where('Asistencia', 1)
            ->whereHas('suscripcion', function ($query) use ($conferenciaId) {
                $query->where('IdConferencia', $conferenciaId);
            })
            ->get();
        // Crear un array para almacenar los PDFs individuales
        $pdfs = [];

        foreach ($asistentes as $asistencia) {
            // Obtener el UUID del diploma generado
            $diploma = DiplomaGenerado::where('IdAsistencia', $asistencia->id)->first();
            if ($diploma) {
                $uuid = $diploma->uuid;

                // Generar el QR code
                $qrcode = QRCodeService::generateTextQRCode(
                    config('app.url') . '/validarDiploma/' . $uuid
                );

                // Generar el contenido del diploma
                $data = [
                    'Nombre' => $asistencia->suscripcion->persona->nombre,
                    'Apellido' => $asistencia->suscripcion->persona->apellido,
                    'Conferencia' => $asistencia->suscripcion->conferencia->nombre,
                    'Conferencista' => $asistencia->suscripcion->conferencia->conferencista->persona->nombre . ' ' . $asistencia->suscripcion->conferencia->conferencista->persona->apellido,
                    'TituloConferencista' => $asistencia->suscripcion->conferencia->conferencista->titulo,
                    'FechaConferencia' => $asistencia->suscripcion->conferencia->fecha,
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
                    'FirmaConferencista' => $asistencia->suscripcion->conferencia->conferencista->firma,
                    'SelloConferencista' =>$asistencia->suscripcion->conferencia->conferencista->sello,
                    'uuid' => $uuid,
                    'qrcode' => $qrcode,
                ];
                $pdf = PDF::loadView('livewire.descargarDiploma', $data)->setPaper('a4', 'landscape');
                $pdfs[] = $pdf->output();
            }
        }

        // Combinar todos los PDFs en uno solo usando FPDI
        $combinedPdf = new Fpdi();
        foreach ($pdfs as $pdf) {
            $pageCount = $combinedPdf->setSourceFile(StreamReader::createByString($pdf));
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $combinedPdf->importPage($pageNo);
                $size = $combinedPdf->getTemplateSize($templateId);
                $combinedPdf->AddPage('L', [$size['width'], $size['height']]);
                $combinedPdf->useTemplate($templateId);
            }
        }

        // Generar el contenido del PDF combinado como una cadena
        $tempFilePath = tempnam(sys_get_temp_dir(), 'diplomas') . '.pdf';
        $combinedPdf->Output($tempFilePath, 'F');

        // Descargar el PDF combinado
        return response()->download($tempFilePath, 'diplomas.pdf')->deleteFileAfterSend(true);
    }





    public function render()
    {
        $suscripciones = Suscripcion::with(['persona', 'conferencia', 'asistencias'])
            ->where('IdConferencia', $this->conferencia_id)
            ->whereHas('persona', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido', 'like', '%' . $this->search . '%');
            })
            ->paginate(8);

        return view('livewire.Asistencia.asistencias-Conferencia', [
            'suscripciones' => $suscripciones,
            'idConferencia' => $this->conferencia_id,
        ]);
    }
}

