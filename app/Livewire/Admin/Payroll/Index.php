<?php

namespace App\Livewire\Admin\Payroll;

use App\Actions\ChangePayrollStatus;
use App\Actions\GeneratePayroll;
use App\Enums\PayrollStatus;
use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Models\Payroll;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

#[Layout('layouts.admin')]
#[Title('Payroll')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public bool $showGenerateModal = false;

    public string $generateMonth = '';

    public ?int $viewingId = null;

    public function mount(): void
    {
        Gate::authorize('payroll.view');
        $this->generateMonth = now()->format('Y-m');
    }

    public function rules(): array
    {
        return [
            'generateMonth' => ['required', 'date_format:Y-m'],
        ];
    }

    public function openGenerate(): void
    {
        Gate::authorize('payroll.create');

        $this->generateMonth = now()->format('Y-m');
        $this->showGenerateModal = true;
    }

    public function generate(): void
    {
        Gate::authorize('payroll.create');

        $this->validate();

        $payroll = app(GeneratePayroll::class)(Carbon::createFromFormat('Y-m', $this->generateMonth), Auth::user());

        $this->showGenerateModal = false;

        $itemCount = $payroll->items()->count();
        $this->dispatch('notify', type: 'success', message: "Payroll generated for {$payroll->month->format('F Y')} with {$itemCount} officer(s).");
    }

    public function view(int $payrollId): void
    {
        $this->viewingId = $payrollId;
    }

    public function approve(int $payrollId): void
    {
        Gate::authorize('payroll.approve');

        $this->changeStatus(Payroll::findOrFail($payrollId), PayrollStatus::Approved, 'Payroll approved successfully.');
    }

    public function markPaid(int $payrollId): void
    {
        Gate::authorize('payroll.pay');

        $this->changeStatus(Payroll::findOrFail($payrollId), PayrollStatus::Paid, 'Payroll marked as paid.');
    }

    protected function changeStatus(Payroll $payroll, PayrollStatus $status, string $successMessage): void
    {
        try {
            app(ChangePayrollStatus::class)($payroll, $status, Auth::user());
            $this->dispatch('notify', type: 'success', message: $successMessage);
        } catch (RuntimeException $e) {
            $this->dispatch('notify', type: 'error', message: $e->getMessage());
        }
    }

    public function render()
    {
        $payrolls = Payroll::query()
            ->withCount('items')
            ->with('items')
            ->latest('month')
            ->paginate(10);

        return view('livewire.admin.payroll.index', [
            'payrolls' => $payrolls,
            'viewing' => $this->viewingId ? Payroll::with('items.officer')->find($this->viewingId) : null,
        ]);
    }
}
