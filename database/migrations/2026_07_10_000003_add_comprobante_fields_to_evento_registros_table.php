<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_registros', function (Blueprint $table) {
            $table->string('comprobante_pago_path')->nullable()->after('payload_pago');
            $table->string('comprobante_pago_nombre')->nullable()->after('comprobante_pago_path');
            $table->string('comprobante_pago_mime', 120)->nullable()->after('comprobante_pago_nombre');
        });
    }

    public function down(): void
    {
        Schema::table('evento_registros', function (Blueprint $table) {
            $table->dropColumn([
                'comprobante_pago_path',
                'comprobante_pago_nombre',
                'comprobante_pago_mime',
            ]);
        });
    }
};
