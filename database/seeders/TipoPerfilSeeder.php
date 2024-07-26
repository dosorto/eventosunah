<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tipoperfil;
class TipoPerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoperfiles = [
            'Estudiante',
            'Empleado',
            'Docente',
            'Externo'
        ];

        foreach ($tipoperfiles as $tipoperfil) {
            Tipoperfil::updateOrCreate(
                ['tipoperfil' => $tipoperfil],
                ['created_by' => 1]
            );
        }
    }
}
