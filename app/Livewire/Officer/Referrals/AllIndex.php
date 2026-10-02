<?php

namespace App\Livewire\Officer\Referrals;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.officer')]
#[Title('All Referrals')]
class AllIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $referrals = Referral::query()
            ->where('officer_id', Auth::id())
            ->with(['customer.user', 'customer.applications.designation', 'customer.applications.package', 'officer.officer.designation'])
            ->when($this->search, fn ($query) => $query->whereHas('customer.user', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($query) => $query->whereHas('customer', function ($q) {
                $q->where('status', $this->statusFilter);
            }))
            ->latest('registered_at')
            ->paginate(10);

        $allOfficerReferrals = Referral::where('officer_id', Auth::id())->get();
        $referralCodes = $allOfficerReferrals->pluck('customer.user.referral_code')->filter()->unique()->values()->all();

        $counts = [];
        $treeCounts = [];
        $allTreeCodes = collect();

        if (! empty($referralCodes)) {
            $counts = Referral::whereIn('referral_code', $referralCodes)
                ->select('referral_code', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('referral_code')
                ->pluck('total', 'referral_code')
                ->all();

            foreach ($referralCodes as $code) {
                $treeCodes = $this->getTreeReferralCodes($code);
                $treeCounts[$code] = Referral::whereIn('referral_code', $treeCodes)->count();
                $allTreeCodes = $allTreeCodes->merge($treeCodes);
            }
        }

        return view('livewire.officer.referrals.all-index', [
            'referrals' => $referrals,
            'totalReferrals' => $allOfficerReferrals->count(),
            'allMemberCount' => $allTreeCodes->unique()->isNotEmpty() ? Referral::whereIn('referral_code', $allTreeCodes->unique()->values()->all())->count() : 0,
            'referralCounts' => $counts,
            'treeCounts' => $treeCounts,
        ]);
    }

    private function getTreeMemberCount(string $rootCode): int
    {
        $codes = $this->getTreeReferralCodes($rootCode);

        return Referral::whereIn('referral_code', $codes)->count();
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
