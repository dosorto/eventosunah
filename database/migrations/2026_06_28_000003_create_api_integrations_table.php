<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_integrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipoperfil_id');
            $table->string('nombre');
            $table->string('base_url');
            $table->string('lookup_path');
            $table->string('http_method')->default('GET');
            $table->string('auth_type')->default('none');
            $table->text('auth_token')->nullable();
            $table->string('identifier_query_key')->nullable();
            $table->unsignedInteger('timeout_seconds')->default(10);
            $table->string('response_path')->nullable();
            $table->json('headers_json')->nullable();
            $table->json('field_map')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('tipoperfil_id')->references('id')->on('tipoperfils')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_integrations');
    }
};
