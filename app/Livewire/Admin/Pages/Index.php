<?php

namespace App\Livewire\Admin\Pages;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pages')]
class Index extends Component
{
    use ValidatesOnUpdate;

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $meta_description = '';

    public string $content = '';

    public function mount(): void
    {
        Gate::authorize('pages.view');
    }

    public function edit(int $pageId): void
    {
        Gate::authorize('pages.edit');

        $page = Page::findOrFail($pageId);

        $this->editingId = $page->id;
        $this->title = $page->title;
        $this->meta_description = (string) $page->meta_description;
        $this->content = (string) $page->content;
        $this->showModal = true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
        ];
    }

    public function save(): void
    {
        Gate::authorize('pages.edit');

        $validated = $this->validate();

        $page = Page::findOrFail($this->editingId);
        $page->fill($validated);
        $page->updated_by = Auth::id();
        $page->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: 'Page updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.pages.index', [
            'pages' => Page::orderBy('title')->get(),
        ]);
    }
}
