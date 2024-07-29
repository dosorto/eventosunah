<?php

namespace Database\Factories;

use App\Models\Asistencia;
use App\Models\Suscripcion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asistencia>
 */
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
        // Obtén una suscripción aleatoria, o null si no hay ninguna
        $suscripcion = Suscripcion::inRandomOrder()->first();
        $suscripcionId = $suscripcion ? $suscripcion->id : null;

        return [
            'Fecha' => $this->faker->date(),
            'Asistencia' => $this->faker->boolean(),
            'IdSuscripcion' => $suscripcionId,
            'created_by' => 1
        ];
    }
}
