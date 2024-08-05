<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nacionalidad;
use App\Models\Tipoperfil;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Conferencista>
 */
class ConferencistaFactory extends Factory
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
         'Titulo' => $this->faker->sentence,
         'Descripcion' => $this->faker->paragraph,
         'Foto' => $this->faker->imageUrl(),
         'dni' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'), 
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
