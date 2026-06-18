<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuditorAnalyticsTest extends TestCase
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

    public function test_auditor_can_access_analytics_dashboard()
    {
        /** @var \App\Models\User $auditor */
        $auditor = User::factory()->create();
        $auditor->assignRole('auditor');

        $response = $this->actingAs($auditor)->get(route('auditor.analytics'));

        $response->assertStatus(200);
        $response->assertSee('EXECUTIVE AUDIT PORTAL');
        $response->assertSee('Immutable System Audit Trail');
    }

    public function test_unauthorized_user_cannot_access_analytics_dashboard()
    {
        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $response = $this->actingAs($vendor)->get(route('auditor.analytics'));

        $response->assertStatus(403);
    }

    public function test_vendor_can_save_geotagging_coordinates()
    {
        /** @var \App\Models\User $vendor */
        $vendor = User::factory()->create();
        $vendor->assignRole('vendor');

        $response = $this->actingAs($vendor)->patch(route('profile.update'), [
            'name' => 'Vendor Test Corp',
            'email' => $vendor->email,
            'phone_number' => '08123456789',
            'address' => 'Gedung Cyber, Jakarta',
            'latitude' => -6.2234,
            'longitude' => 106.8123,
        ]);

        $response->assertRedirect();
        
        $vendor->refresh();
        $this->assertEquals(-6.2234, (float) $vendor->latitude);
        $this->assertEquals(106.8123, (float) $vendor->longitude);
    }

    public function test_activity_log_records_ip_address_and_table()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        // Simulate an request-driven activity log creation
        $log = ActivityLog::query()->create([
            'user_id' => $user->id,
            'action' => 'Test Action',
            'description' => 'Tested forensic logging helper',
            'table_affected' => 'users',
        ]);

        $this->assertNotNull($log->ip_address);
        $this->assertEquals('users', $log->table_affected);
    }
}
