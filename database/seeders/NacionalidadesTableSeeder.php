<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Nacionalidad;

class NacionalidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nacionalidades = [
            'Hondureña',
            'Salvadoreña',
            'Guatemalteca',
            'Nicaragüense',
            'Costarricense',
            'Panameña',
            'Colombiana',
            'Peruana',
            'Ecuatoriana',
            'Chilena',
            'Argentina',
            'Uruguaya',
            'Paraguaya',
            'Boliviana',
            'Venezolana',
            'Brasileña',
            'Cubana',
            'Dominicana',
            'Puertorriqueña',
            'Mexicana',
            'Canadiense',
            'Estadounidense',
            'Británica',
            'Francesa',
            'Alemana',
            'Italiano',
            'Española',
            'Portuguesa',
            'Holandesa',
            'Sueca',
            'Noruega',
            'Danesa',
            'Finlandesa',
            'Rusa',
            'Polaca',
            'Checa',
            'Húngara',
            'Rumana',
            'Búlgara',
            'Serbia',
            'Griega',
            'Turca',
            'Egipcia',
            'Marroquí',
            'Sudafricana',
            'Nigeriana',
            'Kenia',
            'Tanzana',
            'Japonés',
            'Chino',
            'Coreano',
            'Vietnamita',
            'Filipino',
            'Malasio',
            'Indonesio',
            'Indio',
            'Paquistaní',
            'Bangladesí',
            'Sri Lankés',
            'Nepalí',
            'Afgano',
            'Iraní',
            'Iraquí',
            'Sirio',
            'Libanés',
            'Jordano',
            'Yemení',
            'Omán',
            'Qatarí',
            'Emiratí',
            'Bahrainí',
        ];

        foreach ($nacionalidades as $nacionalidad) {
            Nacionalidad::updateOrCreate(
                ['nombreNacionalidad' => $nacionalidad],
                ['created_by' => 1]
            );
        }
    }
}
