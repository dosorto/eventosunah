<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_precios', function (Blueprint $table) {
            $table->unsignedBigInteger('moneda_id')->nullable()->after('IdTipoPerfil');
            $table->foreign('moneda_id')->references('id')->on('monedas')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('evento_precios', function (Blueprint $table) {
            $table->dropForeign(['moneda_id']);
            $table->dropColumn('moneda_id');
        });
    }
};
