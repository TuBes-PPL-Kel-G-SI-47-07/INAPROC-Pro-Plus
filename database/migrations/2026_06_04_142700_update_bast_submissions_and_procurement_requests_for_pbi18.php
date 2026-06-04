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
        Schema::table('bast_submissions', function (Blueprint $table) {
            $table->string('pemohon_status')->default('pending')->after('status');
            $table->text('pemohon_notes')->nullable()->after('auditor_notes');
        });

        // Convert status enum to string in procurement_requests to support 'completed' status
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bast_submissions', function (Blueprint $table) {
            $table->dropColumn(['pemohon_status', 'pemohon_notes']);
        });

        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }
};
