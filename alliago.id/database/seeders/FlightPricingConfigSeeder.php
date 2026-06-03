<?php

namespace Database\Seeders;

use App\Models\FlightPricingConfig;
use Illuminate\Database\Seeder;

class FlightPricingConfigSeeder extends Seeder
{
    public function run(): void
    {
        FlightPricingConfig::updateOrCreate(
            ['id' => 1],
            [
                'label' => 'Default',
                'addon_cost' => 0,
                'service_fee' => 500000,
                'notes' => 'Service fee set to Rp 500.000 to price above Traveloka average.',
            ]
        );
    }
}
