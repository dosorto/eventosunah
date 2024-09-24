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
        Schema::create('inscripcions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('IdEvento');
            $table->unsignedBigInteger('IdPersona');
            $table->unsignedBigInteger('IdRecibo');
            $table->string('Status')->default('pendiente'); 
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('IdEvento')->references('id')->on('eventos')->onDelete('restrict');
            $table->foreign('IdRecibo')->references('id')->on('recibopagos')->onDelete('restrict');
            $table->foreign('IdPersona')->references('id')->on('personas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcions');
    }
};
