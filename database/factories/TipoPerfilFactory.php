<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tipoperfil;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TipoPerfil>
 */
class TipoPerfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Nombre' => $this->faker->randomElement(['Estudiante', 'Docente', 'Empleado', 'Externo']),
            'created_by' => 1
        ];
    }
}
