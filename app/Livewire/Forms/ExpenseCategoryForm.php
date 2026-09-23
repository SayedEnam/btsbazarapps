<?php

namespace App\Livewire\Forms;

use App\Models\ExpenseCategory;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ExpenseCategoryForm extends Form
{
    public ?ExpenseCategory $editing = null;

    public string $name = '';

    public function setCategory(?ExpenseCategory $category): void
    {
        $this->editing = $category;
        $this->name = $category->name ?? '';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('expense_categories', 'name')->ignore($this->editing?->id)],
        ];
    }

    public function save(): ExpenseCategory
    {
        $this->validate();

        $category = $this->editing ?? new ExpenseCategory;
        $category->fill(['name' => $this->name]);
        $category->save();

        return $category;
    }
}
