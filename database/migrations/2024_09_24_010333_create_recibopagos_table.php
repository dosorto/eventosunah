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
        Schema::create('recibopagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_evento')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('idPersona')->constrained('personas')->onDelete('cascade');
            $table->date('fecha');
            $table->string('foto');
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recibopagos');
    }
};
