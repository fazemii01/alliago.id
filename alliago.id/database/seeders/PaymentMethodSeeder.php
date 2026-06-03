<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\PaymentMethod::updateOrCreate(
            ['code' => 'xendit'],
            [
                'name' => 'Xendit Payment Gateway',
                'provider' => 'xendit',
                'description' => 'Pay via Virtual Account, Credit Card, or e-Wallet.',
                'is_active' => true,
            ]
        );

        \App\Models\PaymentMethod::updateOrCreate(
            ['code' => 'manual_bca'],
            [
                'name' => 'Transfer Bank BCA',
                'provider' => 'manual',
                'account_name' => 'PT Alliago Global',
                'account_number' => '1234567890',
                'description' => 'Transfer ke rekening BCA kami.',
                'is_active' => true,
            ]
        );
    }
}
