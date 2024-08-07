<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Persona;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conferencista>
 */
class ConferencistaFactory extends Factory
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
         'Titulo' => $this->faker->sentence,
         'Descripcion' => $this->faker->paragraph,
         'Foto' => $this->faker->imageUrl(),
         'IdPersona' => $PersonaId,
         'created_by' => 1
        ];
    }
}