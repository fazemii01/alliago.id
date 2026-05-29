<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FlightPricingConfig extends Model
{
    protected $fillable = ['label', 'addon_cost', 'service_fee', 'notes'];

    protected $casts = [
        'addon_cost' => 'decimal:2',
        'service_fee' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('flight_pricing_config');
        });
    }

    public static function current(): self
    {
        return Cache::remember('flight_pricing_config', 300, fn () =>
            static::firstOrCreate(
                ['id' => 1],
                ['label' => 'Default', 'addon_cost' => 0, 'service_fee' => 500000]
            )
        );
    }

    public function totalMarkup(): float
    {
        return (float) $this->addon_cost + (float) $this->service_fee;
    }
}
