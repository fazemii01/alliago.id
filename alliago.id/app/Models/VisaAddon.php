<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\LogsActivity;

class VisaAddon extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'visa_product_id',
        'name',
        'description',
        'price',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function visaProduct(): BelongsTo
    {
        return $this->belongsTo(VisaProduct::class);
    }

    public function getDisplayPriceAttribute(): float
    {
        $current = VisaSetting::current();
        $currency = $current->currency;
        $rate = VisaSetting::getIdrToTargetRate($currency);
        if ($currency === 'IDR') {
            return (float) $this->price;
        }
        return (float) $this->price / $rate;
    }

    public function getFormattedPriceAttribute(): string
    {
        $current = VisaSetting::current();
        $currency = $current->currency;
        $price = $this->display_price;
        if ($currency === 'IDR') {
            return 'Rp ' . number_format($price, 0, ',', '.');
        }
        return 'RM ' . number_format($price, 0, ',', '.');
    }
}
