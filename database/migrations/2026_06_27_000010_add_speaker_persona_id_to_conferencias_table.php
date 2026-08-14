<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->unsignedBigInteger('speaker_persona_id')->nullable()->after('conferencista_nombre_invitado');
            $table->foreign('speaker_persona_id')->references('id')->on('personas')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->dropForeign(['speaker_persona_id']);
            $table->dropColumn('speaker_persona_id');
        });
    }
};
