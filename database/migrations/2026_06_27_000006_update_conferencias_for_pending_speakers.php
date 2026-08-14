<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->string('conferencista_nombre_invitado')->nullable()->after('linkreunion');
            $table->unsignedBigInteger('idConferencista')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->dropColumn('conferencista_nombre_invitado');
            $table->unsignedBigInteger('idConferencista')->nullable(false)->change();
        });
    }
};
