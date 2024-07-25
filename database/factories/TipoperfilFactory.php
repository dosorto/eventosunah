<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoPerfil>
 */
class TipoperfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tipoperfil' => $this->faker->randomElement(['Estudiante', 'Docente', 'Empleado', 'Externo']),
            'created_by' => 1
        ];
    }
}
