<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_invitaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->restrictOnDelete();
            $table->string('codigo')->unique();
            $table->string('nombre_invitado');
            $table->string('correo_invitado')->nullable();
            $table->unsignedInteger('cupos')->default(1);
            $table->unsignedInteger('cupos_utilizados')->default(0);
            $table->boolean('activa')->default(true);
            $table->integer('created_by');
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_invitaciones');
    }
};
