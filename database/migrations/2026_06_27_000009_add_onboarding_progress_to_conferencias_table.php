<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->unsignedTinyInteger('speaker_onboarding_step')->default(1)->after('conference_content_completed_at');
            $table->timestamp('speaker_onboarding_submitted_at')->nullable()->after('speaker_onboarding_step');
        });
    }

    public function down(): void
    {
        Schema::table('conferencias', function (Blueprint $table) {
            $table->dropColumn([
                'speaker_onboarding_step',
                'speaker_onboarding_submitted_at',
            ]);
        });
    }
};
