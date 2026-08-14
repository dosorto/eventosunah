<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metodos_pago', function (Blueprint $table) {
            $table->string('tipo', 30)->default('efectivo')->after('descripcion');
            $table->boolean('requiere_comprobante')->default(false)->after('requiere_api');
        });
    }

    public function down(): void
    {
        Schema::table('metodos_pago', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'requiere_comprobante']);
        });
    }
};
