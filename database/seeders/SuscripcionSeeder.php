<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Suscripcion;

class SuscripcionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Suscripcion::factory()->count(50)->create(); // Ajusta el número según sea necesario
    }
}
