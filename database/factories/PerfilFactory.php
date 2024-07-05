<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Persona;
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
        $PersonaId = Persona::inRandomOrder()->first()->id;
        return [
            'Numero de Cuenta' => $this->faker->unique()->numerify('###########'), 
            'Correo Institucional' => $this->faker->unique()->safeEmail(),
            'IdPersona' => $PersonaId,
            'created_by' => 1
        ];
    }
}
