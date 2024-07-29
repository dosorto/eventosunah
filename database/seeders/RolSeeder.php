<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crea el rol 'Participante'
        $role = Role::create(['name' => 'Participante', 'guard_name' => 'web']);

        // Encuentra el permiso 'Admin-Participante'
        $permission = Permission::where('name', 'Admin-Participante')->first();

        // Asigna el permiso al rol
        if ($permission) {
            $role->givePermissionTo($permission);
        }
    }
}
