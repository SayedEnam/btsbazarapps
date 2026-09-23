<?php

namespace App\Livewire\Admin\Expenses;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\ExpenseForm;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Expenses')]
class Index extends Component
{
    use WithFileUploads, WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public string $categoryFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ExpenseForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('expenses.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('expenses.create');

        $this->editingId = null;
        $this->form->setExpense(null);
        $this->showModal = true;
    }

    public function edit(int $expenseId): void
    {
        Gate::authorize('expenses.edit');

        $expense = Expense::findOrFail($expenseId);

        $this->editingId = $expense->id;
        $this->form->setExpense($expense);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'expenses.edit' : 'expenses.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Expense updated successfully.' : 'Expense recorded successfully.');
    }

    public function confirmDelete(int $expenseId): void
    {
        $this->deletingId = $expenseId;
    }

    public function delete(): void
    {
        Gate::authorize('expenses.delete');

        Expense::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Expense deleted successfully.');
    }

    protected function filteredQuery()
    {
        return Expense::query()
            ->with('category')
            ->when($this->search, fn ($query) => $query->where(function ($q) {
                $q->where('description', 'like', "%{$this->search}%")
                    ->orWhere('paid_by', 'like', "%{$this->search}%")
                    ->orWhere('reference', 'like', "%{$this->search}%");
            }))
            ->when($this->categoryFilter, fn ($query) => $query->where('category_id', $this->categoryFilter))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('expense_date', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('expense_date', '<=', $this->dateTo));
    }

    public function render()
    {
        // Two separate queries (not the paginated page's collection) so the
        // total reflects every filtered expense, not just the 10 shown.
        $totalFiltered = $this->filteredQuery()->sum('amount');

        $expenses = $this->filteredQuery()
            ->with(['category', 'createdBy'])
            ->latest('expense_date')
            ->paginate(10);

        return view('livewire.admin.expenses.index', [
            'expenses' => $expenses,
            'categories' => ExpenseCategory::orderBy('name')->get(),
            'totalFiltered' => $totalFiltered,
        ]);
    }
}
