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
        Schema::create('diploma_eventos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inscripcionId')->unique();
            $table->string('uuid')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('inscripcionId')->references('id')->on('inscripcions')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diploma_eventos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('diploma_eventos');
    }
};
