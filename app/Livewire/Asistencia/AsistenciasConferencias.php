<?php

namespace App\Livewire\Asistencia;

use App\Models\Asistencia;
use App\Models\Suscripcion;
use Livewire\Component;
use Livewire\WithPagination;

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
        Asistencia::updateOrCreate(
            ['IdSuscripcion' => $suscripcionId],
            ['Fecha' => now(), 'Asistencia' => 1]
        );

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
            Asistencia::updateOrCreate(
                ['IdSuscripcion' => $suscripcion->id],
                ['Fecha' => now(), 'Asistencia' => 1]
            );
        }

        $this->modalMessage = 'Todas las asistencias fueron marcadas.';
        $this->modalOpen = true;
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

        return view('livewire.asistencia.asistencias-conferencia', [
            'suscripciones' => $suscripciones,
        ]);
    }
}

