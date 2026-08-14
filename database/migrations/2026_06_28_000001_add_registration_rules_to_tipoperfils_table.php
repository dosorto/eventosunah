<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tipoperfils', function (Blueprint $table) {
            $table->string('codigo')->nullable()->after('tipoperfil');
            $table->string('tipo_identificador')->nullable()->after('codigo');
            $table->string('etiqueta_identificador')->nullable()->after('tipo_identificador');
            $table->boolean('requiere_api')->default(false)->after('etiqueta_identificador');
            $table->boolean('permite_registro_manual')->default(true)->after('requiere_api');
        });

        DB::table('tipoperfils')
            ->whereRaw('LOWER(tipoperfil) = ?', ['estudiante'])
            ->update([
                'codigo' => 'estudiante',
                'tipo_identificador' => 'numeroCuenta',
                'etiqueta_identificador' => 'Número de cuenta',
                'requiere_api' => true,
                'permite_registro_manual' => false,
            ]);

        DB::table('tipoperfils')
            ->whereRaw('LOWER(tipoperfil) = ?', ['docente'])
            ->update([
                'codigo' => 'docente',
                'tipo_identificador' => 'numeroEmpleado',
                'etiqueta_identificador' => 'Número de empleado',
                'requiere_api' => true,
                'permite_registro_manual' => false,
            ]);

        DB::table('tipoperfils')
            ->whereRaw('LOWER(tipoperfil) = ?', ['empleado'])
            ->update([
                'codigo' => 'empleado',
                'tipo_identificador' => 'numeroEmpleado',
                'etiqueta_identificador' => 'Número de empleado',
                'requiere_api' => true,
                'permite_registro_manual' => false,
            ]);

        DB::table('tipoperfils')
            ->whereRaw('LOWER(tipoperfil) = ?', ['externo'])
            ->update([
                'codigo' => 'externo',
                'tipo_identificador' => 'dni',
                'etiqueta_identificador' => 'Número de identidad',
                'requiere_api' => false,
                'permite_registro_manual' => true,
            ]);
    }

    public function down(): void
    {
        Schema::table('tipoperfils', function (Blueprint $table) {
            $table->dropColumn([
                'codigo',
                'tipo_identificador',
                'etiqueta_identificador',
                'requiere_api',
                'permite_registro_manual',
            ]);
        });
    }
};
