<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_registros', function (Blueprint $table) {
            $table->foreignId('metodo_pago_id')->nullable()->after('tipoperfil_id')->constrained('metodos_pago')->nullOnDelete();
            $table->string('estado_pago')->default('no_aplica')->after('estado');
            $table->string('referencia_pago')->nullable()->after('estado_pago');
            $table->json('payload_pago')->nullable()->after('referencia_pago');
            $table->timestamp('pagado_en')->nullable()->after('payload_pago');
        });
    }

    public function down(): void
    {
        Schema::table('evento_registros', function (Blueprint $table) {
            $table->dropConstrainedForeignId('metodo_pago_id');
            $table->dropColumn(['estado_pago', 'referencia_pago', 'payload_pago', 'pagado_en']);
        });
    }
};
