<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('tipo_acceso')->default('gratuita')->after('localidad_nombre');
            $table->boolean('genera_diploma_participacion')->default(true)->after('tipo_acceso');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_acceso',
                'genera_diploma_participacion',
            ]);
        });
    }
};
