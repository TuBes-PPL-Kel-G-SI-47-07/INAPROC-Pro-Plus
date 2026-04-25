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
            $table->decimal('score_harga', 5, 2)->nullable();
            $table->decimal('score_teknis', 5, 2)->nullable();
            $table->decimal('score_integritas', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            //
        });
    }
};
