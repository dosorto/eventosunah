<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('diploma_page_size')->nullable()->after('diploma_plantilla');
            $table->string('diploma_orientation')->nullable()->after('diploma_page_size');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn([
                'diploma_page_size',
                'diploma_orientation',
            ]);
        });
    }
};
