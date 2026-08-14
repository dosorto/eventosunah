<?php

namespace App\Livewire\Participante;

use App\Models\EventoRegistro;
use App\Services\ParticipantBadgeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DetalleInscripcionEvento extends Component
{
    public EventoRegistro $registro;

    public function mount(EventoRegistro $registro): void
    {
        $registro->loadMissing([
            'evento.modalidad',
            'evento.localidad',
            'evento.tipoEvento',
            'tipoPerfil',
            'metodoPago',
            'persona',
        ]);

        abort_unless((int) (Auth::user()?->persona?->id ?? 0) === (int) $registro->persona_id || Auth::user()?->can('events.manage'), 403);

        $this->registro = $registro;
    }

    public function render(ParticipantBadgeService $badgeService)
    {
        $proofUrl = $this->registro->comprobante_pago_path
            ? Storage::disk('public')->url($this->registro->comprobante_pago_path)
            : null;

        return view('livewire.participante.detalle-inscripcion-evento', [
            'badgeAvailable' => $badgeService->isAvailable($this->registro),
            'proofUrl' => $proofUrl,
        ])->layout('components.layouts.app');
    }
}
