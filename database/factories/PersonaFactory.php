<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
/*
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nacionalidadId = Nacionalidad::inRandomOrder()->first()->id;
        $tipoPerfilId = Tipoperfil::inRandomOrder()->first()->id;
        return [
            'DNI' => $this->faker->regexify('[0-9]{8}[A-Z]{1}'), 
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'correo' => $this->faker->safeEmail(),
            'Fecha de nacimiento' => $this->faker->date(),
            'Sexo' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'Direccion' => $this->faker->address(),
            'Telefono' => $this->faker->phoneNumber(),
            'IdNacionalidad' => $nacionalidadId,
            'IdTipoPerfil' => $tipoPerfilId,
            'created_by' => 1
        ];
    }
}
