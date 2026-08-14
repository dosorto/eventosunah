<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_certificados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('persona_id');
            $table->unsignedBigInteger('evento_registro_id')->nullable();
            $table->unsignedBigInteger('conferencia_id')->nullable();
            $table->string('tipo', 40);
            $table->string('nombre_certificado');
            $table->string('hash_unico', 80)->unique();
            $table->string('pdf_path');
            $table->timestamp('generated_at')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['evento_id', 'tipo']);
            $table->index(['persona_id', 'tipo']);
            $table->index(['conferencia_id', 'tipo']);

            $table->foreign('evento_id')->references('id')->on('eventos')->cascadeOnDelete();
            $table->foreign('persona_id')->references('id')->on('personas')->restrictOnDelete();
            $table->foreign('evento_registro_id')->references('id')->on('evento_registros')->cascadeOnDelete();
            $table->foreign('conferencia_id')->references('id')->on('conferencias')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_certificados');
    }
};
