<?php

namespace App\Livewire\Forms;

use App\Enums\PackageStatus;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Form;
use Livewire\WithFileUploads;

class PackageForm extends Form
{
    use WithFileUploads;

    public ?Package $editing = null;

    public string $name = '';

    public string $code = '';

    public string $description = '';

    public string $price = '';

    public string $duration = '';

    public string $status = 'active';

    public int $sort_order = 0;

    public $image = null;

    public function setPackage(?Package $package): void
    {
        $this->editing = $package;

        if ($package) {
            $this->name = $package->name;
            $this->code = $package->code;
            $this->description = (string) $package->description;
            $this->price = (string) $package->price;
            $this->duration = (string) $package->duration;
            $this->status = $package->status->value;
            $this->sort_order = $package->sort_order;
        } else {
            $this->reset(['name', 'code', 'description', 'price', 'duration', 'status', 'sort_order']);
            $this->status = PackageStatus::Active->value;
            $this->code = Package::generateCode();
        }
    }

    public function rules(): array
    {
        $packageId = $this->editing?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('packages', 'name')->ignore($packageId)],
            'code' => ['required', 'string', 'max:50', Rule::unique('packages', 'code')->ignore($packageId)],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'duration' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(array_map(fn ($case) => $case->value, PackageStatus::cases()))],
            'sort_order' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Never overwritten on edit: the package's slug and price stay
     * meaningful identifiers/history even as `name`/`price` change going
     * forward — existing applications keep the price they were submitted at
     * because Application.package_price is a separate, already-copied value.
     */
    public function save(): Package
    {
        $this->validate();

        $package = $this->editing ?? new Package;

        if (! $package->exists) {
            $slug = Str::slug($this->name);

            Validator::make(['name' => $this->name], [
                'name' => [
                    function ($attribute, $value, $fail) use ($slug) {
                        if (Package::where('slug', $slug)->exists()) {
                            $fail('A package with this name already exists. Please choose a different name.');
                        }
                    },
                ],
            ])->validate();
        }

        $package->fill([
            'name' => $this->name,
            'slug' => $package->slug ?? Str::slug($this->name),
            'code' => $this->code,
            'description' => $this->description ?: null,
            'price' => $this->price,
            'duration' => $this->duration ?: null,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ]);

        if (! $package->exists) {
            $package->created_by = Auth::id();
        }
        $package->updated_by = Auth::id();

        if ($this->image) {
            $package->image = $this->image->store('packages', 'public');
        }

        $package->save();

        return $package;
    }
}
