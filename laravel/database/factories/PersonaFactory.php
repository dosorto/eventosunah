<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
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
        return [
            'DNI' => $this->faker->regexify('[0-9]{8}[A-Z]{1}'), // Genera un DNI español simulado
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'correo' => $this->faker->safeEmail(),
            'Fecha de nacimiento' => $this->faker->date(),
            'Sexo' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'Direccion' => $this->faker->address(),
            'Telefono' => $this->faker->phoneNumber(),
            'IdNacionalidad' => function () {
                return \App\Models\Nacionalidad::factory()->create()->id; 
            },
            'IdTipoPerfil' => function () {
                return \App\Models\Tipoperfil::factory()->create()->id; 
            },
            'created_by' => 1
        ];
    }
}
