<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visa_product_id',
        'reference_number',
        'status',
        'traveler_name',
        'traveler_email',
        'traveler_phone',
        'notes',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Application $application): void {
            if (blank($application->reference_number)) {
                $application->reference_number = 'GP-'.strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visaProduct(): BelongsTo
    {
        return $this->belongsTo(VisaProduct::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class)->latest();
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ApplicationStatusLog::class)->latest();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ApplicationMessage::class)->latest();
    }
}
