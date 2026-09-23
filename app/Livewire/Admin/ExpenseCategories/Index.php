<?php

namespace App\Livewire\Admin\ExpenseCategories;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\ExpenseCategoryForm;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Expense Categories')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public string $search = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ExpenseCategoryForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('expense-categories.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        Gate::authorize('expense-categories.create');

        $this->editingId = null;
        $this->form->setCategory(null);
        $this->showModal = true;
    }

    public function edit(int $categoryId): void
    {
        Gate::authorize('expense-categories.edit');

        $category = ExpenseCategory::findOrFail($categoryId);

        $this->editingId = $category->id;
        $this->form->setCategory($category);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'expense-categories.edit' : 'expense-categories.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Category updated successfully.' : 'Category created successfully.');
    }

    public function confirmDelete(int $categoryId): void
    {
        $this->deletingId = $categoryId;
    }

    public function delete(): void
    {
        Gate::authorize('expense-categories.delete');

        $category = ExpenseCategory::withCount('expenses')->findOrFail($this->deletingId);

        if ($category->expenses_count > 0) {
            $this->deletingId = null;
            $this->dispatch('notify', type: 'error', message: 'This category has expenses recorded against it and cannot be deleted.');

            return;
        }

        $category->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Category deleted successfully.');
    }

    public function render()
    {
        $categories = ExpenseCategory::query()
            ->withCount('expenses')
            ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.expense-categories.index', [
            'categories' => $categories,
        ]);
    }
}
