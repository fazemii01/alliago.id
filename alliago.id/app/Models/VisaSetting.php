<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * Retrieve cached or live exchange rates data from Abstract API.
     */
    public static function getExchangeRatesData(): ?array
    {
        $apiKey = config('services.abstract_currency.key') ?: env('EXCHANGE_RATE_API_KEY');

        return Cache::remember('currency_rates_data', 14400, function () use ($apiKey) {
            if (!$apiKey) {
                return null;
            }

            try {
                $response = Http::timeout(10)->get('https://exchange-rates.abstractapi.com/v1/live/', [
                    'api_key' => $apiKey,
                    'base' => 'USD',
                ]);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::warning('Abstract API Currency response not successful: ' . $response->status() . ' - ' . $response->body());
            } catch (\Throwable $e) {
                Log::error('Abstract API Currency fetch failed: ' . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Retrieve the exchange rate from IDR to the target currency.
     * Calculated dynamically from Abstract API live rates (cached for 4h).
     */
    public static function getIdrToTargetRate(string $target): float
    {
        $target = strtoupper($target);
        if ($target === 'IDR') {
            return 1.0;
        }
        if ($target === 'RM') {
            $target = 'MYR';
        }

        $ratesData = self::getExchangeRatesData();
        if ($ratesData && isset($ratesData['exchange_rates'])) {
            $rates = $ratesData['exchange_rates'];
            $idrPerUsd = (float) ($rates['IDR'] ?? 0);
            $targetPerUsd = (float) ($rates[$target] ?? 0);
            
            if ($idrPerUsd > 0 && $targetPerUsd > 0) {
                return $idrPerUsd / $targetPerUsd; // e.g. 16250 / 4.7 = ~3457 IDR per MYR
            }
        }

        // Realistic fallbacks if cache is not populated or offline
        if ($target === 'MYR') {
            return 3450.0;
        }

        return 1.0;
    }
}
