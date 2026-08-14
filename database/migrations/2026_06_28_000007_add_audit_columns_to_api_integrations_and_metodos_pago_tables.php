<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_integrations', function (Blueprint $table) {
            if (! Schema::hasColumn('api_integrations', 'created_by')) {
                $table->integer('created_by')->nullable()->after('id');
            }

            if (! Schema::hasColumn('api_integrations', 'deleted_by')) {
                $table->integer('deleted_by')->nullable()->after('created_by');
            }

            if (! Schema::hasColumn('api_integrations', 'updated_by')) {
                $table->integer('updated_by')->nullable()->after('deleted_by');
            }

            if (! Schema::hasColumn('api_integrations', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('metodos_pago', function (Blueprint $table) {
            if (! Schema::hasColumn('metodos_pago', 'created_by')) {
                $table->integer('created_by')->nullable()->after('id');
            }

            if (! Schema::hasColumn('metodos_pago', 'deleted_by')) {
                $table->integer('deleted_by')->nullable()->after('created_by');
            }

            if (! Schema::hasColumn('metodos_pago', 'updated_by')) {
                $table->integer('updated_by')->nullable()->after('deleted_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('api_integrations', function (Blueprint $table) {
            if (Schema::hasColumn('api_integrations', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            $columns = array_filter([
                Schema::hasColumn('api_integrations', 'created_by') ? 'created_by' : null,
                Schema::hasColumn('api_integrations', 'deleted_by') ? 'deleted_by' : null,
                Schema::hasColumn('api_integrations', 'updated_by') ? 'updated_by' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });

        Schema::table('metodos_pago', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('metodos_pago', 'created_by') ? 'created_by' : null,
                Schema::hasColumn('metodos_pago', 'deleted_by') ? 'deleted_by' : null,
                Schema::hasColumn('metodos_pago', 'updated_by') ? 'updated_by' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
