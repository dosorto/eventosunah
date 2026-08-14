<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_registros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('persona_id');
            $table->unsignedBigInteger('tipoperfil_id');
            $table->decimal('precio_aplicado', 10, 2)->default(0);
            $table->string('detalle_precio')->nullable();
            $table->string('estado')->default('registrado');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['evento_id', 'persona_id']);
            $table->foreign('evento_id')->references('id')->on('eventos')->cascadeOnDelete();
            $table->foreign('persona_id')->references('id')->on('personas')->restrictOnDelete();
            $table->foreign('tipoperfil_id')->references('id')->on('tipoperfils')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_registros');
    }
};
