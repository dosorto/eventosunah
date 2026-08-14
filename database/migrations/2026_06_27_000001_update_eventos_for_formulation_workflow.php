<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->date('fechainicio')->nullable()->change();
            $table->date('fechafinal')->nullable()->change();
            $table->time('horainicio')->nullable()->change();
            $table->time('horafin')->nullable()->change();
            $table->unsignedBigInteger('idlocalidad')->nullable()->change();
            $table->unsignedBigInteger('IdDiploma')->nullable()->change();
            $table->string('localidad_nombre')->nullable()->after('idlocalidad');
            $table->string('diploma_plantilla')->nullable()->after('IdDiploma');
            $table->string('estado')->default('formulacion')->after('diploma_plantilla');
            $table->timestamp('published_at')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn([
                'localidad_nombre',
                'diploma_plantilla',
                'estado',
                'published_at',
            ]);

            $table->date('fechainicio')->nullable(false)->change();
            $table->date('fechafinal')->nullable(false)->change();
            $table->time('horainicio')->nullable(false)->change();
            $table->time('horafin')->nullable(false)->change();
            $table->unsignedBigInteger('idlocalidad')->nullable(false)->change();
            $table->unsignedBigInteger('IdDiploma')->nullable(false)->change();
        });
    }
};
