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
        Schema::create('tender_configs', function (Blueprint $table) {
            $table->id();
            $table->string('judul_tender');
            // Bobot dalam persen (Total harus 100)
            $table->integer('weight_harga')->default(40);
            $table->integer('weight_teknis')->default(40);
            $table->integer('weight_integritas')->default(20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tender_configs');
    }
};
