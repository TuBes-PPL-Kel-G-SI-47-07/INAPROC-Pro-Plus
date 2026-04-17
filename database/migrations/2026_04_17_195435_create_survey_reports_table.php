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
        Schema::create('survey_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Vendor yang disurvey
            $table->foreignId('surveyor_id')->constrained('users'); // Petugas yang melakukan survey
            $table->string('office_condition'); // Layak / Tidak Layak
            $table->integer('infrastructure_score'); // Skor 1-100
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->string('survey_photo'); // Bukti foto saat survey (Real-time)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_reports');
    }
};
