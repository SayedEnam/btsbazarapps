<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_number',
        'customer_id',
        'officer_id',
        'package_id',
        'package_price',
        'application_date',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'package_price' => 'decimal:2',
            'application_date' => 'date:Y-m-d',
            'reviewed_at' => 'datetime',
            'status' => ApplicationStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class)->latest('changed_at');
    }

    /**
     * Generate the next sequential application number for the current year
     * (APP-2026-000001, APP-2026-000002, ...). Locks matching rows for the
     * duration of the transaction so two concurrent submissions can't
     * compute the same number.
     */
    public static function generateApplicationNumber(): string
    {
        return DB::transaction(function () {
            $year = now()->year;
            $prefix = "APP-{$year}-";

            $max = static::withTrashed()
                ->where('application_number', 'like', "{$prefix}%")
                ->lockForUpdate()
                ->pluck('application_number')
                ->map(fn (string $number) => (int) str_replace($prefix, '', $number))
                ->max() ?? 0;

            return $prefix.str_pad((string) ($max + 1), 6, '0', STR_PAD_LEFT);
        });
    }
}
