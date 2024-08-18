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
        Schema::create('diploma_generados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('IdAsistencia')->unique();
            $table->string('uuid')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('IdAsistencia')->references('id')->on('asistencias')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diploma_generados', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('diploma_generados');
    }
};
