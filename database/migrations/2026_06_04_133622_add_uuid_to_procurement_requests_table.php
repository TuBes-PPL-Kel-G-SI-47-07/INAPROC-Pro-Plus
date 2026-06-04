<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Generate UUIDs for any existing procurement requests
        $requests = Illuminate\Support\Facades\DB::table('procurement_requests')->get();
        foreach ($requests as $request) {
            if (empty($request->uuid)) {
                Illuminate\Support\Facades\DB::table('procurement_requests')
                    ->where('id', $request->id)
                    ->update(['uuid' => (string) Str::uuid()]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
