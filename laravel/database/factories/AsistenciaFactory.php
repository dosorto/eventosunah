<?php

namespace Database\Factories;
use App\Models\Asistencia;
use App\Models\Persona;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asistencia>
 */
class AsistenciaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Fecha' => $this->faker->date(),
            'Asistencia' => $this->faker->boolean(),
            'IdPersona' => function () {
                return Persona::factory()->create()->id;
            },
            'IdEvento' => function () {
                return Evento::factory()->create()->id;
            },
            'created_by' => 1
        ];
    }
}
