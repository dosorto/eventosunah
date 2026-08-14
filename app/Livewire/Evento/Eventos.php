<?php

namespace App\Livewire\Evento;

use App\Models\Evento;
use App\Models\Localidad;
use App\Models\Modalidad;
use App\Models\TipoConferencia;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Eventos extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $nombreevento = '';
    public $descripcion = '';
    public $organizador = '';
    public $tipo_conferencia_id = '';
    public $idmodalidad = '';
    public $localidad_nombre = '';
    public $tipo_acceso = 'gratuita';
    public $genera_diploma_participacion = true;
    public $fechainicio = '';
    public $fechafinal = '';
    public $evento_id;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $isOpen = false;
    public $confirmingDelete = false;
    public $eventoIdAEliminar;
    public $nombreEventoAEliminar = '';
    public $modalidades = [];
    public $tiposConferencias = [];

    protected $validationAttributes = [
        'nombreevento' => 'nombre del evento',
        'descripcion' => 'descripcion',
        'organizador' => 'organizador',
        'tipo_conferencia_id' => 'tipo de evento',
        'idmodalidad' => 'modalidad',
        'localidad_nombre' => 'localidad',
        'tipo_acceso' => 'tipo de acceso',
        'genera_diploma_participacion' => 'genera diploma de participacion',
        'fechainicio' => 'fecha de inicio',
        'fechafinal' => 'fecha final',
    ];

    public function mount(): void
    {
        $this->authorize('events.manage');
        $this->modalidades = $this->loadModalidades();
        $this->tiposConferencias = $this->loadTiposConferencias();
    }

    public function render()
    {
        $query = Evento::query()
            ->with(['modalidad', 'localidad', 'diploma', 'tipoEvento'])
            ->withCount('conferencias')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery
                        ->where('nombreevento', 'like', '%' . $this->search . '%')
                        ->orWhere('organizador', 'like', '%' . $this->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                        ->orWhere('localidad_nombre', 'like', '%' . $this->search . '%')
                        ->orWhereHas('tipoEvento', function ($tipoQuery) {
                            $tipoQuery->where('tipo', 'like', '%' . $this->search . '%');
                        });
                });
            });

        $stats = [
            'total' => (clone $query)->count(),
            'formulacion' => (clone $query)->where('estado', 'formulacion')->count(),
            'publicados' => (clone $query)->where('estado', 'publicado')->count(),
        ];

        $eventos = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(8);

        return view('livewire.Evento.eventos', [
            'eventos' => $eventos,
            'stats' => $stats,
        ])->layout('components.layouts.app');
    }

    protected function rules(): array
    {
        return [
            'nombreevento' => 'required|string|max:255',
            'descripcion' => 'required|string|max:500',
            'organizador' => 'required|string|max:255',
            'tipo_conferencia_id' => 'required|exists:tipos_conferencias,id',
            'idmodalidad' => 'required|exists:modalidads,id',
            'localidad_nombre' => 'required|string|max:255',
            'tipo_acceso' => 'required|string|in:gratuita,pagada',
            'genera_diploma_participacion' => 'required|boolean',
            'fechainicio' => 'required|date',
            'fechafinal' => 'required|date|after_or_equal:fechainicio',
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $this->authorize('events.manage');

        if (! in_array($field, ['id', 'nombreevento', 'organizador', 'estado', 'published_at'], true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('events.manage');
        $this->resetForm();
        $this->isOpen = true;
    }

    public function configure(int $id): void
    {
        $this->authorize('events.manage');
        $this->redirectRoute('eventos.configurar', ['evento' => $id], navigate: true);
    }

    public function openEvent(int $id): void
    {
        $this->authorize('events.manage');

        $evento = Evento::query()->select(['id', 'estado'])->findOrFail($id);

        if ($evento->estado === 'publicado') {
            $this->redirectRoute('eventos.gestion', ['evento' => $evento->id], navigate: true);

            return;
        }

        $this->redirectRoute('eventos.configurar', ['evento' => $evento->id], navigate: true);
    }

    public function store(): void
    {
        $this->authorize('events.manage');

        $validated = $this->validate();
        $localidad = Localidad::query()->firstOrCreate([
            'localidad' => trim($validated['localidad_nombre']),
        ]);

        $evento = Evento::query()->updateOrCreate(
            ['id' => $this->evento_id],
            [
                'nombreevento' => trim($validated['nombreevento']),
                'descripcion' => trim($validated['descripcion']),
                'organizador' => trim($validated['organizador']),
                'tipo_conferencia_id' => $validated['tipo_conferencia_id'],
                'idmodalidad' => $validated['idmodalidad'],
                'idlocalidad' => $localidad->id,
                'localidad_nombre' => $localidad->localidad,
                'tipo_acceso' => $validated['tipo_acceso'],
                'genera_diploma_participacion' => $validated['genera_diploma_participacion'],
                'fechainicio' => $validated['fechainicio'],
                'fechafinal' => $validated['fechafinal'],
                'estado' => 'formulacion',
            ]
        );

        session()->flash(
            'message',
            'Evento creado correctamente. Ahora completa su configuracion y publicacion.'
        );

        $this->closeModal();
        $this->redirectRoute('eventos.configurar', ['evento' => $evento->id], navigate: true);
    }

    public function confirmDelete(int $id): void
    {
        $this->authorize('events.manage');

        $evento = Evento::query()->findOrFail($id);

        if ($evento->conferencias()->exists()) {
            session()->flash(
                'error',
                'No se puede eliminar el evento "' . $evento->nombreevento . '" porque tiene conferencias asociadas.'
            );

            return;
        }

        $this->eventoIdAEliminar = $evento->id;
        $this->nombreEventoAEliminar = $evento->nombreevento;
        $this->confirmingDelete = true;
    }

    public function delete(): void
    {
        $this->authorize('events.manage');

        if (! $this->confirmingDelete || ! $this->eventoIdAEliminar) {
            return;
        }

        $evento = Evento::query()->find($this->eventoIdAEliminar);

        if (! $evento) {
            session()->flash('error', 'El evento ya no existe.');
            $this->confirmingDelete = false;
            return;
        }

        if ($evento->conferencias()->exists()) {
            session()->flash(
                'error',
                'No se puede eliminar el evento "' . $evento->nombreevento . '" porque tiene conferencias asociadas.'
            );
            $this->confirmingDelete = false;
            return;
        }

        $evento->delete();

        session()->flash('message', 'Evento eliminado correctamente.');

        $this->confirmingDelete = false;
        $this->eventoIdAEliminar = null;
        $this->nombreEventoAEliminar = '';
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->resetValidation();
        $this->reset([
            'nombreevento',
            'descripcion',
            'organizador',
            'tipo_conferencia_id',
            'idmodalidad',
            'localidad_nombre',
            'tipo_acceso',
            'genera_diploma_participacion',
            'fechainicio',
            'fechafinal',
            'evento_id',
        ]);

        $this->tipo_acceso = 'gratuita';
        $this->genera_diploma_participacion = true;
    }

    public function getEventDurationDaysProperty(): ?int
    {
        if (! $this->fechainicio || ! $this->fechafinal) {
            return null;
        }

        try {
            $start = Carbon::parse($this->fechainicio);
            $end = Carbon::parse($this->fechafinal);
        } catch (\Throwable) {
            return null;
        }

        if ($end->lt($start)) {
            return null;
        }

        return $start->diffInDays($end) + 1;
    }

    private function loadModalidades()
    {
        $canonical = [
            'Presencial',
            'Virtual',
            'Hibrida (Presencial/Virtual)',
        ];

        $hibrido = Modalidad::query()->where('modalidad', 'Híbrido')->first();

        if ($hibrido && ! Modalidad::query()->where('modalidad', 'Hibrida (Presencial/Virtual)')->exists()) {
            $hibrido->update(['modalidad' => 'Hibrida (Presencial/Virtual)']);
        }

        foreach ($canonical as $modalidad) {
            Modalidad::query()->firstOrCreate(['modalidad' => $modalidad]);
        }

        return Modalidad::query()
            ->whereIn('modalidad', $canonical)
            ->orderByRaw("CASE modalidad WHEN 'Presencial' THEN 1 WHEN 'Virtual' THEN 2 WHEN 'Hibrida (Presencial/Virtual)' THEN 3 ELSE 4 END")
            ->get();
    }

    private function loadTiposConferencias()
    {
        return TipoConferencia::query()
            ->orderBy('tipo')
            ->get();
    }
}
