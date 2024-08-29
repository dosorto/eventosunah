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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_conferencia')->constrained('conferencias')->onDelete('cascade');
            $table->foreignId('idPersona')->constrained('personas')->onDelete('cascade');
            $table->decimal('monto', 15, 2);
            $table->date('fecha_pago');
            $table->string('descripcion')->nullable();
            $table->string('estado')->default('pendiente'); 
            $table->string('tarjeta'); 
            $table->string('transaccion_id')->nullable()->unique(); 
            $table->string('cvv');
            $table->text('fechaExpiracion'); 
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
        Schema::dropIfExists('pagos');
    }
};
