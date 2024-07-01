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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre');
            $table->string('Descripcion',500);
            $table->string('Organizador');
            $table->date('Fecha Inicio');
            $table->date('Fecha Final');
            $table->time('HoraInicio');
            $table->time('HoraFin');
            $table->unsignedBigInteger('IdModalidad');
            $table->unsignedBigInteger('IdLocalidad');
            $table->unsignedBigInteger('IdConferencia');
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('IdModalidad')->references('id')->on('modalidads')->onDelete('restrict');
            $table->foreign('IdLocalidad')->references('id')->on('localidads')->onDelete('restrict');
            $table->foreign('IdConferencia')->references('id')->on('conferencias')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
