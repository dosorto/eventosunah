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
        $conferenciaId = Conferencia::inRandomOrder()->first()->id;
        return [
           'Nombre' => $this->faker->sentence,
            'Descripcion' => $this->faker->paragraph,
            'Organizador' => $this->faker->company,
            'Fecha Inicio' => $this->faker->date(),
            'Fecha Final' => $this->faker->date(),
            'HoraInicio' => $this->faker->time(),
            'HoraFin' => $this->faker->time(),
            'IdModalidad'=> $ModalidadId,
            'IdLocalidad' =>$LocalidadId,
            'IdConferencia' => $conferenciaId,
            'created_by' => 1
        ];
    }
}
