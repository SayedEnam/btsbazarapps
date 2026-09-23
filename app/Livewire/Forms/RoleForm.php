<?php

namespace App\Livewire\Forms;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;

class RoleForm extends Form
{
    // Validation lives solely in rules() below — a property-level #[Validate]
    // attribute for a field declared there would silently win the array_merge
    // Livewire performs and clobber the richer rule (e.g. the uniqueness
    // check on `name`), so the two are never mixed for the same field.
    public ?Role $editing = null;

    public string $name = '';

    public ?string $description = '';

    /** @var array<int, int> */
    public array $permission_ids = [];

    public function setRole(?Role $role): void
    {
        $this->editing = $role;

        if ($role) {
            $this->name = $role->name;
            $this->description = $role->description;
            $this->permission_ids = $role->permissions->pluck('id')->all();
        } else {
            $this->reset(['name', 'description', 'permission_ids']);
        }
    }

    public function rules(): array
    {
        $roleId = $this->editing?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($roleId)],
            'description' => ['nullable', 'string', 'max:255'],
            'permission_ids' => ['array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    public function save(): Role
    {
        $this->validate();

        $role = $this->editing ?? new Role;
        $role->fill([
            'name' => $this->name,
            'slug' => $role->slug ?? Str::slug($this->name),
            'description' => $this->description,
        ]);
        $role->save();

        $role->permissions()->sync($this->permission_ids);

        return $role;
    }
}
