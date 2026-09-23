<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStatusHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'application_id',
        'old_status',
        'new_status',
        'changed_by',
        'reason',
        'changed_at',
    ];

    protected function casts(): array
    {
        return [
            'old_status' => ApplicationStatus::class,
            'new_status' => ApplicationStatus::class,
            'changed_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
