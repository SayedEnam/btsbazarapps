<?php

namespace App\Models;

use App\Enums\PackageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'price',
        'duration',
        'status',
        'image',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sort_order' => 'integer',
            'status' => PackageStatus::class,
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PackageStatus::Active);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Generate the next sequential package code (PKG-0001, PKG-0002, ...).
     * Locks existing rows (including soft-deleted ones, so a removed
     * package's code is never reissued and collided with) for the duration
     * of the transaction so two concurrent calls can't compute the same
     * next number.
     */
    public static function generateCode(): string
    {
        return DB::transaction(function () {
            $max = static::withTrashed()
                ->where('code', 'like', 'PKG-%')
                ->lockForUpdate()
                ->pluck('code')
                ->map(fn (string $code) => (int) str_replace('PKG-', '', $code))
                ->max() ?? 0;

            return sprintf('PKG-%04d', $max + 1);
        });
    }
}
