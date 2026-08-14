<?php

namespace App\Livewire\Moneda;

use App\Models\Moneda;
use Livewire\Component;
use Livewire\WithPagination;

class Monedas extends Component
{
    use WithPagination;

    public $nombre = '';
    public $codigo = '';
    public $simbolo = '';
    public $moneda_id = null;
    public $search = '';
    public $isOpen = false;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $monedas = Moneda::query()
            ->where(function ($query) {
                $query
                    ->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('simbolo', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nombre')
            ->paginate(8);

        return view('livewire.Moneda.monedas', compact('monedas'))
            ->layout('components.layouts.app');
    }

    public function create(): void
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal(): void
    {
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function store(): void
    {
        $validated = $this->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'codigo' => ['required', 'string', 'max:10', 'unique:monedas,codigo,' . $this->moneda_id],
            'simbolo' => ['nullable', 'string', 'max:12'],
        ]);

        Moneda::query()->updateOrCreate(
            ['id' => $this->moneda_id],
            [
                'nombre' => trim($validated['nombre']),
                'codigo' => strtoupper(trim($validated['codigo'])),
                'simbolo' => trim((string) $validated['simbolo']) ?: null,
            ]
        );

        session()->flash('message', $this->moneda_id ? 'Moneda actualizada correctamente.' : 'Moneda creada correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit(int $id): void
    {
        $moneda = Moneda::query()->findOrFail($id);

        $this->moneda_id = $moneda->id;
        $this->nombre = $moneda->nombre;
        $this->codigo = $moneda->codigo;
        $this->simbolo = $moneda->simbolo ?? '';

        $this->openModal();
    }

    public function confirmDelete(int $id): void
    {
        $moneda = Moneda::query()->find($id);

        if (! $moneda) {
            session()->flash('error', 'Moneda no encontrada.');
            return;
        }

        if ($moneda->eventoPrecios()->exists()) {
            session()->flash('error', 'No se puede eliminar la moneda porque ya está siendo utilizada en precios de eventos.');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $moneda->nombre;
        $this->confirmingDelete = true;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        $moneda = Moneda::query()->find($this->IdAEliminar);

        if (! $moneda) {
            session()->flash('error', 'Moneda no encontrada.');
            $this->confirmingDelete = false;
            return;
        }

        $moneda->delete();
        $this->confirmingDelete = false;
        session()->flash('message', 'Moneda eliminada correctamente.');
    }

    private function resetInputFields(): void
    {
        $this->nombre = '';
        $this->codigo = '';
        $this->simbolo = '';
        $this->moneda_id = null;
    }
}
