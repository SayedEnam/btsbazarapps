<?php

namespace App\Livewire\Admin\SalaryPayments;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Livewire\Forms\SalaryPaymentForm;
use App\Models\Officer;
use App\Models\Payroll;
use App\Models\SalaryPayment;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Salary Payments')]
class Index extends Component
{
    use WithPagination, ValidatesOnUpdate;

    public bool $showModal = false;

    public SalaryPaymentForm $form;

    public function mount(): void
    {
        Gate::authorize('payroll.view');
    }

    public function create(): void
    {
        Gate::authorize('payroll.pay');

        $this->form->setDefaults();
        $this->showModal = true;
    }

    public function save(): void
    {
        Gate::authorize('payroll.pay');

        $this->form->save();

        $this->showModal = false;
        $this->dispatch('notify', type: 'success', message: 'Salary payment recorded successfully.');
    }

    public function render()
    {
        $payments = SalaryPayment::query()
            ->with(['officer', 'paidBy'])
            ->latest('payment_date')
            ->paginate(10);

        return view('livewire.admin.salary-payments.index', [
            'payments' => $payments,
            'officers' => Officer::with('user')->get(),
            'payrolls' => Payroll::orderByDesc('month')->get(),
        ]);
    }
}
