<?php

namespace Database\Factories;

use App\Models\Diploma;
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
        $ModalidadId = Modalidad::inRandomOrder()->first()-> id;
        $LocalidadId = Localidad::inRandomOrder()->first()->id;
        $diploma = Diploma::inRandomOrder()->first()->id;
        
        return [
            'logo' => $this->faker->imageUrl(),
            'nombreevento' => $this->faker->sentence,
            'descripcion' => $this->faker->paragraph,
            'organizador' => $this->faker->company,
            'fechainicio' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'fechafinal' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'horainicio' => $this->faker->time,
            'horafin' => $this->faker->time,
            'idmodalidad'=> $ModalidadId,
            'idlocalidad' =>$LocalidadId,
            'IdDiploma' => $diploma ,
            'created_by' => 1
        ];
    }
}