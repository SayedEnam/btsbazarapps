<?php

namespace App\Livewire\Forms;

use App\Enums\HeroSlideDisplayMode;
use App\Enums\HeroSlideStatus;
use App\Models\HeroSlide;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;
use Livewire\WithFileUploads;

class HeroSlideForm extends Form
{
    use WithFileUploads;

    public ?HeroSlide $editing = null;

    public string $title = '';

    public string $subtitle = '';

    public string $button_text = '';

    public string $button_url = '';

    public string $display_mode = 'text_and_button';

    public string $status = 'active';

    public int $sort_order = 0;

    public $image = null;

    public function setSlide(?HeroSlide $slide): void
    {
        $this->editing = $slide;

        if ($slide) {
            $this->title = $slide->title;
            $this->subtitle = (string) $slide->subtitle;
            $this->button_text = (string) $slide->button_text;
            $this->button_url = (string) $slide->button_url;
            $this->display_mode = $slide->display_mode->value;
            $this->status = $slide->status->value;
            $this->sort_order = $slide->sort_order;
        } else {
            $this->reset(['title', 'subtitle', 'button_text', 'button_url', 'display_mode', 'status', 'sort_order']);
            $this->display_mode = HeroSlideDisplayMode::TextAndButton->value;
            $this->status = HeroSlideStatus::Active->value;
        }
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:50'],
            'button_url' => ['nullable', 'string', 'max:255'],
            'display_mode' => ['required', \Illuminate\Validation\Rule::in(array_map(fn ($case) => $case->value, HeroSlideDisplayMode::cases()))],
            'status' => ['required', \Illuminate\Validation\Rule::in(array_map(fn ($case) => $case->value, HeroSlideStatus::cases()))],
            'sort_order' => ['required', 'integer', 'min:0'],
            'image' => [
                $this->display_mode === HeroSlideDisplayMode::ImageOnly->value && ! $this->editing?->image ? 'required' : 'nullable',
                'image', 'max:4096',
            ],
        ];
    }

    /**
     * The optional background image is never cleared implicitly — a slide
     * that already has one keeps it on save unless a new file is chosen,
     * same convention as Package/Officer image uploads elsewhere.
     */
    public function save(): HeroSlide
    {
        $this->validate();

        $slide = $this->editing ?? new HeroSlide;

        $slide->fill([
            'title' => $this->title,
            'subtitle' => $this->subtitle ?: null,
            'button_text' => $this->button_text ?: null,
            'button_url' => $this->button_url ?: null,
            'display_mode' => $this->display_mode,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ]);

        if (! $slide->exists) {
            $slide->created_by = Auth::id();
        }
        $slide->updated_by = Auth::id();

        if ($this->image) {
            $slide->image = $this->image->store('hero-slides', 'public');
        }

        $slide->save();

        return $slide;
    }
}
