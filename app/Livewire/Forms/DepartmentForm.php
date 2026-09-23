<?php

namespace App\Livewire\Forms;

use App\Models\Department;
use Illuminate\Validation\Rule;
use Livewire\Form;

class DepartmentForm extends Form
{
    public ?Department $editing = null;

    public string $name = '';

    public ?int $parent_id = null;

    public function setDepartment(?Department $department): void
    {
        $this->editing = $department;
        $this->name = $department->name ?? '';
        $this->parent_id = $department?->parent_id;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('departments', 'name')->ignore($this->editing?->id)],
            'parent_id' => [
                'nullable',
                Rule::exists('departments', 'id')->whereNull('parent_id'), // parent must itself be top-level — one level of nesting only
                Rule::notIn([$this->editing?->id]), // a department can't be its own parent
                function (string $attribute, mixed $value, \Closure $fail) {
                    // A department that already has sub-departments of its own can't
                    // become a sub-department itself — that would create a second
                    // level of nesting, and this app only supports one level.
                    if ($value !== null && $this->editing?->children()->exists()) {
                        $fail('This department already has sub-departments and cannot be moved under another department.');
                    }
                },
            ],
        ];
    }

    public function save(): Department
    {
        $this->validate();

        $department = $this->editing ?? new Department;
        $department->fill([
            'name' => $this->name,
            'parent_id' => $this->parent_id,
        ]);
        $department->save();

        return $department;
    }
}
