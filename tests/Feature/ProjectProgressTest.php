<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\ProcurementRequest;
use App\Models\User;
use App\Models\ProjectProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProjectProgressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles if they do not exist
        Role::findOrCreate('vendor');
        Role::findOrCreate('auditor');
        Role::findOrCreate('pemohon');
    }

    public function test_vendor_can_view_only_their_won_projects()
    {
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $otherVendor = User::factory()->create();
        $otherVendor->assignRole('vendor');

        $budget = Budget::create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        // Won project for this vendor
        $project1 = ProcurementRequest::create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project 1',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        // Project won by another vendor
        $project2 = ProcurementRequest::create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project 2',
            'quantity' => 1,
            'price' => 3000000,
            'total_price' => 3000000,
            'status' => 'approved',
            'vendor_id' => $otherVendor->id,
        ]);

        $response = $this->actingAs($vendor)->get(route('progress.index'));
        $response->assertStatus(200);
        $response->assertSee('Project 1');
        $response->assertDontSee('Project 2');
    }

    public function test_vendor_cannot_view_other_vendors_project_details()
    {
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $otherVendor = User::factory()->create();
        $otherVendor->assignRole('vendor');

        $budget = Budget::create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project 2',
            'quantity' => 1,
            'price' => 3000000,
            'total_price' => 3000000,
            'status' => 'approved',
            'vendor_id' => $otherVendor->id,
        ]);

        $response = $this->actingAs($vendor)->get(route('progress.show', $project->id));
        $response->assertStatus(403);
    }

    public function test_vendor_upload_progress_without_exif_flags_anomaly()
    {
        Storage::fake('public');

        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $budget = Budget::create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project 1',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        // Upload fake image (has no EXIF GPS metadata)
        $fakeImage = UploadedFile::fake()->image('progress.jpg');

        $response = $this->actingAs($vendor)->post(route('progress.store'), [
            'procurement_request_id' => $project->id,
            'percentage' => 30,
            'description' => 'Mulai pengerjaan struktur pondasi.',
            'progress_photo' => $fakeImage,
        ]);

        $response->assertRedirect(route('progress.show', $project->id));
        
        $this->assertDatabaseHas('project_progresses', [
            'procurement_request_id' => $project->id,
            'percentage' => 30,
            'status' => 'anomaly', // Flagged as anomaly due to missing EXIF metadata
            'latitude' => null,
            'longitude' => null,
        ]);

        // File is stored on disk
        $progress = ProjectProgress::first();
        Storage::disk('public')->assertExists($progress->photo_path);
    }

    public function test_auditor_can_verify_progress()
    {
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        $budget = Budget::create([
            'nama_pagu' => 'Pagu Test',
            'nominal_awal' => 10000000,
            'sisa_pagu' => 10000000,
        ]);

        $project = ProcurementRequest::create([
            'user_id' => $vendor->id,
            'budget_id' => $budget->id,
            'item_name' => 'Project 1',
            'quantity' => 1,
            'price' => 5000000,
            'total_price' => 5000000,
            'status' => 'approved',
            'vendor_id' => $vendor->id,
        ]);

        $progress = ProjectProgress::create([
            'procurement_request_id' => $project->id,
            'vendor_id' => $vendor->id,
            'percentage' => 50,
            'description' => 'Progres 50 persen pengerjaan.',
            'photo_path' => 'progress_photos/test.jpg',
            'status' => 'anomaly',
        ]);

        $response = $this->actingAs($auditor)->post(route('progress.verify', $progress->id), [
            'status' => 'approved',
            'auditor_notes' => 'Telah dikonfirmasi secara visual dan lokasi pengerjaan sesuai.',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('project_progresses', [
            'id' => $progress->id,
            'status' => 'approved',
            'auditor_notes' => 'Telah dikonfirmasi secara visual dan lokasi pengerjaan sesuai.',
        ]);
    }
}
