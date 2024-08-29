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
            $table->unsignedBigInteger('IdEvento');
            $table->string('foto')->nullable();
            $table->string('nombre');
            $table->string('descripcion',500);
            $table->date('fecha');
            $table->time('horaInicio');
            $table->time('horaFin');
            $table->string('lugar');
            $table->string('linkreunion')->nullable();
            $table->unsignedBigInteger('idConferencista');
            $table->string('estado');
            $table->decimal('precio', 8, 2)->nullable(); 
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('IdEvento')->references('id')->on('eventos')->onDelete('restrict');
            $table->foreign('idConferencista')->references('id')->on('conferencistas')->onDelete('restrict');
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