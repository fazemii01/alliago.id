<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaAddon extends Model
{
    use HasFactory;

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
}
