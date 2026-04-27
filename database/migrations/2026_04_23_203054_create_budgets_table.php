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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pagu'); // Contoh: Anggaran IT Dinas Pendidikan
            $table->decimal('nominal_awal', 15, 2); // Plafon awal (Misal 1 Milyar)
            $table->decimal('sisa_pagu', 15, 2);    // Sisa yang bisa dipakai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
