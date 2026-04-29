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
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_config_id')->constrained('tender_configs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('encrypted_price');
            $table->string('hash_key')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('score_harga', 8, 2)->nullable();
            $table->decimal('score_teknis', 8, 2)->nullable();
            $table->decimal('score_integritas', 8, 2)->nullable();
            $table->decimal('final_score', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bids');
    }
};
