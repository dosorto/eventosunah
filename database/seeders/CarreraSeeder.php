<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Carrera;
use App\Models\Departamento;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carreras = [
            ['carrera' => 'Ingeniería en Sistemas', 'IdDepartamento' => 1],
            ['carrera' => 'Ingeniería Civil', 'IdDepartamento' => 2],
            ['carrera' => 'Arquitectura', 'IdDepartamento' => 3],
            ['carrera' => 'Medicina', 'IdDepartamento' => 4],
            ['carrera' => 'Psicología', 'IdDepartamento' => 5],
            ['carrera' => 'Derecho', 'IdDepartamento' => 6],
            ['carrera' => 'Administración de Empresas', 'IdDepartamento' => 7],
            ['carrera' => 'Contaduría Pública', 'IdDepartamento' => 8],
            ['carrera' => 'Biología', 'IdDepartamento' => 9],
            ['carrera' => 'Química', 'IdDepartamento' => 10],
            ['carrera' => 'Física', 'IdDepartamento' => 11],
            ['carrera' => 'Matemáticas', 'IdDepartamento' => 12],
            ['carrera' => 'Historia', 'IdDepartamento' => 13],
            ['carrera' => 'Literatura', 'IdDepartamento' => 14],
            ['carrera' => 'Filosofía', 'IdDepartamento' => 15],
            ['carrera' => 'Sociología', 'IdDepartamento' => 16],
            ['carrera' => 'Ciencias Políticas', 'IdDepartamento' => 17],
            ['carrera' => 'Educación', 'IdDepartamento' => 18],
            ['carrera' => 'Enfermería', 'IdDepartamento' => 19],
            ['carrera' => 'Odontología', 'IdDepartamento' => 20],
            ['carrera' => 'Trabajo Social', 'IdDepartamento' => 21],
        ];


        foreach ($carreras as $carrera) {
            $departamento = Departamento::find($carrera['IdDepartamento']);

            if ($departamento) {
                Carrera::updateOrCreate(
                    ['carrera' => $carrera['carrera']],
                    [
                        'IdDepartamento' => $carrera['IdDepartamento'],
                        'created_by' => 1,
                    ]
                );
            } else {
                echo "Departamento con ID {$carrera['IdDepartamento']} no encontrado.\n";
            }
        }
    }
}
