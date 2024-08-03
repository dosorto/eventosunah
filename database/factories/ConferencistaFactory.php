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
         'Foto' => $this->faker->imageUrl(),
         'dni' => $this->faker->unique()->regexify('[0-9]{8}[A-Z]{1}'), 
         'nombre' => $this->faker->firstName(),
         'apellido' => $this->faker->lastName(),
         'Titulo' => $this->faker->sentence,      
         'correo' => $this->faker->unique()->safeEmail(),
         'fechaNacimiento' => $this->faker->date(),
         'sexo' => $this->faker->randomElement(['Masculino', 'Femenino']),
         'telefono' => $this->faker->phoneNumber(),
         'IdNacionalidad' => $nacionalidadId,
         'Descripcion' => $this->faker->paragraph, 
         'direccion' => $this->faker->address(),
         'IdTipoPerfil' => $tipoPerfilId,
         'correoInstitucional' => $this->faker->unique()->safeEmail(),   
         'numeroCuenta' => $this->faker->unique()->numerify('###########'),       
         'created_by' => 1
        ];
    }
}
