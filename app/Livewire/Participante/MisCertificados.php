<?php

namespace App\Livewire\Participante;

use App\Models\EventoCertificado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class MisCertificados extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function downloadCertificate(int $certificateId)
    {
        $personaId = Auth::user()?->persona?->id;

        $certificate = EventoCertificado::query()
            ->where('persona_id', $personaId ?? 0)
            ->findOrFail($certificateId);

        if (! Storage::disk('public')->exists($certificate->pdf_path)) {
            session()->flash('error', 'El archivo del certificado no se encontró en almacenamiento.');

            return null;
        }

        return Storage::disk('public')->download(
            $certificate->pdf_path,
            basename($certificate->pdf_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    public function render()
    {
        $personaId = Auth::user()?->persona?->id;

        $certificados = EventoCertificado::query()
            ->with([
                'evento',
                'conferencia.tipoConferencia',
                'persona',
            ])
            ->where('persona_id', $personaId ?? 0)
            ->when($this->search !== '', function ($query) {
                $search = trim($this->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre_certificado', 'like', '%' . $search . '%')
                        ->orWhere('hash_unico', 'like', '%' . $search . '%')
                        ->orWhereHas('evento', fn ($eventoQuery) => $eventoQuery->where('nombreevento', 'like', '%' . $search . '%'))
                        ->orWhereHas('conferencia', fn ($conferenceQuery) => $conferenceQuery->where('nombre', 'like', '%' . $search . '%'));
                });
            })
            ->orderByDesc('generated_at')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.participante.mis-certificados', [
            'certificados' => $certificados,
        ])->layout('components.layouts.app');
    }
}
