<?php

namespace App\Models;

use App\Enums\PayrollStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    protected $fillable = [
        'month',
        'status',
        'generated_by',
        'approved_by',
        'approved_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            // Explicit Y-m-d format: a plain 'date' cast still serializes to
            // full 'Y-m-d H:i:s' on save, which silently breaks the exact
            // string-match query GeneratePayroll/firstOrCreate rely on to
            // detect "does this month's payroll already exist" — the insert
            // and the lookup would stop agreeing on what the stored value
            // looks like.
            'month' => 'date:Y-m-d',
            'status' => PayrollStatus::class,
            'approved_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function totalNetSalary(): string
    {
        return (string) $this->items->sum('net_salary');
    }
}
