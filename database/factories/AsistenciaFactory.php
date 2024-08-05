<?php

namespace Database\Factories;

use App\Models\Asistencia;
use App\Models\Suscripcion;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsistenciaFactory extends Factory
{
    protected $model = Asistencia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Asegúrate de que hay al menos una Suscripcion en la base de datos
        $suscripcionId = Suscripcion::inRandomOrder()->first()->id ?? 1; // Default to 1 if no Suscripcion exists

        return [
            'Fecha' => $this->faker->date(),
            'Asistencia' => $this->faker->boolean(),
            'IdSuscripcion' => $suscripcionId,
            'created_by' => $this->faker->numberBetween(1, 10),
            'deleted_by' => $this->faker->optional()->numberBetween(1, 10),
            'updated_by' => $this->faker->optional()->numberBetween(1, 10),
        ];
    }
}
