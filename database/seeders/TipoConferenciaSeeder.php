<?php

namespace Database\Seeders;

use App\Models\TipoConferencia;
use Illuminate\Database\Seeder;

class TipoConferenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Diplomado',
            'Curso',
            'Conferencia',
            'Seminario',
            'Taller',
            'Simposio',
            'Charla',
            'Foro',
            'Congresos',
        ] as $tipo) {
            TipoConferencia::query()->firstOrCreate([
                'tipo' => $tipo,
            ]);
        }
    }
}
