<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class PaymentMethod extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentMethodFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'provider',
        'code',
        'account_name',
        'account_number',
        'description',
        'icon',
        'is_active',
        'configuration',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'configuration' => 'array',
    ];
}
