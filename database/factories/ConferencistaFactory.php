<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            return [
                'Titulo' => $this->faker->sentence,
                'Descripcion' => $this->faker->paragraph,
                'Foto' => $this->faker->imageUrl(),
                'IdPersona' => function () {
                    return \App\Models\Persona::factory()->create()->id; 
                 },
            'created_by' => 1
            ];
    }
}
