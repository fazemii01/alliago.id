<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PopupBanner extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'desktop_banner_path',
        'desktop_banner_url',
        'mobile_banner_path',
        'mobile_banner_url',
        'redirect_link',
        'is_active',
        'delay_seconds',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'delay_seconds' => 'integer',
    ];
}
