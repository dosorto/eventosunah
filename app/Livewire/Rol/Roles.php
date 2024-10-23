<?php

namespace App\Livewire\Rol;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class Roles extends Component
{
    use WithPagination;

    public $permissions;
    public $role;
    public $name;
    public $search = '';
    public $selectedPermissions = [];
    public $isOpen = false;
    public $confirmingDelete = false;
    public $IdAEliminar;
    public $nombreAEliminar;
    protected $rules = [
        'name' => 'required|unique:roles,name',
        'selectedPermissions' => 'required|array',
    ];

    protected $listeners = ['roleStored' => '$refresh'];
    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')->orderBy('id', 'DESC')->paginate(5);

        return view('livewire.Rol.roles', ['roles' => $roles]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->permissions = Permission::all();
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:roles,name' . ($this->role ? ',' . $this->role->id : ''),
            'selectedPermissions' => 'required|array',
        ]);

        if ($this->role) {
            $this->update();
        } else {
            $this->createRole();
        }
    }

    private function createRole()
    {
        $role = Role::create([
            'name' => $this->name,
            'guard_name' => 'web'
        ]);

        $permissionIds = Permission::whereIn('id', $this->selectedPermissions)->pluck('id')->toArray();
        $role->syncPermissions($permissionIds);

        session()->flash('message', 'Role creado exitosamente.');
        $this->permissions = Permission::all();
        $this->reset();
        $this->isOpen = false;
    }

    public function edit(Role $role)
    {
        $this->role = $role;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->permissions = Permission::all();
        $this->isOpen = true;
    }

    public function update()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'selectedPermissions' => 'required|array',
            'selectedPermissions.*' => 'exists:permissions,id',
        ]);

        try {
            $role = Role::findOrFail($this->role->id);

            Log::info('Selected Permissions IDs:', $validatedData['selectedPermissions']);

            $role->update([
                'name' => $this->name,
            ]);

            $permissionIds = Permission::whereIn('id', $validatedData['selectedPermissions'])->pluck('id')->toArray();
            $role->syncPermissions($permissionIds);

            session()->flash('message', 'Rol actualizado');
            $this->permissions = Permission::all();
            $this->reset();
            $this->closeModal();
       

        } catch (\Exception $e) {
            session()->flash('message', 'Error al actualizar rol: ' . $e->getMessage());
            Log::error('Error updating role: ' . $e->getMessage());
        }
    }
    public function delete()
    {
        if ($this->confirmingDelete) {
            $role  = Role::find($this->IdAEliminar);

            if (!$role ) {
                session()->flash('error', 'Rol no encontrada.');
                $this->confirmingDelete = false;
                return;
            }

            $role ->forceDelete();
            session()->flash('message', 'Rol eliminado correctamente!');
            $this->confirmingDelete = false;
        }
    }

    public function confirmDelete($id)
    {
        $role  = Role::find($id);

        if (! $role ) {
            session()->flash('error', 'rol no encontrado.');
            return;
        }

        if ($role->users()->exists()) {
            session()->flash('error', 'No se puede eliminar el  rol: '. $role->name .', porque está enlazado a uno o más usuarios');
            return;
        }

        $this->IdAEliminar = $id;
        $this->nombreAEliminar = $role->name;
        $this->confirmingDelete = true;
    }

       
    public function closeModal()
    {
        $this->isOpen = false;
    }
    private function resetInputFields()
    {
        $this->name = '';
        $this->selectedPermissions = [];
    }
}