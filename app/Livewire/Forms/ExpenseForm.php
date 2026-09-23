<?php

namespace App\Livewire\Forms;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;
use Livewire\WithFileUploads;

class ExpenseForm extends Form
{
    use WithFileUploads;

    public ?Expense $editing = null;

    public string $category_id = '';

    public string $amount = '';

    public string $expense_date = '';

    public string $description = '';

    public string $paid_by = '';

    public string $reference = '';

    public $attachment = null;

    public function setExpense(?Expense $expense): void
    {
        $this->editing = $expense;

        if ($expense) {
            $this->category_id = (string) $expense->category_id;
            $this->amount = (string) $expense->amount;
            $this->expense_date = $expense->expense_date->format('Y-m-d');
            $this->description = (string) $expense->description;
            $this->paid_by = (string) $expense->paid_by;
            $this->reference = (string) $expense->reference;
        } else {
            $this->reset(['category_id', 'amount', 'expense_date', 'description', 'paid_by', 'reference']);
            $this->expense_date = now()->toDateString();
        }
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:9999999999999.99'],
            'expense_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'paid_by' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            // Attachments are receipts/invoices — image or PDF only, 5MB cap.
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function save(): Expense
    {
        $this->validate();

        $expense = $this->editing ?? new Expense;
        $expense->fill([
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'description' => $this->description ?: null,
            'paid_by' => $this->paid_by ?: null,
            'reference' => $this->reference ?: null,
        ]);

        if (! $expense->exists) {
            $expense->created_by = Auth::id();
        }

        if ($this->attachment) {
            $expense->attachment = $this->attachment->store('expenses', 'public');
        }

        $expense->save();

        return $expense;
    }
}
