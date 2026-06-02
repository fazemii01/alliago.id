<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

use App\Traits\LogsActivity;

class Application extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'visa_product_id',
        'reference_number',
        'status',
        'traveler_name',
        'traveler_email',
        'traveler_phone',
        'notes',
        'metadata',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'metadata' => 'array',
        'is_locked' => 'boolean',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
            if (empty($model->reference_number)) {
                $model->reference_number = 'VSA-' . strtoupper(Str::random(10));
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
