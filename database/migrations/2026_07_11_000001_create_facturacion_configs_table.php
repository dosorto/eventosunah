<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturacion_configs', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 180)->nullable();
            $table->string('rtn_emisor', 32)->nullable();
            $table->string('cai', 64)->nullable();
            $table->string('establecimiento_codigo', 8)->nullable();
            $table->string('punto_emision_codigo', 8)->nullable();
            $table->string('tipo_documento_codigo', 8)->default('01');
            $table->unsignedBigInteger('rango_inicio')->nullable();
            $table->unsignedBigInteger('rango_fin')->nullable();
            $table->unsignedBigInteger('siguiente_numero')->nullable();
            $table->date('fecha_limite_emision')->nullable();
            $table->string('direccion_fiscal', 255)->nullable();
            $table->string('telefono_fiscal', 50)->nullable();
            $table->string('correo_fiscal', 120)->nullable();
            $table->text('leyenda')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturacion_configs');
    }
};
