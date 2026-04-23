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
        Schema::table('procurement_requests', function (Blueprint $table) {
            // Menghubungkan pengadaan dengan tabel budget
            $table->foreignId('budget_id')->constrained('budgets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
        $table->dropForeign(['budget_id']);
        $table->dropColumn('budget_id');
        });
    }
};
