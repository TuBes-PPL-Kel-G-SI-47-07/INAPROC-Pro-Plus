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
    Schema::table('users', function (Blueprint $table) {
        $table->string('phone_number')->nullable();
        $table->text('address')->nullable();
        $table->string('profile_picture')->nullable();
        $table->string('position')->nullable(); 
        $table->timestamp('last_login_at')->nullable(); 
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Jangan lupa tambahkan ini untuk rollback jika terjadi error
        $table->dropColumn(['phone_number', 'address', 'profile_picture', 'position', 'last_login_at']);
    });
}
};