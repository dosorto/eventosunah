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
           'nombre' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'fecha' => $this->faker->date(),
            'horaInicio' => $this->faker->time(),
            'horaFin' => $this->faker->time(),
            'lugar' => $this->faker->address,
            'linkreunion' => $this->faker->url,
            'idConferencista' => $conferencistaId,
            'created_by' => 1,
        ];
    }
}
