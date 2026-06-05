<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

use App\Traits\LogsActivity;

class VisaProduct extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'country_id',
        'name',
        'slug',
        'type',
        'icon_image_path',
        'promo_label',
        'processing_time',
        'stay_duration',
        'validity',
        'base_price',
        'discount_price',
        'short_description',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (VisaProduct $visaProduct): void {
            if (blank($visaProduct->slug)) {
                $visaProduct->slug = Str::slug($visaProduct->name.'-'.$visaProduct->type);
            }
        });
    }

    public function getIconUrlAttribute(): string
    {
        if ($this->icon_image_path) {
            return \Illuminate\Support\Facades\Storage::url($this->icon_image_path);
        }

        $cardImages = [
            'Jepang' => 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?auto=format&fit=crop&w=400&q=80',
            'Korea Selatan' => 'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?auto=format&fit=crop&w=400&q=80',
            'Australia' => 'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?auto=format&fit=crop&w=400&q=80',
            'China' => 'https://images.unsplash.com/photo-1508804185872-d7badad00f7d?auto=format&fit=crop&w=400&q=80',
            'Taiwan' => 'https://images.unsplash.com/photo-1470004914212-05527e49370b?auto=format&fit=crop&w=400&q=80',
            'United States' => 'https://images.unsplash.com/photo-1485738422979-f5c462d49f04?auto=format&fit=crop&w=400&q=80',
            'United Kingdom' => 'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=400&q=80',
            'Netherlands' => 'https://images.unsplash.com/photo-1534351590666-13e3e96b5017?auto=format&fit=crop&w=400&q=80',
        ];

        $countryName = $this->country->name ?? '';

        return $cardImages[$countryName] ?? 'https://images.unsplash.com/photo-1488085061387-422e29b40080?auto=format&fit=crop&w=400&q=80';
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(VisaRequirement::class)->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VisaDocument::class)->orderBy('sort_order');
    }

    public function processSteps(): HasMany
    {
        return $this->hasMany(VisaProcessStep::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(VisaFaq::class)->orderBy('sort_order');
    }

    public function addons(): HasMany
    {
        return $this->hasMany(VisaAddon::class)->orderBy('sort_order');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class)->latest();
    }

    public function getDisplayBasePriceAttribute(): float
    {
        $current = VisaSetting::current();
        $currency = $current->currency;
        $rate = VisaSetting::getIdrToTargetRate($currency);
        if ($currency === 'IDR') {
            return (float) $this->base_price;
        }
        return (float) $this->base_price / $rate;
    }

    public function getDisplayDiscountPriceAttribute(): ?float
    {
        if (is_null($this->discount_price)) {
            return null;
        }
        $current = VisaSetting::current();
        $currency = $current->currency;
        $rate = VisaSetting::getIdrToTargetRate($currency);
        if ($currency === 'IDR') {
            return (float) $this->discount_price;
        }
        return (float) $this->discount_price / $rate;
    }

    public function getFormattedBasePriceAttribute(): string
    {
        $current = VisaSetting::current();
        $currency = $current->currency;
        $price = $this->display_base_price;
        if ($currency === 'IDR') {
            return 'Rp ' . number_format($price, 0, ',', '.');
        }
        return 'RM ' . number_format($price, 0, ',', '.');
    }

    public function getFormattedDiscountPriceAttribute(): ?string
    {
        $price = $this->display_discount_price;
        if (is_null($price)) {
            return null;
        }
        $current = VisaSetting::current();
        $currency = $current->currency;
        if ($currency === 'IDR') {
            return 'Rp ' . number_format($price, 0, ',', '.');
        }
        return 'RM ' . number_format($price, 0, ',', '.');
    }

    public function getFormattedDisplayPriceAttribute(): string
    {
        if (!is_null($this->discount_price)) {
            return $this->formatted_discount_price;
        }
        return $this->formatted_base_price;
    }
}
