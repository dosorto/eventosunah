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
        Schema::create('diplomas', function (Blueprint $table) {
            $table->id();
            $table->string('Codigo')->unique();
            $table->string('Plantilla');
            $table->string('Nombre');
            $table->string('Titulo1');
            $table->string('NombreFirma1');
            $table->string('Firma1');
            $table->string('Sello1')->nullable();
            $table->string('Titulo2')->nullable();
            $table->string('NombreFirma2')->nullable();
            $table->string('Firma2')->nullable();
            $table->string('Sello2')->nullable();
           
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();

        //    $table->foreign('IdConferencia')->references('id')->on('conferencias')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diplomas');
    }
};