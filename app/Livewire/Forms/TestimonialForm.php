<?php

namespace App\Livewire\Forms;

use App\Enums\TestimonialStatus;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;
use Livewire\WithFileUploads;

class TestimonialForm extends Form
{
    use WithFileUploads;

    public ?Testimonial $editing = null;

    public string $customer_name = '';

    public string $role_or_company = '';

    public string $quote = '';

    public string $rating = '';

    public string $status = 'active';

    public int $sort_order = 0;

    public $photo = null;

    public function setTestimonial(?Testimonial $testimonial): void
    {
        $this->editing = $testimonial;

        if ($testimonial) {
            $this->customer_name = $testimonial->customer_name;
            $this->role_or_company = (string) $testimonial->role_or_company;
            $this->quote = $testimonial->quote;
            $this->rating = $testimonial->rating !== null ? (string) $testimonial->rating : '';
            $this->status = $testimonial->status->value;
            $this->sort_order = $testimonial->sort_order;
        } else {
            $this->reset(['customer_name', 'role_or_company', 'quote', 'rating', 'status', 'sort_order']);
            $this->status = TestimonialStatus::Active->value;
        }
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'role_or_company' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'status' => ['required', \Illuminate\Validation\Rule::in(array_map(fn ($case) => $case->value, TestimonialStatus::cases()))],
            'sort_order' => ['required', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): Testimonial
    {
        $this->validate();

        $testimonial = $this->editing ?? new Testimonial;

        $testimonial->fill([
            'customer_name' => $this->customer_name,
            'role_or_company' => $this->role_or_company ?: null,
            'quote' => $this->quote,
            'rating' => $this->rating !== '' ? (int) $this->rating : null,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ]);

        if (! $testimonial->exists) {
            $testimonial->created_by = Auth::id();
        }
        $testimonial->updated_by = Auth::id();

        if ($this->photo) {
            $testimonial->photo = $this->photo->store('testimonials', 'public');
        }

        $testimonial->save();

        return $testimonial;
    }
}
