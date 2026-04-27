<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);

        // 2. Buat akun Admin
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // 3. Buat akun Vendor
        $vendor = User::create([
            'name' => 'Vendor Berkah',
            'email' => 'vendor@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $vendor->assignRole('vendor');

        // 4. Buat akun Auditor
        $auditor = User::create([
            'name' => 'Auditor Senior',
            'email' => 'auditor@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $auditor->assignRole('auditor');

        // 5. Buat akun Pemohon
        $pemohon = User::create([
            'name' => 'Pemohon Proyek',
            'email' => 'pemohon@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $pemohon->assignRole('pemohon');

        // 6. Jalankan BudgetSeeder (Jika sudah ada)
        if (class_exists(BudgetSeeder::class)) {
            $this->call(BudgetSeeder::class);
        }
    }
}
