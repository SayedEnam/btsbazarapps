<?php

namespace App\Models;

use App\Enums\SalaryProfileStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'officer_id',
        'basic_salary',
        'house_allowance',
        'transport_allowance',
        'mobile_allowance',
        'other_allowance',
        'deduction',
        'effective_from',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'house_allowance' => 'decimal:2',
            'transport_allowance' => 'decimal:2',
            'mobile_allowance' => 'decimal:2',
            'other_allowance' => 'decimal:2',
            'deduction' => 'decimal:2',
            'effective_from' => 'date:Y-m-d',
            'status' => SalaryProfileStatus::class,
        ];
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function totalAllowance(): string
    {
        return bcadd(
            bcadd(bcadd((string) $this->house_allowance, (string) $this->transport_allowance, 2), (string) $this->mobile_allowance, 2),
            (string) $this->other_allowance,
            2
        );
    }

    /**
     * Net Salary = Basic + Total Allowance - Deduction. Always computed
     * server-side (spec section 18) — never accept a net figure from the
     * client.
     */
    public function netSalary(): string
    {
        return bcsub(bcadd((string) $this->basic_salary, $this->totalAllowance(), 2), (string) $this->deduction, 2);
    }
}
