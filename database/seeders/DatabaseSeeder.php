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
           RolSeeder::class,
           UserTableSeeder::class,
           NacionalidadesTableSeeder::class,
           ModalidadSeeder::class,
           TipoPerfilSeeder::class,
           TipoConferenciaSeeder::class,
           MonedaSeeder::class,
           LocalidadSeeder::class,
           //MassiveEventDemoSeeder::class,
       ]);
    }
}
