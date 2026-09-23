<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    public const ABOUT = 'about';

    public const TERMS = 'terms';

    public const PRIVACY = 'privacy';

    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'content',
        'updated_by',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
