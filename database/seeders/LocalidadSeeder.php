<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Localidad;

class LocalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $localidades = [
            'UNAH-CAMPUS CHOLUTECA',
            'UNAH-CAMPUS CORTES',
            'UNAH-CAMPUS DANLI',
            'UNAH-CAMPUS CEIBA', 
            'UNAH-CAMPUS COMAYAGUA', 
            'La Gran Terminal del Pacifico', 
            'Unimall',                     
            'Colegio de Abogados',          
            'Hotel Intercontinental',       
            'Hotel Real Intercontinental',  
            'Hotel Marriott',              
            'Hotel Clarion',                
            'Hotel Palma Real',             
            'Hotel Gran Hotel Paris',       
            'Hotel Plaza',                  
            'Hotel La Posada',            
            'Hotel Lenca',                 
            'Hotel Los Alpes',             
            'Hotel las Rocas',             
            'Hotel Anthony’s Key Resort',   
            'Hotel Guacamaya',              
            'Hotel Payaqui',              
        ];

        foreach ($localidades as $localidad) {
            Localidad::updateOrCreate(
                ['localidad' => $localidad],
                ['created_by' => 1]
            );
        }
    }
}
