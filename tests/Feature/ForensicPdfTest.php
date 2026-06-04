<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Budget;
use App\Models\ProcurementRequest;
use App\Models\Tender;
use App\Models\Bid;
use App\Models\BastSubmission;
use App\Models\ProjectProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ForensicPdfTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'vendor', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'pemohon', 'guard_name' => 'web']);
    }

    public function test_auditor_can_export_forensic_pdf()
    {
        Storage::fake('public');

        /** @var \App\Models\User $auditor */
        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $budget = Budget::query()->create([
            'nama_pagu' => 'Pagu Test 2026',
            'nominal_awal' => 50000000,
            'sisa_pagu' => 45000000,
        ]);

        $project = ProcurementRequest::query()->create([
            'user_id' => $auditor->id, // created by requester
            'budget_id' => $budget->id,
            'item_name' => 'Pengadaan Laptop Audit',
            'quantity' => 2,
            'price' => 5000000,
            'total_price' => 10000000,
            'status' => 'completed',
            'vendor_id' => $vendor->id,
        ]);

        $tender = Tender::query()->create([
            'procurement_request_id' => $project->id,
            'title' => 'Tender Laptop Audit',
            'description' => 'Tender laptop kantor',
            'status' => 'completed',
        ]);

        $bid = Bid::query()->create([
            'tender_id' => $tender->id,
            'user_id' => $vendor->id,
            'encrypted_price' => \Illuminate\Support\Facades\Crypt::encryptString('9800000'),
            'status' => 'winner',
            'score_harga' => 100.0,
            'score_teknis' => 95.0,
            'score_integritas' => 98.0,
            'final_score' => 97.8,
        ]);

        // Create a progress item with photo
        $fakePhoto = UploadedFile::fake()->image('progress.jpg');
        $path = $fakePhoto->store('progress_photos', 'public');

        ProjectProgress::query()->create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'percentage' => 100,
            'description' => 'Pekerjaan selesai 100 persen.',
            'photo_path' => $path,
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'status' => 'approved',
        ]);

        BastSubmission::query()->create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'file_path' => 'bast_documents/bast.pdf',
            'description' => 'Selesai serah terima',
            'status' => 'approved',
            'pemohon_status' => 'approved',
            'pemohon_notes' => 'Barang lengkap',
            'auditor_notes' => 'Fisik oke',
        ]);

        $response = $this->actingAs($auditor)->get(route('procurement.forensic-pdf', $project->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition');
    }

    public function test_unauthorized_user_cannot_export_forensic_pdf()
    {
        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $budget = Budget::query()->create([
            'nama_pagu' => 'Pagu Test 2026',
            'nominal_awal' => 50000000,
            'sisa_pagu' => 45000000,
        ]);

        $project = ProcurementRequest::query()->create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Pengadaan Laptop Audit',
            'quantity' => 2,
            'price' => 5000000,
            'total_price' => 10000000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($vendor)->get(route('procurement.forensic-pdf', $project->id));

        $response->assertStatus(403);
    }
}
