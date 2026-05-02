<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaFaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'visa_product_id',
        'question',
        'answer',
        'sort_order',
    ];

    public function visaProduct(): BelongsTo
    {
        return $this->belongsTo(VisaProduct::class);
    }
}
