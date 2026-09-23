<?php

namespace App\Livewire\Forms;

use App\Enums\SalaryProfileStatus;
use App\Models\SalaryProfile;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Form;

class SalaryProfileForm extends Form
{
    public ?SalaryProfile $editing = null;

    public string $officer_id = '';

    public string $basic_salary = '';

    public string $house_allowance = '0';

    public string $transport_allowance = '0';

    public string $mobile_allowance = '0';

    public string $other_allowance = '0';

    public string $deduction = '0';

    public string $effective_from = '';

    public string $status = 'active';

    public function setProfile(?SalaryProfile $profile): void
    {
        $this->editing = $profile;

        if ($profile) {
            $this->officer_id = (string) $profile->officer_id;
            $this->basic_salary = (string) $profile->basic_salary;
            $this->house_allowance = (string) $profile->house_allowance;
            $this->transport_allowance = (string) $profile->transport_allowance;
            $this->mobile_allowance = (string) $profile->mobile_allowance;
            $this->other_allowance = (string) $profile->other_allowance;
            $this->deduction = (string) $profile->deduction;
            $this->effective_from = $profile->effective_from->format('Y-m-d');
            $this->status = $profile->status->value;
        } else {
            $this->reset([
                'officer_id', 'basic_salary', 'house_allowance', 'transport_allowance',
                'mobile_allowance', 'other_allowance', 'deduction', 'effective_from', 'status',
            ]);
            $this->house_allowance = $this->transport_allowance = $this->mobile_allowance = $this->other_allowance = $this->deduction = '0';
            $this->effective_from = now()->toDateString();
            $this->status = SalaryProfileStatus::Active->value;
        }
    }

    public function rules(): array
    {
        return [
            'officer_id' => ['required', 'exists:officers,user_id'],
            'basic_salary' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'house_allowance' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'transport_allowance' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'mobile_allowance' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'other_allowance' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'deduction' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'effective_from' => ['required', 'date'],
            'status' => ['required', Rule::in(array_map(fn ($case) => $case->value, SalaryProfileStatus::cases()))],
        ];
    }

    /**
     * Only one salary profile should be "current" per officer. Activating
     * this one automatically deactivates any other Active profile for the
     * same officer, inside the same transaction.
     */
    public function save(): SalaryProfile
    {
        $this->validate();

        return DB::transaction(function () {
            $isNew = ! $this->editing;
            $profile = $this->editing ?? new SalaryProfile;
            $profile->fill([
                'officer_id' => $this->officer_id,
                'basic_salary' => $this->basic_salary,
                'house_allowance' => $this->house_allowance,
                'transport_allowance' => $this->transport_allowance,
                'mobile_allowance' => $this->mobile_allowance,
                'other_allowance' => $this->other_allowance,
                'deduction' => $this->deduction,
                'effective_from' => $this->effective_from,
                'status' => $this->status,
            ]);
            $profile->save();

            if ($profile->status === SalaryProfileStatus::Active) {
                SalaryProfile::where('officer_id', $profile->officer_id)
                    ->where('id', '!=', $profile->id)
                    ->where('status', SalaryProfileStatus::Active)
                    ->update(['status' => SalaryProfileStatus::Inactive]);
            }

            $officer = $profile->officer;
            ActivityLogger::log(
                action: $isNew ? 'created' : 'updated',
                module: 'Salary Profiles',
                description: Auth::user()->name.' '.($isNew ? 'created' : 'updated')." the salary profile for Officer {$officer?->name}",
                model: $profile,
                newValues: ['basic_salary' => (string) $profile->basic_salary, 'status' => $profile->status->value],
            );

            return $profile;
        });
    }
}
