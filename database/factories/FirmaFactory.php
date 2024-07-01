<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Firma>
 */
class FirmaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Nombre' => $this->faker->name(),
            'Firma' => $this->faker->numberBetween(1000, 9999),
            'Sello' => $this->faker->numberBetween(1000, 9999),
            'created_by' => 1
        ];
    }
}
