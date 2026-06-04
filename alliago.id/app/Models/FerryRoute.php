<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FerryRoute extends Model
{
    protected $fillable = ['origin', 'destination', 'price', 'ship_image_path', 'is_active'];

    protected $casts = [
        'price'     => 'integer',
        'is_active' => 'boolean',
    ];

    public function getLabelAttribute(): string
    {
        return "Ferry {$this->origin} → {$this->destination}";
    }
}
