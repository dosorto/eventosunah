<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrera;
class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carreras = [
            'Ingeniería en Sistemas',
            'Ingeniería Civil',
            'Arquitectura',
            'Medicina',
            'Psicología',
            'Derecho',
            'Administración de Empresas',
            'Contaduría Pública',
            'Biología',
            'Química',
            'Física',
            'Matemáticas',
            'Historia',
            'Literatura',
            'Filosofía',
            'Sociología',
            'Ciencias Políticas',
            'Educación',
            'Enfermería',
            'Odontología',
            'Trabajo Social',
            'Diseño Gráfico',
            'Mercadotecnia',
            'Ingeniería Industrial',
            'Tecnologías de la Información',
            'Ingeniería Electrónica',
            'Biotecnología',
            'Ingeniería Mecánica',
            'Ingeniería Ambiental',
        ];

        foreach ($carreras as $carrera) {
            Carrera::updateOrCreate(
                ['carrera' => $carrera],
                ['IdDepartamento' => 1, 
                'created_by' => 1] 
            );
        }
    }
}
