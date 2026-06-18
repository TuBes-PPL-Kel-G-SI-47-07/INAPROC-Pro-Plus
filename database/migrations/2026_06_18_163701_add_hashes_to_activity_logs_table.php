<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'previous_hash')) {
                $table->string('previous_hash')->nullable()->after('description');
            }
            if (!Schema::hasColumn('activity_logs', 'current_hash')) {
                $table->string('current_hash')->nullable()->after('previous_hash');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (Schema::hasColumn('activity_logs', 'previous_hash')) {
                $table->dropColumn('previous_hash');
            }
            if (Schema::hasColumn('activity_logs', 'current_hash')) {
                $table->dropColumn('current_hash');
            }
        });
    }
};
