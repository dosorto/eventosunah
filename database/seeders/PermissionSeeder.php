<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'admin-modalidad',
            'admin-nacionalidad',
            'admin-tipoPerfil',
            'admin-localidad',
            'admin-departamento',
            'admin-carrera',
<<<<<<< HEAD
            'admin-persona',
            'admin-rol',
            'admin-conferencia',
=======
            'admin-evento',
>>>>>>> origin/acxel

         ];
         
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
