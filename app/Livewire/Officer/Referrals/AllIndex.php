<?php

namespace App\Livewire\Officer\Referrals;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        $officerId = Auth::id();
        $cacheKey = "referral.stats.officer.{$officerId}";

        $stats = Cache::remember($cacheKey, 300, function () use ($officerId) {
            $totalReferrals = Referral::where('officer_id', $officerId)->count();

            $referralCodes = Referral::where('officer_id', $officerId)
                ->whereHas('customer.user', fn ($q) => $q->whereNotNull('referral_code'))
                ->with('customer.user')
                ->get()
                ->pluck('customer.user.referral_code')
                ->filter()
                ->unique()
                ->values()
                ->all();

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

            $allMemberCount = 0;
            if ($allTreeCodes->unique()->isNotEmpty()) {
                $allMemberCount = Referral::whereIn('referral_code', $allTreeCodes->unique()->values()->all())->count();
            }

            return [
                'totalReferrals' => $totalReferrals,
                'allMemberCount' => $allMemberCount,
                'referralCounts' => $counts,
                'treeCounts' => $treeCounts,
            ];
        });

        $referralTree = Cache::remember("referral.tree.officer.{$officerId}", 300, function () use ($officerId) {
            return $this->buildReferralTree($officerId);
        });

        return view('livewire.officer.referrals.all-index', [
            'referrals' => $referrals,
            'totalReferrals' => $stats['totalReferrals'],
            'allMemberCount' => $stats['allMemberCount'],
            'referralCounts' => $stats['referralCounts'],
            'treeCounts' => $stats['treeCounts'],
            'referralTree' => $referralTree,
        ]);
    }

    private function buildReferralTree(int $officerId): \Illuminate\Support\Collection
    {
        $officer = User::find($officerId);

        if (! $officer || ! $officer->referral_code) {
            return collect();
        }

        $rootCode = $officer->referral_code;

        $rootNode = [
            'name' => $officer->name,
            'code' => $rootCode,
            'registered_at' => $officer->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
            'members' => $this->getTreeMemberCount($rootCode),
            'children' => $this->buildChildren($rootCode, [$rootCode]),
        ];

        return collect([$rootNode]);
    }

    private function buildChildren(string $parentCode, array $seenCodes = []): \Illuminate\Support\Collection
    {
        if (in_array($parentCode, $seenCodes, true)) {
            return collect();
        }

        $children = collect();
        $seenCodes[] = $parentCode;

        $directRegistrations = Referral::where('referral_code', $parentCode)
            ->with('customer.user')
            ->get()
            ->map(fn (Referral $r) => $r->customer->user)
            ->filter()
            ->unique('id');

        foreach ($directRegistrations as $user) {
            $childCode = $user->referral_code;

            $children->push([
                'name' => $user->name,
                'code' => $childCode,
                'registered_at' => $user->created_at?->format('Y-m-d H:i:s') ?? now()->format('Y-m-d H:i:s'),
                'members' => $childCode ? $this->getTreeMemberCount($childCode) : 0,
                'children' => $childCode ? $this->buildChildren($childCode, $seenCodes) : collect(),
            ]);
        }

        return $children;
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
