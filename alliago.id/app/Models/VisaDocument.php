<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'visa_product_id',
        'name',
        'description',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function visaProduct(): BelongsTo
    {
        return $this->belongsTo(VisaProduct::class);
    }
}
