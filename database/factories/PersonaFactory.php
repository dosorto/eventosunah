<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
use App\Models\User;

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
        $nacionalidadId = Nacionalidad::inRandomOrder()->first()->id;
        $usuarioId = User::inRandomOrder()->first()->id;
        $tipoPerfilId = Tipoperfil::inRandomOrder()->first()->id;

        return [
            'IdUsuario' => $usuarioId,
            'dni' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'), 
            'foto' => $this->faker->imageUrl(),
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'correo' => $this->faker->unique()->safeEmail(),
            'fechaNacimiento' => $this->faker->date(),
            'sexo' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'IdNacionalidad' => $nacionalidadId,
            'IdTipoPerfil' => $tipoPerfilId,
            'numeroCuenta' => $this->faker->unique()->numerify('###########'), 
            'correoInstitucional' => $this->faker->unique()->safeEmail(),
            'created_by' => 1
        ];
    }
}
