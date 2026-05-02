<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class VisaProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'slug',
        'type',
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
}
