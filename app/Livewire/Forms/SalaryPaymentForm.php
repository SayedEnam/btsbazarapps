<?php

namespace App\Livewire\Forms;

use App\Enums\PaymentMethod;
use App\Models\SalaryPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class SalaryPaymentForm extends Form
{
    public string $officer_id = '';

    public string $payroll_id = '';

    public string $month = '';

    public string $amount = '';

    public string $payment_date = '';

    public string $payment_method = 'cash';

    public string $transaction_reference = '';

    public string $note = '';

    public function setDefaults(): void
    {
        $this->reset();
        $this->month = now()->format('Y-m');
        $this->payment_date = now()->toDateString();
        $this->payment_method = PaymentMethod::Cash->value;
    }

    public function rules(): array
    {
        return [
            'officer_id' => ['required', 'exists:officers,user_id'],
            'payroll_id' => ['nullable', 'exists:payrolls,id'],
            'month' => ['required', 'date_format:Y-m'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', Rule::in(array_map(fn ($case) => $case->value, PaymentMethod::cases()))],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function save(): SalaryPayment
    {
        $this->validate();

        return SalaryPayment::create([
            'officer_id' => $this->officer_id,
            'payroll_id' => $this->payroll_id ?: null,
            'month' => $this->month.'-01',
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'transaction_reference' => $this->transaction_reference ?: null,
            'note' => $this->note ?: null,
            'paid_by' => Auth::id(),
        ]);
    }
}
