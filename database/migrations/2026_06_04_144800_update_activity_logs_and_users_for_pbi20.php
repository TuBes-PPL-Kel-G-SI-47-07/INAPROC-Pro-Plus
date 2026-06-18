<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add coordinates to users table for office geotagging (PBI-11/19)
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        // Add forensic audit trail fields to activity logs (PBI-20)
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('ip_address', 45)->nullable()->after('description');
            $table->string('table_affected')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'table_affected']);
        });
    }
};
