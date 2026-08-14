<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conferencia_registro_asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conferencia_id');
            $table->unsignedBigInteger('evento_registro_id');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['conferencia_id', 'evento_registro_id'], 'conferencia_registro_asistencia_unique');
            $table->foreign('conferencia_id')->references('id')->on('conferencias')->cascadeOnDelete();
            $table->foreign('evento_registro_id')->references('id')->on('evento_registros')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conferencia_registro_asistencias');
    }
};
