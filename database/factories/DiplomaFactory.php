<?php

namespace Database\Factories;
use App\Models\Diploma;
use App\Models\Conferencia;
use App\Models\Evento;
use App\Models\Firma;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Diploma>
 */
class DiplomaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conferenciaId = Conferencia::inRandomOrder()->first()->id;
        $eventoId = Evento::inRandomOrder()->first()->id;
        $firmaId = Firma::inRandomOrder()->first()->id;
        return [
            'codigo' => $this->faker->uuid,
            'URL' => $this->faker->url,
            'Fecha' => $this->faker->date(),
            'IdConferencia' => $conferenciaId,
            'IdFirma' => $firmaId,
            'created_by' => 1,
        ];
    }
}