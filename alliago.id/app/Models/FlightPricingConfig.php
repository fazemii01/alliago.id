<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FlightPricingConfig extends Model
{
    protected $fillable = ['label', 'addon_cost', 'service_fee', 'notes', 'zz_markup', 'zz_name', 'zz_logo_url'];

    protected $casts = [
        'addon_cost' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'zz_markup'  => 'decimal:2',
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

    public function markupFor(string $iata): float
    {
        if (strtoupper($iata) === 'ZZ' && $this->zz_markup !== null) {
            return (float) $this->zz_markup;
        }
        return $this->totalMarkup();
    }

    public function nameFor(string $iata): ?string
    {
        if (strtoupper($iata) === 'ZZ' && $this->zz_name) {
            return $this->zz_name;
        }
        return null;
    }

    public function logoFor(string $iata): ?string
    {
        if (strtoupper($iata) === 'ZZ' && $this->zz_logo_url) {
            return $this->zz_logo_url;
        }
        return null;
    }
}
