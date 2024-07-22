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
        $ModalidadId = Modalidad::inRandomOrder()->first()->id;
        $LocalidadId = Localidad::inRandomOrder()->first()->id;
        
        return [
           'nombreevento' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'organizador' => $this->faker->company,
            'idmodalidad'=> $ModalidadId,
            'idlocalidad' =>$LocalidadId,
            'created_by' => 1
        ];
    }
}
