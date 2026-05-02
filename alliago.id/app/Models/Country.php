<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'flag_emoji',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Country $country): void {
            if (blank($country->slug)) {
                $country->slug = Str::slug($country->name);
            }
        });
    }

    public function visaProducts(): HasMany
    {
        return $this->hasMany(VisaProduct::class);
    }
}
