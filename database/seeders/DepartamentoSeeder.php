<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;
class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            'Departamento de Ingeniería en Sistemas',
            'Departamento de Ingeniería Civil',
            'Departamento de Arquitectura',
            'Departamento de Medicina',
            'Departamento de Psicología',
            'Departamento de Derecho',
            'Departamento de Administración de Empresas',
            'Departamento de Contaduría Pública',
            'Departamento de Biología',
            'Departamento de Química',
            'Departamento de Física',
            'Departamento de Matemáticas',
            'Departamento de Historia',
            'Departamento de Literatura',
            'Departamento de Filosofía',
            'Departamento de Sociología',
            'Departamento de Ciencias Políticas',
            'Departamento de Educación',
            'Departamento de Enfermería',
            'Departamento de Odontología',
            'Departamento de Trabajo Social',
        ];

        foreach ($departamentos as $departamento) {
            Departamento::updateOrCreate(
                ['departamento' => $departamento],
                ['created_by' => 1]
            );
        }
    }
}
