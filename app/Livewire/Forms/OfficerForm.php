<?php

namespace App\Livewire\Forms;

use App\Enums\OfficerStatus;
use App\Enums\UserStatus;
use App\Models\Officer;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Form;
use Livewire\WithFileUploads;

class OfficerForm extends Form
{
    use WithFileUploads;

    public ?Officer $editing = null;

    public string $name = '';

    public string $email = '';

    public string $username = '';

    public string $phone = '';

    public string $password = '';

    public string $employee_id = '';

    public ?int $department_id = null;

    public ?int $designation_id = null;

    public string $address = '';

    public string $joining_date = '';

    public string $basic_salary = '';

    public string $status = 'active';

    public $profile_picture = null;

    public function setOfficer(?Officer $officer): void
    {
        $this->editing = $officer;

        if ($officer) {
            $officer->loadMissing('user');
            $this->name = $officer->user->name;
            $this->email = $officer->user->email;
            $this->username = (string) $officer->user->username;
            $this->phone = (string) $officer->user->phone;
            $this->employee_id = $officer->employee_id;
            $this->department_id = $officer->department_id;
            $this->designation_id = $officer->designation_id;
            $this->address = (string) $officer->address;
            $this->joining_date = $officer->joining_date?->format('Y-m-d') ?? '';
            $this->basic_salary = $officer->basic_salary !== null ? (string) $officer->basic_salary : '';
            $this->status = $officer->status->value;
        } else {
            $this->reset([
                'name', 'email', 'username', 'phone', 'password', 'employee_id', 'department_id', 'designation_id',
                'address', 'joining_date', 'basic_salary', 'status',
            ]);
            $this->status = OfficerStatus::Active->value;
            $this->employee_id = Officer::generateEmployeeId();
        }
    }

    public function rules(): array
    {
        $userId = $this->editing?->user_id;
        $officerId = $this->editing?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'username' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($userId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($userId)],
            'password' => [$this->editing ? 'nullable' : 'required', 'string', 'min:8'],
            'employee_id' => ['required', 'string', 'max:30', Rule::unique('officers', 'employee_id')->ignore($officerId)],
            'department_id' => ['nullable', 'exists:departments,id'],
            'designation_id' => ['nullable', 'exists:designations,id'],
            'address' => ['nullable', 'string', 'max:500'],
            'joining_date' => ['nullable', 'date'],
            'basic_salary' => ['nullable', 'numeric', 'min:0', 'max:9999999999999.99'],
            'status' => ['required', Rule::in(array_map(fn ($case) => $case->value, OfficerStatus::cases()))],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Create/update the User account and Officer profile in one transaction
     * — an Officer row can never exist without its User, and vice versa.
     */
    public function save(): Officer
    {
        $this->validate();

        return DB::transaction(function () {
            $isNewUser = ! $this->editing;
            $user = $this->editing?->user ?? new User;

            $user->fill([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'phone' => $this->phone,
            ]);

            // Account status (Active/Inactive/Suspended) is managed on the
            // Users screen, not here — an edit to department/salary/etc.
            // must never silently reactivate a suspended officer's account.
            if ($isNewUser) {
                $user->status = UserStatus::Active;
            }

            if ($this->password !== '') {
                $user->password = Hash::make($this->password);
            }

            $user->save();

            if ($isNewUser) {
                $user->email_verified_at = now();

                $officerRole = Role::where('slug', Role::MARKETING_OFFICER)->firstOrFail();
                $user->roles()->syncWithoutDetaching([$officerRole->id]);

                $user->referral_code = User::generateReferralCode();
                $user->save();
            }

            $officer = $this->editing ?? new Officer;
            $officer->fill([
                'user_id' => $user->id,
                'employee_id' => $this->employee_id,
                'department_id' => $this->department_id,
                'designation_id' => $this->designation_id,
                'address' => $this->address ?: null,
                'joining_date' => $this->joining_date ?: null,
                'basic_salary' => $this->basic_salary !== '' ? $this->basic_salary : null,
                'status' => $this->status,
            ]);

            if ($this->profile_picture) {
                $officer->profile_picture = $this->profile_picture->store('officers', 'public');
            }

            $officer->save();

            return $officer;
        });
    }
}
