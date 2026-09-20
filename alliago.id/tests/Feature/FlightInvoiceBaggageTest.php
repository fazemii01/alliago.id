<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FlightInvoiceBaggageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    }

    public function test_admin_can_generate_flight_invoice_with_extra_baggage(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $client = User::factory()->create();
        $paymentMethod = PaymentMethod::create([
            'name' => 'Bank Transfer Mandiri',
            'provider' => 'manual',
            'code' => 'mandiri',
            'is_active' => true,
            'configuration' => [],
        ]);

        $payload = [
            'client_id' => $client->id,
            'traveler_name' => 'Sri Prianti',
            'traveler_email' => 'sri.prianti@example.com',
            'traveler_phone' => '+628123456789',
            'payment_method_id' => $paymentMethod->id,
            'flight' => [
                'airline' => 'ZZ',
                'airline_name' => 'Virtual Airline ZZ',
                'flight_numbers' => 'ZZ-991',
                'origin' => 'CGK',
                'destination' => 'DPS',
                'depart_date' => '2026-07-15',
                'depart_time' => '10:00',
                'cabin_class' => 'economy',
                'price_value' => 2500000,
                'tax' => 150000,
                'total' => 2650000 + 1028872, // Ticket + Tax + 20kg baggage
            ],
            'baggage_weight' => 20,
            'baggage_price' => 1028872,
        ];

        $response = $this->actingAs($admin)
            ->postJson('/admin/flights/generate-invoice', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'reference_number', 'invoice_url']);

        $application = Application::first();
        $this->assertNotNull($application);
        $this->assertEquals($client->id, $application->user_id);
        $this->assertEquals('Sri Prianti', $application->traveler_name);
        
        $metadata = $application->metadata;
        $this->assertEquals(20, $metadata['flight_details']['extra_baggage_weight']);
        $this->assertEquals(1028872, $metadata['flight_details']['extra_baggage_price']);
        $this->assertEquals(2650000 + 1028872, $metadata['invoice_amount']);
    }

    public function test_admin_can_generate_flight_invoice_with_rm_currency(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $client = User::factory()->create();
        $paymentMethod = PaymentMethod::create([
            'name' => 'Bank Transfer Mandiri',
            'provider' => 'manual',
            'code' => 'mandiri_myr',
            'is_active' => true,
            'configuration' => [],
        ]);

        $payload = [
            'client_id' => $client->id,
            'traveler_name' => 'Ahmad Faiz',
            'traveler_email' => 'ahmad.faiz@example.com',
            'traveler_phone' => '+60123456789',
            'payment_method_id' => $paymentMethod->id,
            'currency' => 'RM',
            'flight' => [
                'airline' => 'ZZ',
                'airline_name' => 'Virtual Airline ZZ',
                'flight_numbers' => 'ZZ-550',
                'origin' => 'KUL',
                'destination' => 'CGK',
                'depart_date' => '2026-08-10',
                'depart_time' => '14:00',
                'cabin_class' => 'economy',
                'price_value' => 750,
                'tax' => 50,
                'total' => 800,
            ],
            'baggage_weight' => 0,
            'baggage_price' => 0,
        ];

        $response = $this->actingAs($admin)
            ->postJson('/admin/flights/generate-invoice', $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'reference_number', 'invoice_url']);

        $application = Application::where('traveler_name', 'Ahmad Faiz')->first();
        $this->assertNotNull($application);
        $this->assertEquals('MYR', $application->metadata['price_breakdown']['currency']);
        $this->assertEquals('MYR', $application->metadata['currency']);
        $this->assertEquals(800, $application->metadata['invoice_amount']);

        // Test invoice view renders RM
        $invoiceResponse = $this->actingAs($client)->get(route('client.applications.invoice', $application));
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee('RM');
    }

    public function test_admin_converts_idr_amount_to_rm_when_generating_invoice(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $client = User::factory()->create();
        $paymentMethod = PaymentMethod::create([
            'name' => 'Bank Transfer Mandiri',
            'provider' => 'manual',
            'code' => 'mandiri_conversion',
            'is_active' => true,
            'configuration' => [],
        ]);

        // Sending a 4,000,000 IDR amount with currency RM should be converted using the exchange rate
        $payload = [
            'client_id' => $client->id,
            'traveler_name' => 'Budi Santoso',
            'traveler_email' => 'budi.santoso@example.com',
            'traveler_phone' => '+628111222333',
            'payment_method_id' => $paymentMethod->id,
            'currency' => 'RM',
            'convert_to_rm' => true,
            'flight' => [
                'airline' => 'ZZ',
                'airline_name' => 'Virtual Airline ZZ',
                'flight_numbers' => 'ZZ-100',
                'origin' => 'CGK',
                'destination' => 'KUL',
                'depart_date' => '2026-09-01',
                'depart_time' => '08:00',
                'cabin_class' => 'economy',
                'price_value' => 4000000, // 4 million IDR
                'tax' => 0,
                'total' => 4000000,
            ],
            'baggage_weight' => 0,
            'baggage_price' => 0,
        ];

        $response = $this->actingAs($admin)
            ->postJson('/admin/flights/generate-invoice', $payload);

        $response->assertStatus(200);

        $application = Application::where('traveler_name', 'Budi Santoso')->first();
        $this->assertNotNull($application);
        $this->assertEquals('MYR', $application->metadata['currency']);

        $rate = \App\Models\VisaSetting::getIdrToTargetRate('MYR');
        $expectedAmount = round(4000000 / $rate); // ~1,159 RM instead of 4,000,000 RM

        $this->assertEquals($expectedAmount, $application->metadata['invoice_amount']);
        $this->assertEquals($expectedAmount, $application->metadata['price_breakdown']['total']);
        $this->assertNotEquals(4000000, $application->metadata['invoice_amount']);
    }

    public function test_converting_existing_application_currency_from_idr_to_rm(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $client = User::factory()->create();

        $application = Application::create([
            'user_id' => $client->id,
            'visa_product_id' => null,
            'reference_number' => 'FLT-TEST12345',
            'status' => 'pending_payment',
            'traveler_name' => 'John Doe',
            'traveler_email' => 'john.doe@example.com',
            'metadata' => [
                'type' => 'flight',
                'currency' => 'IDR',
                'invoice_amount' => 4000000,
                'price_breakdown' => [
                    'currency' => 'IDR',
                    'total' => 4000000,
                    'subtotal' => 4000000,
                ],
                'flight_details' => [
                    'airline' => 'ZZ',
                    'airline_name' => 'Virtual Airline ZZ',
                    'price_value' => 4000000,
                    'total' => 4000000,
                ],
            ],
        ]);

        $rate = \App\Models\VisaSetting::getIdrToTargetRate('MYR');
        $expectedConverted = round(4000000 / $rate);

        // When viewing in IDR
        $response = $this->actingAs($client)->get(route('client.applications.invoice', $application));
        $response->assertStatus(200);
        $response->assertSee('Rp 4.000.000');

        // Now update application currency to MYR with conversion (like Filament change_currency action does)
        $metadata = $application->metadata;
        $metadata['currency'] = 'MYR';
        $metadata['price_breakdown']['currency'] = 'MYR';
        $metadata['invoice_amount'] = round($metadata['invoice_amount'] / $rate);
        $metadata['price_breakdown']['total'] = $metadata['invoice_amount'];
        $application->update(['metadata' => $metadata]);

        $responseMyr = $this->actingAs($client)->get(route('client.applications.invoice', $application));
        $responseMyr->assertStatus(200);
        $responseMyr->assertSee('RM ' . number_format($expectedConverted, 0, ',', '.'));
        $responseMyr->assertDontSee('RM 4.000.000');
    }
}


