<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perfil>
 */
class PerfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Numero de Cuenta' => $this->faker->unique()->numerify('###########'), 
            'Correo Institucional' => $this->faker->unique()->safeEmail(),
            'IdPersona' => function () {
                return \App\Models\Persona::factory()->create()->id; 
            },
            'created_by' => 1
        ];
    }
}
