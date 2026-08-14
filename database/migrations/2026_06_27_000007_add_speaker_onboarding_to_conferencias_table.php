<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->string('speaker_access_token')->nullable()->unique()->after('conferencista_nombre_invitado');
            $table->timestamp('speaker_profile_completed_at')->nullable()->after('speaker_access_token');
            $table->timestamp('conference_content_completed_at')->nullable()->after('speaker_profile_completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->dropColumn([
                'speaker_access_token',
                'speaker_profile_completed_at',
                'conference_content_completed_at',
            ]);
        });
    }
};
