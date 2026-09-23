<?php

namespace Database\Seeders;

use App\Actions\ChangePayrollStatus;
use App\Actions\GeneratePayroll;
use App\Enums\PayrollStatus;
use App\Models\Payroll;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@monthlybazar.test')->first();

        if (! $admin) {
            return;
        }

        // Last month: fully processed — generated, approved, and paid — with
        // a matching SalaryPayment per officer so the Salary Payments screen
        // has real data too.
        $lastMonth = now()->subMonthNoOverflow()->startOfMonth();

        if (! Payroll::where('month', $lastMonth->toDateString())->exists()) {
            $payroll = app(GeneratePayroll::class)($lastMonth, $admin);
            app(ChangePayrollStatus::class)($payroll, PayrollStatus::Approved, $admin);
            app(ChangePayrollStatus::class)($payroll->fresh(), PayrollStatus::Paid, $admin);

            foreach ($payroll->items as $item) {
                SalaryPayment::create([
                    'officer_id' => $item->officer_id,
                    'payroll_id' => $payroll->id,
                    'month' => $lastMonth->toDateString(),
                    'amount' => $item->net_salary,
                    'payment_date' => $lastMonth->copy()->addDays(28),
                    'payment_method' => 'bank',
                    'transaction_reference' => 'TXN-'.$lastMonth->format('Ym').'-'.$item->officer_id,
                    'paid_by' => $admin->id,
                ]);
            }
        }

        // This month: generated but still in Draft, so the admin has
        // something to approve/pay when testing the workflow.
        $thisMonth = now()->startOfMonth();

        if (! Payroll::where('month', $thisMonth->toDateString())->exists()) {
            app(GeneratePayroll::class)($thisMonth, $admin);
        }
    }
}
