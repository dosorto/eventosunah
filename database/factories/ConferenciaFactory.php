<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Conferencista;
use App\Models\Evento;
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
        $eventoId = Evento::inRandomOrder()->first()->id;
        $conferencistaId = Conferencista::inRandomOrder()->first()->id;
        return [
            'IdEvento' => $eventoId,
            'Foto' => $this->faker->imageUrl(),
           'nombre' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'fecha' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'horaInicio' => $this->faker->time(),
            'horaFin' => $this->faker->time(),
            'lugar' => $this->faker->address,
            'linkreunion' => $this->faker->url,
            'idConferencista' => $conferencistaId,
            'created_by' => 1,
        ];
    }
}