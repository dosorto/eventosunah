<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_invitaciones', function (Blueprint $table) {
            $table->string('telefono_invitado')->nullable()->after('correo_invitado');
        });
    }

    public function down(): void
    {
        Schema::table('evento_invitaciones', function (Blueprint $table) {
            $table->dropColumn('telefono_invitado');
        });
    }
};
