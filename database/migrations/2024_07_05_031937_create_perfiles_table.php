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
        Schema::create('perfils', function (Blueprint $table) {
            $table->id();
            $table->string('Numero de Cuenta')->unique()->nullable();
            $table->string('Correo Institucional')->unique()->nullable();
            $table->unsignedBigInteger('IdPersona');
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
        Schema::dropIfExists('perfils');
    }
};
