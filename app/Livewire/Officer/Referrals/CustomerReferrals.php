<?php

namespace App\Livewire\Officer\Referrals;

use App\Models\Customer;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.officer')]
#[Title('Customer Referrals')]
class CustomerReferrals extends Component
{
    use WithPagination;

    public ?Customer $customer = null;

    public string $search = '';

    public function mount(int $customerId): void
    {
        $customer = Customer::where('id', $customerId)->firstOrFail();

        $allowed = $customer->referral()->where('officer_id', Auth::id())->exists()
            || $this->isInOfficerTree($customer);

        if (! $allowed) {
            abort(404);
        }

        $this->customer = $customer;
    }

    private function isInOfficerTree(Customer $customer): bool
    {
        $referral = $customer->referral()->with('officer')->first();

        if (! $referral || ! $referral->officer) {
            return false;
        }

        $officerUserId = $referral->officer->id;
        $queue = collect([$officerUserId]);
        $seen = collect([$officerUserId]);

        while ($queue->isNotEmpty()) {
            $userId = $queue->shift();

            if ($userId === Auth::id()) {
                return true;
            }

            $parentReferral = Referral::where('customer_id', function ($query) use ($userId) {
                $query->select('id')->from('customers')->where('user_id', $userId)->limit(1);
            })->first();

            if ($parentReferral && $parentReferral->officer && ! $seen->contains($parentReferral->officer->id)) {
                $seen->push($parentReferral->officer->id);
                $queue->push($parentReferral->officer->id);
            }
        }

        return false;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $rootCode = $this->customer->user->referral_code;

        $referrals = Referral::query()
            ->where('referral_code', $rootCode)
            ->with(['customer.user', 'customer.applications.designation'])
            ->when($this->search, fn ($query) => $query->whereHas('customer.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->latest('registered_at')
            ->paginate(10);

        $treeCodes = $rootCode ? $this->getTreeReferralCodes($rootCode) : [];
        $counts = [];
        $treeCounts = [];
        $allTreeCodes = collect();

        if (! empty($treeCodes)) {
            $counts = Referral::whereIn('referral_code', $treeCodes)
                ->select('referral_code', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('referral_code')
                ->pluck('total', 'referral_code')
                ->all();

            foreach ($treeCodes as $code) {
                $branchCodes = $this->getTreeReferralCodes($code);
                $treeCounts[$code] = Referral::whereIn('referral_code', $branchCodes)->count();
                $allTreeCodes = $allTreeCodes->merge($branchCodes);
            }
        }

        $totalReferrals = Referral::where('referral_code', $rootCode)->count();
        $allMemberCount = 0;

        if ($allTreeCodes->unique()->isNotEmpty()) {
            $allMemberCount = max(0, Referral::whereIn('referral_code', $allTreeCodes->unique()->values()->all())->count() - $totalReferrals);
        }

        return view('livewire.officer.referrals.customer-referrals', [
            'customer' => $this->customer,
            'referrals' => $referrals,
            'totalReferrals' => $totalReferrals,
            'allMemberCount' => $allMemberCount,
            'referralCounts' => $counts,
            'treeCounts' => $treeCounts,
        ]);
    }

    private function getReferralCodes(string $rootCode): array
    {
        return $this->getTreeReferralCodes($rootCode);
    }

    private function getTreeReferralCodes(string $rootCode): array
    {
        $codes = collect([$rootCode]);
        $queue = collect([$rootCode]);
        $seen = collect([$rootCode]);

        while ($queue->isNotEmpty()) {
            $code = $queue->shift();

            $children = User::where('referral_code', '!=', $code)
                ->whereNotNull('referral_code')
                ->whereIn('id', function ($query) use ($code) {
                    $query->select('user_id')
                        ->from('customers')
                        ->whereIn('id', function ($q2) use ($code) {
                            $q2->select('customer_id')->from('referrals')->where('referral_code', $code);
                        });
                })
                ->pluck('referral_code')
                ->filter()
                ->unique()
                ->values()
                ->all();

            foreach ($children as $childCode) {
                if (! $seen->contains($childCode)) {
                    $seen->push($childCode);
                    $codes->push($childCode);
                    $queue->push($childCode);
                }
            }
        }

        return $codes->unique()->values()->all();
    }
}
