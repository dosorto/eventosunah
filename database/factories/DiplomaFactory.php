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
      //  $conferenciaId = Conferencia::inRandomOrder()->first()->id;
        return [
            'codigo' => $this->faker->uuid,
            'plantilla' => $this->faker->imageUrl(),
            'Nombre' => $this->faker->name(),
          //  'IdConferencia' => $conferenciaId,
            
            'titulo1' => $this->faker->title(),
            'nombrefirma1' => $this->faker->name(),
            'firma1' => $this->faker->imageUrl(),
            'sello1' => $this->faker->imageUrl(),

            'titulo2' => $this->faker->title(),
            'nombrefirma2' => $this->faker->name(),
            'firma2' => $this->faker->imageUrl(),
            'sello2' => $this->faker->imageUrl(),

            'created_by' => 1,
        ];
    }
}