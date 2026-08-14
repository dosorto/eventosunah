<?php

namespace Database\Seeders;

use App\Models\Moneda;
use Illuminate\Database\Seeder;

class MonedaSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['nombre' => 'Lempira hondureño', 'codigo' => 'HNL', 'simbolo' => 'L'],
            ['nombre' => 'Dólar estadounidense', 'codigo' => 'USD', 'simbolo' => '$'],
        ] as $moneda) {
            Moneda::query()->updateOrCreate(
                ['codigo' => $moneda['codigo']],
                $moneda,
            );
        }
    }
};
