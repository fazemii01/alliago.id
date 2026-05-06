<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\VisaProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('outbound_visa_services_seed.csv');
        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: {$csvFile}");
            return;
        }

        $csv = array_map('str_getcsv', file($csvFile));
        $headers = array_shift($csv);

        foreach ($csv as $row) {
            if (count($row) < 6) {
                continue;
            }

            // sort_order,slug,category,jurisdiction,service_name,published_price,currency,partner_price,partner_price_ppn,source_period,source_title,source_file,source_page
            $sortOrder = $row[0];
            $slug = $row[1];
            $category = $row[2];
            $jurisdiction = $row[3];
            $serviceName = $row[4];
            $publishedPrice = (float) str_replace(',', '', $row[5]);
            
            $countryName = $this->extractCountry($serviceName, $category);
            
            $country = Country::firstOrCreate(
                ['name' => $countryName],
                ['is_active' => true, 'slug' => Str::slug($countryName)]
            );

            // determine type
            $type = 'Regular';
            if (str_contains(strtolower($serviceName), 'express')) {
                $type = 'Express';
            }
            if (str_contains(strtolower($serviceName), 'super express')) {
                $type = 'Super Express';
            }

            VisaProduct::updateOrCreate(
                ['slug' => $slug],
                [
                    'country_id' => $country->id,
                    'name' => $serviceName,
                    'type' => $type,
                    'base_price' => $publishedPrice,
                    'short_description' => trim($category . ' - ' . $jurisdiction, ' -'),
                    'is_active' => true,
                    'sort_order' => (int) $sortOrder,
                ]
            );
        }

        $this->command->info("Visa data seeded successfully.");
    }

    private function extractCountry($name, $category) {
        if (str_contains($name, 'Schengen')) {
            $words = explode(' ', $name);
            if ($words[0] === 'Schengen' && isset($words[1])) {
                return $words[1];
            }
        }
        if (str_contains($name, 'New Zealand')) return 'New Zealand';
        if (str_contains($name, 'United Kingdom')) return 'United Kingdom';
        if (str_contains($name, 'USA') || str_contains($name, 'United States')) return 'United States';
        if (str_contains($name, 'South Africa')) return 'South Africa';
        if (str_contains($name, 'South Korea') || str_contains($name, 'Korea')) return 'South Korea';
        if (str_contains($name, 'Saudi Arabia')) return 'Saudi Arabia';
        if (str_contains($name, 'Papua New Guinea')) return 'Papua New Guinea';
        if (str_contains($name, 'Czech Republic')) return 'Czech Republic';
        if (str_contains($name, 'United Arab Emirates')) return 'United Arab Emirates';
        if (str_contains($name, 'APEC')) return 'APEC';
        
        if (str_contains(strtolower($category), 'passport')) return 'Indonesia';
        if (str_contains(strtolower($category), 'add-on')) return 'Global/Add-on';
        
        $first = explode(' ', $name)[0];
        return $first ?: 'Unknown';
    }
}
