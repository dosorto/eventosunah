<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nacionalidad;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nacionalidad>
 */
class NacionalidadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Nacionalidad::class;
    public function definition(): array
    {
        return [
            'nombreNacionalidad' => $this->faker->country(),
            'created_by' => 1,
        ];
    }
}
