<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Departamento>
 */
class DepartamentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'Departamento' => $this->faker->randomElement(['Ingeniería en Sistemas','Ingeniería Agroindustrial', 'Ciencias Políticas']),
           'created_by' => 1
        ];
    }
}
