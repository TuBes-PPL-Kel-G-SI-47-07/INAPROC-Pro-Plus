<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\ProcurementRequest;
use App\Models\User;
use App\Models\ProjectProgress;
use App\Models\BastSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BastSubmissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::query()->firstOrCreate(['name' => 'vendor', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'auditor', 'guard_name' => 'web']);
        Role::query()->firstOrCreate(['name' => 'pemohon', 'guard_name' => 'web']);
    }

    public function test_vendor_cannot_upload_bast_if_progress_below_100()
    {
        Storage::fake('public');

        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $budget = Budget::query()->create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::query()->create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project BAST Test',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        // Progress is only 50%
        ProjectProgress::query()->create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'percentage' => 50,
            'description' => 'Halfway done',
            'photo_path' => 'progress_photos/test.jpg',
            'status' => 'approved',
        ]);

        $fakeDoc = UploadedFile::fake()->create('bast.pdf', 500);

        $response = $this->actingAs($vendor)->post(route('bast.store'), [
            'procurement_request_id' => $project->id,
            'bast_file' => $fakeDoc,
            'description' => 'Penyerahan hasil pengerjaan.',
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('bast_submissions', [
            'procurement_request_id' => $project->id,
        ]);
    }

    public function test_vendor_can_upload_bast_when_progress_reaches_100()
    {
        Storage::fake('public');

        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $budget = Budget::query()->create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::query()->create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project BAST Test',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        // Approved progress reaches 100%
        ProjectProgress::query()->create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'percentage' => 100,
            'description' => 'Fully completed',
            'photo_path' => 'progress_photos/test.jpg',
            'status' => 'approved',
        ]);

        $fakeDoc = UploadedFile::fake()->create('bast.pdf', 500);

        $response = $this->actingAs($vendor)->post(route('bast.store'), [
            'procurement_request_id' => $project->id,
            'bast_file' => $fakeDoc,
            'description' => 'Penyerahan hasil pengerjaan akhir.',
        ]);

        $response->assertRedirect(route('progress.show', $project->id));
        
        $this->assertDatabaseHas('bast_submissions', [
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'status' => 'pending',
        ]);

        $bast = BastSubmission::query()->first();
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($bast->file_path);
    }

    public function test_auditor_can_approve_or_reject_bast()
    {
        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        /** @var \App\Models\User $auditor */
        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        $budget = Budget::query()->create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::query()->create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project BAST Test',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        $bast = BastSubmission::query()->create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'file_path' => 'bast_documents/bast.pdf',
            'description' => 'Selesai serah terima',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($auditor)->post(route('bast.verify', $bast->id), [
            'status' => 'approved',
            'auditor_notes' => 'Pekerjaan fisik telah diperiksa secara menyeluruh dan BAST disetujui.',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('bast_submissions', [
            'id' => $bast->id,
            'status' => 'approved',
            'auditor_notes' => 'Pekerjaan fisik telah diperiksa secara menyeluruh dan BAST disetujui.',
        ]);
    }

    public function test_pemohon_can_verify_bast_after_auditor_approval()
    {
        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        /** @var \App\Models\User $pemohon */
        $pemohon = User::factory()->create();
        $pemohon->assignRole('pemohon');

        $budget = Budget::query()->create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::query()->create([
            'user_id' => $pemohon->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project BAST Test',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        $bast = BastSubmission::query()->create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'file_path' => 'bast_documents/bast.pdf',
            'description' => 'Selesai serah terima',
            'status' => 'approved',
            'pemohon_status' => 'pending',
        ]);

        $response = $this->actingAs($pemohon)->post(route('bast.verify_pemohon', $bast->id), [
            'status' => 'approved',
            'pemohon_notes' => 'Barang telah diterima sesuai dengan spesifikasi dan dalam kondisi baik.',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('bast_submissions', [
            'id' => $bast->id,
            'pemohon_status' => 'approved',
            'pemohon_notes' => 'Barang telah diterima sesuai dengan spesifikasi dan dalam kondisi baik.',
        ]);

        $project->refresh();
        $this->assertEquals('completed', $project->status);
    }

    public function test_unauthorized_user_cannot_verify_as_pemohon()
    {
        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        /** @var \App\Models\User $pemohon */
        $pemohon = User::factory()->create();
        $pemohon->assignRole('pemohon');

        /** @var \App\Models\User $otherUser */
        $otherUser = User::factory()->create();
        $otherUser->assignRole('pemohon');

        $budget = Budget::query()->create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::query()->create([
            'user_id' => $pemohon->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project BAST Test',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        $bast = BastSubmission::query()->create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'file_path' => 'bast_documents/bast.pdf',
            'description' => 'Selesai serah terima',
            'status' => 'approved',
            'pemohon_status' => 'pending',
        ]);

        $response = $this->actingAs($otherUser)->post(route('bast.verify_pemohon', $bast->id), [
            'status' => 'approved',
            'pemohon_notes' => 'Mencoba menyetujui.',
        ]);

        $response->assertStatus(403);
        
        $bast->refresh();
        $this->assertEquals('pending', $bast->pemohon_status);
    }
}
