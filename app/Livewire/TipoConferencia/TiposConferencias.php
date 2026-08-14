<?php

namespace App\Livewire\TipoConferencia;

use App\Models\TipoConferencia;
use Livewire\Component;
use Livewire\WithPagination;

class TiposConferencias extends Component
{
    use WithPagination;

    public string $tipo = '';
    public ?int $tipo_conferencia_id = null;
    public string $search = '';
    public bool $isOpen = false;
    public bool $confirmingDelete = false;
    public ?int $IdAEliminar = null;
    public string $nombreAEliminar = '';

    public function render()
    {
        $tiposConferencias = TipoConferencia::query()
            ->where('tipo', 'like', '%' . $this->search . '%')
            ->orderByDesc('id')
            ->paginate(8);

        return view('livewire.TipoConferencia.tipos-conferencias', [
            'tiposConferencias' => $tiposConferencias,
        ])->layout('components.layouts.app');
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function store(): void
    {
        $this->validate([
            'tipo' => ['required', 'string', 'max:150', 'unique:tipos_conferencias,tipo,' . $this->tipo_conferencia_id],
        ], [], [
            'tipo' => 'tipo de conferencia',
        ]);

        TipoConferencia::query()->updateOrCreate(
            ['id' => $this->tipo_conferencia_id],
            ['tipo' => trim($this->tipo)]
        );

        session()->flash(
            'message',
            $this->tipo_conferencia_id ? 'Tipo de conferencia actualizado correctamente.' : 'Tipo de conferencia creado correctamente.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit(int $id): void
    {
        $tipoConferencia = TipoConferencia::query()->findOrFail($id);
        $this->tipo_conferencia_id = $tipoConferencia->id;
        $this->tipo = $tipoConferencia->tipo;
        $this->isOpen = true;
    }

    public function confirmDelete(int $id): void
    {
        $tipoConferencia = TipoConferencia::query()->find($id);

        if (! $tipoConferencia) {
            session()->flash('error', 'Tipo de conferencia no encontrado.');

            return;
        }

        if ($tipoConferencia->conferencias()->exists()) {
            session()->flash('error', 'No se puede eliminar el tipo de conferencia "' . $tipoConferencia->tipo . '" porque está en uso.');

            return;
        }

        $this->IdAEliminar = $tipoConferencia->id;
        $this->nombreAEliminar = $tipoConferencia->tipo;
        $this->confirmingDelete = true;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete || ! $this->IdAEliminar) {
            return;
        }

        $tipoConferencia = TipoConferencia::query()->find($this->IdAEliminar);

        if (! $tipoConferencia) {
            session()->flash('error', 'Tipo de conferencia no encontrado.');
            $this->confirmingDelete = false;

            return;
        }

        $tipoConferencia->delete();
        $this->confirmingDelete = false;
        $this->IdAEliminar = null;
        $this->nombreAEliminar = '';

        session()->flash('message', 'Tipo de conferencia eliminado correctamente.');
    }

    private function resetInputFields(): void
    {
        $this->tipo = '';
        $this->tipo_conferencia_id = null;
    }
}
