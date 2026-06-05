<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class VisaSetting extends Model
{
    protected $fillable = ['currency'];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('visa_setting');
        });
    }

    public static function current(): self
    {
        return Cache::remember('visa_setting', 3600, fn () =>
            static::firstOrCreate(
                ['id' => 1],
                ['currency' => 'IDR']
            )
        );
    }

    /**
     * Retrieve the exchange rate from IDR to the target currency.
     * Note: rates are cached by PageController.
     */
    public static function getIdrToTargetRate(string $target): float
    {
        if (strtoupper($target) === 'IDR') {
            return 1.0;
        }

        $ratesData = Cache::get('currency_rates_data');
        if ($ratesData && isset($ratesData['exchange_rates'])) {
            $rates = $ratesData['exchange_rates'];
            $idrPerUsd = $rates['IDR'] ?? 16250;
            $targetPerUsd = $rates[$target] ?? 1.0;
            
            if ($targetPerUsd > 0) {
                return $idrPerUsd / $targetPerUsd; // e.g. 16250 / 4.7 = ~3457 IDR per MYR
            }
        }

        // Realistic fallbacks if cache is not populated or offline
        if (strtoupper($target) === 'MYR') {
            return 3450.0;
        }

        return 1.0;
    }
}
