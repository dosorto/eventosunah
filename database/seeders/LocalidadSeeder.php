<?php

namespace Database\Seeders;

use App\Models\Localidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Localidad::factory()->count(50)->create();
    }
}
