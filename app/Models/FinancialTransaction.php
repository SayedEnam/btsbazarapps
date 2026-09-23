<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'transaction_type',
        'reference_type',
        'reference_id',
        'amount',
        'transaction_date',
        'description',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'transaction_type' => TransactionType::class,
            'amount' => 'decimal:2',
            'transaction_date' => 'date:Y-m-d',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
