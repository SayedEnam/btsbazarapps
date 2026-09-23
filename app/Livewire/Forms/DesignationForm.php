<?php

namespace App\Livewire\Forms;

use App\Models\Designation;
use Illuminate\Validation\Rule;
use Livewire\Form;

class DesignationForm extends Form
{
    public ?Designation $editing = null;

    public string $name = '';

    public function setDesignation(?Designation $designation): void
    {
        $this->editing = $designation;
        $this->name = $designation->name ?? '';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('designations', 'name')->ignore($this->editing?->id)],
        ];
    }

    public function save(): Designation
    {
        $this->validate();

        $designation = $this->editing ?? new Designation;
        $designation->fill(['name' => $this->name]);
        $designation->save();

        return $designation;
    }
}
