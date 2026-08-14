<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->nullable()->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('requiere_api')->default(false);
            $table->string('proveedor')->nullable();
            $table->string('api_base_url')->nullable();
            $table->string('checkout_path')->nullable();
            $table->string('http_method', 10)->default('POST');
            $table->string('auth_type', 20)->default('none');
            $table->string('identifier_query_key')->nullable();
            $table->string('public_key')->nullable();
            $table->text('api_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->json('headers_json')->nullable();
            $table->json('checkout_fields_json')->nullable();
            $table->text('instrucciones')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};
