<?php

namespace App\Livewire\Forms;

use App\Models\Permission;
use Illuminate\Support\Str;
use Livewire\Form;

class PermissionForm extends Form
{
    // Validation lives solely in rules() below — see the note in RoleForm
    // for why property-level #[Validate] attributes are deliberately absent.
    public ?Permission $editing = null;

    public string $name = '';

    public string $group = '';

    public ?string $description = '';

    public function setPermission(?Permission $permission): void
    {
        $this->editing = $permission;

        if ($permission) {
            $this->name = $permission->name;
            $this->group = $permission->group;
            $this->description = $permission->description;
        } else {
            $this->reset(['name', 'group', 'description']);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'group' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function computedSlug(): string
    {
        return Str::slug($this->group).'.'.Str::slug($this->name);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $exists = Permission::where('slug', $this->computedSlug())
                ->when($this->editing, fn ($q) => $q->whereKeyNot($this->editing->id))
                ->exists();

            if ($exists) {
                $validator->errors()->add('name', 'A permission with this group and name already exists.');
            }
        });
    }

    public function save(): Permission
    {
        $this->validate();

        $permission = $this->editing ?? new Permission;
        $permission->fill([
            'name' => $this->name,
            'group' => $this->group,
            'description' => $this->description,
            'slug' => $permission->slug ?? $this->computedSlug(),
        ]);
        $permission->save();

        return $permission;
    }
}
