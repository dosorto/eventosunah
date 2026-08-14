<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('primer_nombre')->nullable()->after('dni');
            $table->string('segundo_nombre')->nullable()->after('primer_nombre');
            $table->string('primer_apellido')->nullable()->after('segundo_nombre');
            $table->string('segundo_apellido')->nullable()->after('primer_apellido');
        });

        DB::table('personas')
            ->select('id', 'nombre', 'apellido')
            ->orderBy('id')
            ->chunkById(200, function ($personas) {
                foreach ($personas as $persona) {
                    [$primerNombre, $segundoNombre] = $this->splitNameParts($persona->nombre ?? '');
                    [$primerApellido, $segundoApellido] = $this->splitNameParts($persona->apellido ?? '');

                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update([
                            'primer_nombre' => $primerNombre ?: null,
                            'segundo_nombre' => $segundoNombre ?: null,
                            'primer_apellido' => $primerApellido ?: null,
                            'segundo_apellido' => $segundoApellido ?: null,
                        ]);
                }
            });

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellido']);
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->string('nombre')->nullable()->after('dni');
            $table->string('apellido')->nullable()->after('nombre');
        });

        DB::table('personas')
            ->select('id', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido')
            ->orderBy('id')
            ->chunkById(200, function ($personas) {
                foreach ($personas as $persona) {
                    DB::table('personas')
                        ->where('id', $persona->id)
                        ->update([
                            'nombre' => $this->joinNameParts($persona->primer_nombre, $persona->segundo_nombre) ?: null,
                            'apellido' => $this->joinNameParts($persona->primer_apellido, $persona->segundo_apellido) ?: null,
                        ]);
                }
            });

        Schema::table('personas', function (Blueprint $table) {
            $table->dropColumn(['primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido']);
        });
    }

    private function splitNameParts(?string $value): array
    {
        $parts = preg_split('/\s+/', trim((string) $value), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $first = array_shift($parts) ?: '';

        return [$first, implode(' ', $parts)];
    }

    private function joinNameParts(?string $first, ?string $second): string
    {
        return trim(implode(' ', array_filter([
            trim((string) $first),
            trim((string) $second),
        ])));
    }
};
