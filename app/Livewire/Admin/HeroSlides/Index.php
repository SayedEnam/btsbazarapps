<?php

namespace App\Livewire\Admin\HeroSlides;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\HeroSlideForm;
use App\Models\HeroSlide;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Hero Slides')]
class Index extends Component
{
    use WithFileUploads, WithPagination, ValidatesOnUpdate;

    public bool $showModal = false;

    public ?int $editingId = null;

    public HeroSlideForm $form;

    public ?int $deletingId = null;

    public function mount(): void
    {
        Gate::authorize('hero-slides.view');
    }

    public function create(): void
    {
        Gate::authorize('hero-slides.create');

        $this->editingId = null;
        $this->form->setSlide(null);
        $this->showModal = true;
    }

    public function edit(int $slideId): void
    {
        Gate::authorize('hero-slides.edit');

        $slide = HeroSlide::findOrFail($slideId);

        $this->editingId = $slide->id;
        $this->form->setSlide($slide);
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize($this->editingId ? 'hero-slides.edit' : 'hero-slides.create');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Hero slide updated successfully.' : 'Hero slide created successfully.');
    }

    public function confirmDelete(int $slideId): void
    {
        $this->deletingId = $slideId;
    }

    public function delete(): void
    {
        Gate::authorize('hero-slides.delete');

        HeroSlide::findOrFail($this->deletingId)->delete();

        $this->deletingId = null;
        $this->dispatch('notify', type: 'success', message: 'Hero slide deleted successfully.');
    }

    public function render()
    {
        $slides = HeroSlide::query()->ordered()->paginate(10);

        return view('livewire.admin.hero-slides.index', [
            'slides' => $slides,
        ]);
    }
}
