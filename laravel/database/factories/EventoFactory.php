<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Modalidad;
use App\Models\Localidad;
use App\Models\Conferencia;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evento>
 */
class EventoFactory extends Factory
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
            'Organizador' => $this->faker->company,
            'Fecha Inicio' => $this->faker->date(),
            'Fecha Final' => $this->faker->date(),
            'HoraInicio' => $this->faker->time(),
            'HoraFin' => $this->faker->time(),
            'Lugar' => $this->faker->address,
            'IdConferencia' => function () {
                return Conferencia::factory()->create()->id;
            },
            'IdModalidad' => function () {
                return Modalidad::factory()->create()->id;
            },
            'IdLocalidad' => function () {
                return Localidad::factory()->create()->id;
            },
            'IdConferencia' => function () {
                return Conferencia::factory()->create()->id;
            },
            'created_by' => 1
        ];
    }
}
