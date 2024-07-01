<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        

        $this->call([
            NacionalidadSeeder::class,
            FirmaSeeder::class,
            TipoperfilSeeder::class,
            DepartamentoSeeder::class,
            CarreraSeeder::class,
            LocalidadSeeder::class,
            ModalidadSeeder::class,
            PersonaSeeder::class,
            PerfilSeeder::class,
            ConferencistaSeeder::class,
            ConferenciaSeeder::class,
            EventoSeeder::class,
            DiplomaSeeder::class,
            AsistenciaSeeder::class
        ]);
    }
}
