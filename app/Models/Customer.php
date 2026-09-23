<?php

namespace App\Models;

use App\Enums\CustomerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'father_name',
        'mother_name',
        'address',
        'nid_number',
        'profession',
        'date_of_birth',
        'gender',
        'profile_photo',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date:Y-m-d',
            'status' => CustomerStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function referral(): HasOne
    {
        return $this->hasOne(Referral::class);
    }

    public function referralOfficer(): ?User
    {
        return $this->referral?->officer;
    }

    public function profilePhotoUrl(): ?string
    {
        return $this->profile_photo ? asset('storage/'.$this->profile_photo) : null;
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
