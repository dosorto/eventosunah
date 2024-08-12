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
        Schema::create('diplomas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('URL');
            $table->date('Fecha');
            $table->unsignedBigInteger('IdConferencia')->nullable();
            $table->unsignedBigInteger('IdEvento')->nullable();
            $table->unsignedBigInteger('IdFirma');
            $table->integer("created_by");
            $table->integer("deleted_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('IdConferencia')->references('id')->on('conferencias')->onDelete('restrict');
            $table->foreign('IdFirma')->references('id')->on('firmas')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diplomas');
    }
};