<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_invitaciones', function (Blueprint $table) {
            $table->timestamp('enviada_at')->nullable()->after('activa');
            $table->timestamp('correo_enviado_at')->nullable()->after('enviada_at');
            $table->timestamp('whatsapp_enviado_at')->nullable()->after('correo_enviado_at');
            $table->string('ultimo_canal_envio', 30)->nullable()->after('whatsapp_enviado_at');
        });
    }

    public function down(): void
    {
        Schema::table('evento_invitaciones', function (Blueprint $table) {
            $table->dropColumn([
                'enviada_at',
                'correo_enviado_at',
                'whatsapp_enviado_at',
                'ultimo_canal_envio',
            ]);
        });
    }
};
