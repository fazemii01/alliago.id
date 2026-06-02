<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\VisaProduct;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure roles exist
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
    }

    /**
     * Test that an activity is logged when an admin creates a model.
     */
    public function test_activity_is_logged_when_admin_creates_model(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        // Let's create a country and a visa product
        $country = Country::create([
            'name' => 'Japan',
            'code' => 'JP',
            'flag_emoji' => '🇯🇵',
            'is_active' => true,
        ]);

        $visaProduct = VisaProduct::create([
            'country_id' => $country->id,
            'name' => 'Tourist Visa',
            'type' => 'Single Entry',
            'promo_label' => 'Hot',
            'processing_time' => '5 days',
            'stay_duration' => '90 days',
            'validity' => '3 months',
            'base_price' => 150.00,
            'discount_price' => 120.00,
            'short_description' => 'Tourist visa for Japan',
            'description' => 'Tourist visa for Japan',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Assert that logs were created
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'create',
            'model_type' => Country::class,
            'model_id' => (string) $country->id,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'create',
            'model_type' => VisaProduct::class,
            'model_id' => (string) $visaProduct->id,
        ]);
    }

    /**
     * Test that an activity is logged when an admin updates a model, and that sensitive properties are redacted.
     */
    public function test_activity_is_logged_on_update_with_sensitive_redaction(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        // Create a user to update (audited model)
        $targetUser = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123456789',
            'password' => bcrypt('old-password'),
        ]);

        // Clear create logs to simplify update testing
        ActivityLog::truncate();

        // Update target user
        $targetUser->update([
            'name' => 'John Updated',
            'password' => bcrypt('new-password'),
        ]);

        // Assert update log exists
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => 'update',
            'model_type' => User::class,
            'model_id' => (string) $targetUser->id,
        ]);

        $log = ActivityLog::where('model_type', User::class)
            ->where('model_id', $targetUser->id)
            ->where('action', 'update')
            ->first();

        $this->assertNotNull($log);
        $properties = $log->properties;

        // Verify password redaction
        $this->assertArrayHasKey('old', $properties);
        $this->assertArrayHasKey('new', $properties);
        
        $this->assertEquals('[REDACTED]', $properties['old']['password']);
        $this->assertEquals('[REDACTED]', $properties['new']['password']);
        $this->assertEquals('John Doe', $properties['old']['name']);
        $this->assertEquals('John Updated', $properties['new']['name']);
    }

    /**
     * Test that no activity is logged when a regular customer/user performs actions.
     */
    public function test_no_activity_is_logged_for_regular_users(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($customer);

        // Regular user creating a country (in a real scenario, policies might block this, but let's test the trigger directly)
        $country = Country::create([
            'name' => 'Germany',
            'code' => 'DE',
            'flag_emoji' => '🇩🇪',
            'is_active' => true,
        ]);

        // Assert that no activity log was created because user is not admin/staff
        $this->assertDatabaseMissing('activity_logs', [
            'model_type' => Country::class,
            'model_id' => (string) $country->id,
        ]);
    }
}
