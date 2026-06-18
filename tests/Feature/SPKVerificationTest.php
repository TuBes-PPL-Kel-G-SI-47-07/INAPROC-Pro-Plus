<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\ProcurementRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SPKVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        Role::create(['name' => 'pemohon']);
        Role::create(['name' => 'vendor']);
    }

    /**
     * Test that the verification route is public and accessible without authentication.
     */
    public function test_public_can_access_verification_route(): void
    {
        $pemohon = User::factory()->create();
        $pemohon->assignRole('pemohon');

        $budget = Budget::create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $procurement = ProcurementRequest::create([
            'user_id' => $pemohon->id,
            'budget_id' => $budget->id,
            'item_name' => 'Laptop Test',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'pending',
        ]);

        // Access public verification route
        $response = $this->get(route('procurement.verify_spk', $procurement->uuid));

        $response->assertStatus(200);
        // Because status is pending, it should say invalid
        $response->assertSee('DOKUMEN TIDAK VALID');
    }

    /**
     * Test that a valid approved SPK shows the success label and tender details.
     */
    public function test_valid_spk_shows_success_message_and_details(): void
    {
        $pemohon = User::factory()->create();
        $pemohon->assignRole('pemohon');

        $vendor = User::factory()->create(['name' => 'Mitra Vendor Sinergi']);
        $vendor->assignRole('vendor');

        $budget = Budget::create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $procurement = ProcurementRequest::create([
            'user_id' => $pemohon->id,
            'budget_id' => $budget->id,
            'item_name' => 'Laptop Test Premium',
            'quantity' => 2,
            'price' => 4500000,
            'total_price' => 9000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        // Access route
        $response = $this->get(route('procurement.verify_spk', $procurement->uuid));

        $response->assertStatus(200);
        $response->assertSee('DOKUMEN VALID & TERVERIFIKASI DIGITAL', false);
        $response->assertSee('Laptop Test Premium');
        $response->assertSee('Mitra Vendor Sinergi');
        $response->assertSee('Rp 9.000.000');
    }

    /**
     * Test that an invalid SPK shows the error message.
     */
    public function test_invalid_spk_shows_error_message(): void
    {
        // Random UUID that does not exist
        $response = $this->get(route('procurement.verify_spk', 'non-existent-uuid'));

        $response->assertStatus(200);
        $response->assertSee('DOKUMEN TIDAK VALID');
    }
}
