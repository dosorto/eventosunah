<?php

namespace Database\Factories;
use App\Models\Asistencia;
use App\Models\Persona;
use App\Models\Conferencia;
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
        $personaId = Persona::inRandomOrder()->first()->id;
        $conferenciaId = Conferencia::inRandomOrder()->first()->id;
        return [
            'Fecha' => $this->faker->date(),
            'Asistencia' => $this->faker->boolean(),
            'IdPersona' => $personaId,
            'IdConferencia' => $conferenciaId,
            'created_by' => 1
        ];
    }
}
