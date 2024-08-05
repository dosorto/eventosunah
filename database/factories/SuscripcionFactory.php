<?php

namespace Database\Factories;

use App\Models\Suscripcion;
use App\Models\Conferencia;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuscripcionFactory extends Factory
{
    protected $model = Suscripcion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Asegúrate de que haya al menos una Conferencia y una Persona en la base de datos
        $conferencia = Conferencia::inRandomOrder()->first();
        $persona = Persona::inRandomOrder()->first();

        return [
            'IdConferencia' => $conferencia ? $conferencia->id : 1, // Usa un valor predeterminado si no se encuentra Conferencia
            'IdPersona' => $persona ? $persona->id : 1, // Usa un valor predeterminado si no se encuentra Persona
            'created_by' => $this->faker->numberBetween(1, 10),
            'deleted_by' => $this->faker->optional()->numberBetween(1, 10),
            'updated_by' => $this->faker->optional()->numberBetween(1, 10),
        ];
    }
}
