<?php

namespace App\Livewire\Admin\Testimonials;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\TestimonialForm;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Testimonials')]
class Index extends Component
{
    use WithFileUploads, WithPagination, ValidatesOnUpdate;

    public bool $showModal = false;

    public ?int $editingId = null;

    public TestimonialForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('testimonials.view');
    }

    public function create(): void
    {
        Gate::authorize('testimonials.create');

        $this->editingId = null;
        $this->form->setTestimonial(null);
        $this->showModal = true;
    }

    public function edit(int $testimonialId): void
    {
        Gate::authorize('testimonials.edit');

        $testimonial = Testimonial::findOrFail($testimonialId);

        $this->editingId = $testimonial->id;
        $this->form->setTestimonial($testimonial);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'testimonials.edit' : 'testimonials.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Testimonial updated successfully.' : 'Testimonial created successfully.');
    }

    public function confirmDelete(int $testimonialId): void
    {
        $this->deletingId = $testimonialId;
    }

    public function delete(): void
    {
        Gate::authorize('testimonials.delete');

        Testimonial::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Testimonial deleted successfully.');
    }

    public function render()
    {
        $testimonials = Testimonial::query()->ordered()->paginate(10);

        return view('livewire.admin.testimonials.index', [
            'testimonials' => $testimonials,
        ]);
    }
}
