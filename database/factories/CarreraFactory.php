<?php

namespace Database\Factories;

use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carrera>
 */
class CarreraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $DepartamentoId = Departamento::inRandomOrder()->first()->id;
        $carreras = [
            'Ingeniería en Sistemas',
            'Ingeniería Agroindustrial',
            'Comercio',
            'Administración',
        ];
        return [
            'carrera' => $this->faker->randomElement($carreras),
            'IdDepartamento' => $DepartamentoId,
            'created_by' => 1
        ];
    }
}
