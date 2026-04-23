<?php

namespace Database\Seeders;

use App\Models\Budget;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        Budget::create([
            'nama_pagu' => 'Pagu IT Dinas Kesehatan 2026',
            'nominal_awal' => 500000000, // 500 Juta
            'sisa_pagu' => 500000000,
        ]);

        Budget::create([
            'nama_pagu' => 'Pagu Infrastruktur Desa Sompok',
            'nominal_awal' => 200000000, // 200 Juta
            'sisa_pagu' => 200000000,
        ]);
    }
}