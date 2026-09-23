<?php

namespace App\Livewire\Admin\ActivityLogs;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Activity Logs')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public string $moduleFilter = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public ?int $viewingId = null;

    public function mount(): void
    {
        Gate::authorize('activity-logs.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingModuleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function view(int $logId): void
    {
        $this->viewingId = $logId;
    }

    public function render()
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->when($this->search, fn ($query) => $query->where('description', 'like', "%{$this->search}%"))
            ->when($this->moduleFilter, fn ($query) => $query->where('module', $this->moduleFilter))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.activity-logs.index', [
            'logs' => $logs,
            'modules' => ActivityLog::query()->distinct()->orderBy('module')->pluck('module'),
            'viewing' => $this->viewingId ? ActivityLog::with('user')->find($this->viewingId) : null,
        ]);
    }
}
