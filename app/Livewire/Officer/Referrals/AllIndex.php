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

        $referralCodes = $referrals->pluck('customer.user.referral_code')->filter()->unique()->values()->all();

        $counts = [];
        $treeCounts = [];
        if (! empty($referralCodes)) {
            $counts = Referral::whereIn('referral_code', $referralCodes)
                ->select('referral_code', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('referral_code')
                ->pluck('total', 'referral_code')
                ->all();

            foreach ($referralCodes as $code) {
                $treeCounts[$code] = $this->getTreeMemberCount($code);
            }
        }

        return view('livewire.officer.referrals.all-index', [
            'referrals' => $referrals,
            'totalReferrals' => Referral::where('officer_id', Auth::id())->count(),
            'referralCounts' => $counts,
            'treeCounts' => $treeCounts,
        ]);
    }

    private function getTreeMemberCount(string $rootCode): int
    {
        $codes = collect([$rootCode]);
        $queue = collect([$rootCode]);
        $seen = collect([$rootCode]);

        while ($queue->isNotEmpty()) {
            $code = $queue->shift();

            $children = User::whereHas('roles', fn ($q) => $q->where('slug', \App\Models\Role::MARKETING_OFFICER))
                ->where('referral_code', '!=', $code)
                ->whereIn('id', function ($query) use ($code) {
                    $query->select('officer_id')->from('referrals')->where('referral_code', $code);
                })
                ->whereNotNull('referral_code')
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

        return Referral::whereIn('referral_code', $codes->unique()->values()->all())->count();
    }
}
