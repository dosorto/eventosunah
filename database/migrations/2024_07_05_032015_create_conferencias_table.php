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
        Schema::create('conferencias', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre');
            $table->string('Descripcion',500);
            $table->date('Fecha');
            $table->time('HoraInicio');
            $table->time('HoraFin');
            $table->string('Lugar');
            $table->string('Link reunion');
            $table->unsignedBigInteger('IdConferencista');
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('IdConferencista')->references('id')->on('conferencistas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferencias');
    }
};
