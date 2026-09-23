<?php

namespace App\Livewire\Forms;

use App\Enums\TransactionType;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class FinancialTransactionForm extends Form
{
    public string $transaction_type = 'expense';

    public string $amount = '';

    public string $transaction_date = '';

    public string $description = '';

    public function setDefaults(): void
    {
        $this->reset();
        $this->transaction_type = TransactionType::Expense->value;
        $this->transaction_date = now()->toDateString();
    }

    public function rules(): array
    {
        return [
            'transaction_type' => ['required', Rule::in(array_map(fn ($case) => $case->value, TransactionType::cases()))],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],
            'transaction_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * A purely manual entry — reference_type/reference_id are left null,
     * since this form only ever creates ad-hoc ledger entries, never
     * synthetic ones auto-derived from another record (spec section 23).
     */
    public function save(): FinancialTransaction
    {
        $this->validate();

        return FinancialTransaction::create([
            'transaction_type' => $this->transaction_type,
            'amount' => $this->amount,
            'transaction_date' => $this->transaction_date,
            'description' => $this->description,
            'created_by' => Auth::id(),
        ]);
    }
}
