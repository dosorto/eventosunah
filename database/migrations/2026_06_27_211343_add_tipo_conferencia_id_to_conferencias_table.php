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
        Schema::table('conferencias', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_conferencia_id')->nullable()->after('IdEvento');
            $table->foreign('tipo_conferencia_id')
                ->references('id')
                ->on('tipos_conferencias')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->dropForeign(['tipo_conferencia_id']);
            $table->dropColumn('tipo_conferencia_id');
        });
    }
};
