<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencistas', function (Blueprint $table) {
            $table->string('nivel_academico')->nullable()->after('titulo');
        });
    }

    public function down(): void
    {
        Schema::table('conferencistas', function (Blueprint $table) {
            $table->dropColumn('nivel_academico');
        });
    }
};
