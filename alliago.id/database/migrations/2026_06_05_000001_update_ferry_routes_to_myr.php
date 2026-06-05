<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update route 1 (Pidi -> Dumai) to (Port Dickson -> Dumai) with price 265 (MYR)
        DB::table('ferry_routes')
            ->where('id', 1)
            ->orWhere(function($query) {
                $query->where('origin', 'Pidi')->where('destination', 'Dumai');
            })
            ->update([
                'origin' => 'Port Dickson',
                'destination' => 'Dumai',
                'price' => 265,
                'updated_at' => now(),
            ]);

        // Update route 2 (Pidi -> Tanjung Balai) to (Port Dickson -> Tanjung Balai) with price 265 (MYR)
        DB::table('ferry_routes')
            ->where('id', 2)
            ->orWhere(function($query) {
                $query->where('origin', 'Pidi')->where('destination', 'Tanjung Balai');
            })
            ->update([
                'origin' => 'Port Dickson',
                'destination' => 'Tanjung Balai',
                'price' => 265,
                'updated_at' => now(),
            ]);

        // Update route 3 (Stulang Laut -> Batam) to (Stulang Laut -> Batam Center) with price 225 (MYR)
        DB::table('ferry_routes')
            ->where('id', 3)
            ->orWhere(function($query) {
                $query->where('origin', 'Stulang Laut')->where('destination', 'Batam');
            })
            ->update([
                'origin' => 'Stulang Laut',
                'destination' => 'Batam Center',
                'price' => 225,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Reverse updates back to IDR prices and old origins/destinations
        DB::table('ferry_routes')
            ->where('id', 1)
            ->update([
                'origin' => 'Pidi',
                'destination' => 'Dumai',
                'price' => 1125000,
                'updated_at' => now(),
            ]);

        DB::table('ferry_routes')
            ->where('id', 2)
            ->update([
                'origin' => 'Pidi',
                'destination' => 'Tanjung Balai',
                'price' => 1125000,
                'updated_at' => now(),
            ]);

        DB::table('ferry_routes')
            ->where('id', 3)
            ->update([
                'origin' => 'Stulang Laut',
                'destination' => 'Batam',
                'price' => 900000,
                'updated_at' => now(),
            ]);
    }
};
