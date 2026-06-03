<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\LogsActivity;

class ApplicationDocument extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'application_id',
        'visa_document_id',
        'label',
        'file_path',
        'status',
        'admin_feedback',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function visaDocument(): BelongsTo
    {
        return $this->belongsTo(VisaDocument::class);
    }
}
