<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_documento_procesos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->cascadeOnDelete();
            $table->foreignId('solicitado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tipo', 80)->index();
            $table->string('estado', 30)->default('pendiente')->index();
            $table->unsignedInteger('total')->default(0);
            $table->unsignedInteger('procesados')->default(0);
            $table->unsignedInteger('generados')->default(0);
            $table->unsignedInteger('omitidos')->default(0);
            $table->unsignedInteger('tamano_lote')->default(20);
            $table->unsignedBigInteger('ultimo_cursor_id')->nullable();
            $table->json('payload')->nullable();
            $table->string('directorio_temporal')->nullable();
            $table->string('ruta_salida')->nullable();
            $table->text('mensaje_estado')->nullable();
            $table->text('error_detalle')->nullable();
            $table->timestamp('iniciado_at')->nullable();
            $table->timestamp('completado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_documento_procesos');
    }
};
