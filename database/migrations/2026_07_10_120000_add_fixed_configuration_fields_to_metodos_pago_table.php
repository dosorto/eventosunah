<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metodos_pago', function (Blueprint $table) {
            $table->string('documento_cobro_tipo', 20)->nullable()->after('requiere_comprobante');
            $table->string('banco_nombre')->nullable()->after('documento_cobro_tipo');
            $table->string('numero_cuenta')->nullable()->after('banco_nombre');
            $table->string('titular_cuenta')->nullable()->after('numero_cuenta');
            $table->text('detalle_transferencia_internacional')->nullable()->after('titular_cuenta');
        });
    }

    public function down(): void
    {
        Schema::table('metodos_pago', function (Blueprint $table) {
            $table->dropColumn([
                'documento_cobro_tipo',
                'banco_nombre',
                'numero_cuenta',
                'titular_cuenta',
                'detalle_transferencia_internacional',
            ]);
        });
    }
};
