<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'root', 
            'password' => bcrypt('12345678')
        ]);

        $user->syncRoles(['super-admin']);
    }
}
