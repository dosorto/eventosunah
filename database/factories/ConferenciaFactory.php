<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Conferencista;
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
        $conferencistaId = Conferencista::inRandomOrder()->first()->id;
        return [
           'Nombre' => $this->faker->sentence,
            'Descripcion' => $this->faker->paragraph,
            'Fecha' => $this->faker->date(),
            'HoraInicio' => $this->faker->time(),
            'HoraFin' => $this->faker->time(),
            'Lugar' => $this->faker->address,
            'Link reunion' => $this->faker->url,
            'IdConferencista' => $conferencistaId,
            'created_by' => 1,
        ];
    }
}
