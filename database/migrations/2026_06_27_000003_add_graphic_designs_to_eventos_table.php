<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->json('graphic_designs')->nullable()->after('diploma_orientation');
        });

        DB::table('eventos')
            ->select('id', 'diploma_plantilla', 'diploma_page_size', 'diploma_orientation')
            ->orderBy('id')
            ->get()
            ->each(function ($evento): void {
                if (! $evento->diploma_plantilla) {
                    return;
                }

                DB::table('eventos')
                    ->where('id', $evento->id)
                    ->update([
                        'graphic_designs' => json_encode([
                            'participacion_general' => [
                                'file' => $evento->diploma_plantilla,
                                'page_size' => $evento->diploma_page_size,
                                'orientation' => $evento->diploma_orientation,
                            ],
                        ], JSON_UNESCAPED_UNICODE),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('graphic_designs');
        });
    }
};
