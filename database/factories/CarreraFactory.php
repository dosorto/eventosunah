<?php

namespace Database\Factories;

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
        $carreras = [
            'Ingeniería en Sistemas',
            'Ingeniería Agroindustrial',
            'Comercio',
            'Administración',
        ];
        return [
            'Nombre Carrera' => $this->faker->randomElement($carreras),
            'IdDepartamento' => function () {
                return \App\Models\Departamento::factory()->create()->id;
            },
            'created_by' => 1
        ];
    }
}
