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
            'admin-dashboard',
            'admin-modalidad',
            'admin-nacionalidad',
            'admin-tipoPerfil',
            'admin-localidad',
            'admin-departamento',
            'admin-carrera',
            'admin-persona',
            'admin-rol',
            'admin-conferencia',
            'admin-conferencista',
            'admin-evento',
            'admin-asistencia',
            'admin-usuario',
            'admin-Participante',
            'admin-diploma',
            'admin-miAsistencia',
            'admin-Historial',
         ];
         
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}