<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    public function up(): void
    {
        Cache::forget('flight_pricing_config');

        // Find all records with label 'Default'
        $defaultConfigs = DB::table('flight_pricing_configs')
            ->where('label', 'Default')
            ->orderBy('id', 'asc')
            ->get();

        if ($defaultConfigs->count() > 1) {
            // Keep the first record or the one with ID 1
            $keepRecord = $defaultConfigs->firstWhere('id', 1) ?? $defaultConfigs->last();

            // Delete all other default records
            DB::table('flight_pricing_configs')
                ->where('label', 'Default')
                ->where('id', '!=', $keepRecord->id)
                ->delete();
        }
    }

    public function down(): void
    {
        // No reversal needed for cleanup migration
    }
};
