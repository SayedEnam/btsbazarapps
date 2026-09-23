<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryPayment extends Model
{
    protected $fillable = [
        'officer_id',
        'payroll_id',
        'month',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'note',
        'paid_by',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'date:Y-m-d',
            'amount' => 'decimal:2',
            'payment_date' => 'date:Y-m-d',
            'payment_method' => PaymentMethod::class,
        ];
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
