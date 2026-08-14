<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('evento_precios', function (Blueprint $table) {
            $table->unsignedBigInteger('IdTipoPerfil')->nullable()->after('evento_id');
            $table->boolean('es_precio_evento_dia')->default(false)->after('categoria_nombre');

            $table->foreign('IdTipoPerfil')
                ->references('id')
                ->on('tipoperfils')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evento_precios', function (Blueprint $table) {
            $table->dropForeign(['IdTipoPerfil']);
            $table->dropColumn(['IdTipoPerfil', 'es_precio_evento_dia']);
        });
    }
};
