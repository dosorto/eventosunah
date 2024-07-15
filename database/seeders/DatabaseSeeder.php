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
        // User::factory(10)->create();

       $this->call([
           PermissionSeeder::class,
           NacionalidadesTableSeeder::class,
           ModalidadSeeder::class,
           UserTableSeeder::class,
           FirmaSeeder::class,
           TipoPerfilSeeder::class,
           DepartamentoSeeder::class,
           CarreraSeeder::class,
           LocalidadSeeder::class,
           PersonaSeeder::class,
           ConferencistaSeeder::class,
           ConferenciaSeeder::class,
           EventoSeeder::class,
           DiplomaSeeder::class,
           AsistenciaSeeder::class
       ]);
    }
}
