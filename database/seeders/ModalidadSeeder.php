<?php

namespace Database\Seeders;

use App\Models\Modalidad;
use Illuminate\Database\Seeder;

class ModalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modalidades = [
            'Virtual',
            'Presencial',
            'Híbrido',
        ];

        foreach ($modalidades as $modalidad) {
            Modalidad::updateOrCreate(
                ['modalidad' => $modalidad], // La clave aquí debe ser un array asociativo
                ['created_by' => 1] // Los datos a insertar o actualizar
            );
        }
    }
}
