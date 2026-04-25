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
        $table->foreignId('tender_config_id')->constrained('tender_configs');
        $table->foreignId('user_id')->constrained('users'); // ID Vendor
        $table->text('encrypted_price'); // Harga yang sudah di-hash/enkrip
        $table->string('hash_key'); // Kunci unik untuk validasi nantinya
        $table->enum('status', ['sealed', 'opened'])->default('sealed');
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
