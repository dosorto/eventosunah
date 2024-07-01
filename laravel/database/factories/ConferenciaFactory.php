<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conferencia>
 */
class ConferenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'Nombre' => $this->faker->sentence,
            'Descripcion' => $this->faker->paragraph,
            'Fecha' => $this->faker->date(),
            'HoraInicio' => $this->faker->time(),
            'HoraFin' => $this->faker->time(),
            'Lugar' => $this->faker->address,
            'Link reunion' => $this->faker->url,
            'IdConferencista' => function () {
                return \App\Models\Conferencista::factory()->create()->id;
            },
            'created_by' => 1,
        ];
    }
}
