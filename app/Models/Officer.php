<?php

namespace App\Models;

use App\Enums\OfficerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Officer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department_id',
        'designation_id',
        'address',
        'joining_date',
        'basic_salary',
        'profile_picture',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date:Y-m-d',
            'basic_salary' => 'decimal:2',
            'status' => OfficerStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Referrals belong to the underlying User (Referral.officer_id points at
     * users.id, not officers.id), so this joins through user_id rather than
     * this model's own primary key.
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'officer_id', 'user_id');
    }

    public function referralLink(): string
    {
        return $this->user->referral_code
            ? route('referral.capture', $this->user->referral_code)
            : '#';
    }

    public function profilePictureUrl(): ?string
    {
        return $this->profile_picture ? asset('storage/'.$this->profile_picture) : null;
    }

    /**
     * Generate the next sequential employee ID (EMP-0001, EMP-0002, ...).
     * Locks existing rows (including soft-deleted ones, so a removed
     * officer's ID is never reissued) for the duration of the transaction
     * so two concurrent calls can't compute the same next number.
     */
    public static function generateEmployeeId(): string
    {
        return DB::transaction(function () {
            $max = static::withTrashed()
                ->lockForUpdate()
                ->pluck('employee_id')
                ->map(fn (string $id) => (int) str_replace('EMP-', '', $id))
                ->max() ?? 0;

            return sprintf('EMP-%04d', $max + 1);
        });
    }
}
