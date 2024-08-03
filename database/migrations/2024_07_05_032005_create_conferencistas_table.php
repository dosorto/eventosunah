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
            $table->string('Descripcion', 500);
            $table->string('Foto');
            $table->string('dni')->unique();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('correo')->unique();
            $table->string('correoInstitucional')->nullable()->unique();
            $table->date('fechaNacimiento');
            $table->string('sexo');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('numeroCuenta')->nullable()->unique();
            $table->unsignedBigInteger('IdNacionalidad');
            $table->unsignedBigInteger('IdTipoPerfil');
            $table->integer('created_by')->nullable(); // Permitir valores nulos
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        
            $table->foreign('IdNacionalidad')->references('id')->on('nacionalidads')->onDelete('restrict');
            $table->foreign('IdTipoPerfil')->references('id')->on('tipoperfils')->onDelete('restrict');
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
