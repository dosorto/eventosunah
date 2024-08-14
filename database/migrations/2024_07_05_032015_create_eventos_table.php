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
            $table->string('logo')->nullable();
            $table->string('nombreevento');
            $table->string('descripcion',500);
            $table->string('organizador');
            $table->date('fechainicio');
            $table->date('fechafinal');
            $table->time('horainicio');
            $table->time('horafin');
            $table->unsignedBigInteger('idmodalidad');
            $table->unsignedBigInteger('idlocalidad');
            $table->unsignedBigInteger('IdDiploma');
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('idmodalidad')->references('id')->on('modalidads')->onDelete('restrict');
            $table->foreign('idlocalidad')->references('id')->on('localidads')->onDelete('restrict');
            $table->foreign('IdDiploma')->references('id')->on('diplomas')->onDelete('restrict');
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
