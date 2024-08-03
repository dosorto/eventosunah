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
        Schema::create('conferencistas', function (Blueprint $table) {
            $table->id();
            $table->string('Titulo');
            $table->string('Descripcion',500);
            $table->string('Foto');
            $table->unsignedBigInteger('IdPersona')->unique();
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('IdPersona')->references('id')->on('personas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferencistas');
    }
};
