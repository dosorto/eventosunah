<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencias', function (Blueprint $table): void {
            if (! Schema::hasColumn('conferencias', 'codigo_auto_asistencia')) {
                $table->string('codigo_auto_asistencia', 5)->nullable()->unique()->after('speaker_onboarding_submitted_at');
            }

            if (! Schema::hasColumn('conferencias', 'codigo_auto_asistencia_generado_en')) {
                $table->timestamp('codigo_auto_asistencia_generado_en')->nullable()->after('codigo_auto_asistencia');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table): void {
            if (Schema::hasColumn('conferencias', 'codigo_auto_asistencia_generado_en')) {
                $table->dropColumn('codigo_auto_asistencia_generado_en');
            }

            if (Schema::hasColumn('conferencias', 'codigo_auto_asistencia')) {
                $table->dropUnique('conferencias_codigo_auto_asistencia_unique');
                $table->dropColumn('codigo_auto_asistencia');
            }
        });
    }
};
