<?php

namespace App\Livewire\Admin\FinancialTransactions;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\FinancialTransactionForm;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Financial Transactions')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $typeFilter = '';

    public bool $showModal = false;

    public FinancialTransactionForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('financial-transactions.view');
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('financial-transactions.create');

        $this->form->setDefaults();
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize('financial-transactions.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: 'Transaction recorded successfully.');
    }

    public function confirmDelete(int $transactionId): void
    {
        $this->deletingId = $transactionId;
    }

    public function delete(): void
    {
        Gate::authorize('financial-transactions.delete');

        FinancialTransaction::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Transaction deleted successfully.');
    }

    public function render()
    {
        $transactions = FinancialTransaction::query()
            ->with('createdBy')
            ->when($this->typeFilter, fn ($query) => $query->where('transaction_type', $this->typeFilter))
            ->latest('transaction_date')
            ->paginate(10);

        return view('livewire.admin.financial-transactions.index', [
            'transactions' => $transactions,
        ]);
    }
}
