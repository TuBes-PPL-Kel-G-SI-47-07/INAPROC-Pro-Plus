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
        Schema::table('bids', function (Blueprint $table) {
            $table->dropForeign(['tender_config_id']);
            $table->dropColumn('tender_config_id');
            $table->foreignId('tender_id')->nullable()->constrained('tenders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropForeign(['tender_id']);
            $table->dropColumn('tender_id');
            $table->foreignId('tender_config_id')->nullable()->constrained('tender_configs')->cascadeOnDelete();
        });
    }
};
