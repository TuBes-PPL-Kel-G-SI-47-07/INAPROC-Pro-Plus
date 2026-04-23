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
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->id();
            // Menghubungkan pengajuan ini ke user yang sedang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            $table->string('item_name');         // Contoh: Laptop ASUS
            $table->integer('quantity');        // Contoh: 5
            $table->decimal('price', 15, 2);    // Harga satuan (15 digit, 2 desimal)
            $table->decimal('total_price', 15, 2); // Total (PBI-07 bakal cek ini)
            
            $table->text('description')->nullable(); // Alasan pengadaan
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_requests');
    }
};
