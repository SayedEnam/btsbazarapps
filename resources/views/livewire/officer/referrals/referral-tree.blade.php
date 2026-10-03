@php
    /** @var \Illuminate\Support\Collection<int, array{name: string, code: string, registered_at: string, members: int, children: array<int, mixed>}> $tree */
@endphp

@if ($tree->isNotEmpty())
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h4 mb-0">Referral Tree</h1>
                    <p class="text-muted mb-0 small">Nested referral structure by code</p>
                </div>
            </div>

            <div class="referral-tree-diagram">
                @foreach ($tree as $node)
                    @include('livewire.officer.referrals.partials.tree-node', ['node' => $node, 'level' => 0])
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .referral-tree-diagram {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .tree-node {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            width: 100%;
        }

        .tree-node-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-width: 140px;
            max-width: 220px;
            position: relative;
            z-index: 2;
        }

        .tree-node-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 1.1rem;
            background: linear-gradient(135deg, #dc3545, #a71d2a);
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }

        .tree-node-title {
            margin-top: .5rem;
            font-weight: 600;
            color: #212529;
            font-size: .95rem;
        }

        .tree-node-meta {
            display: flex;
            align-items: center;
            gap: .35rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: .25rem;
            font-size: .8rem;
            color: #6c757d;
        }

        .tree-children {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.25rem;
            position: relative;
            width: 100%;
        }

        .tree-children::before {
            content: '';
            position: absolute;
            left: 50%;
            top: -0.6rem;
            height: 0.6rem;
            width: 1px;
            background: #dc3545;
            transform: translateX(-50%);
        }

        .tree-children > .tree-node {
            position: relative;
            flex: 1 1 auto;
            min-width: 140px;
            max-width: 220px;
        }

        .tree-children > .tree-node::before {
            content: '';
            position: absolute;
            left: 50%;
            top: -0.6rem;
            width: 1px;
            height: 0.6rem;
            background: #dc3545;
            transform: translateX(-50%);
        }

        .tree-children > .tree-node::after {
            content: '';
            position: absolute;
            left: 50%;
            top: -0.6rem;
            height: 1px;
            background: #dc3545;
            transform: translateX(-50%);
        }

        .tree-children > .tree-node:first-child::after {
            left: 50%;
            width: 50%;
            transform: translateX(0);
        }

        .tree-children > .tree-node:last-child::after {
            left: 0;
            width: 50%;
            transform: translateX(0);
        }

        .tree-children > .tree-node:only-child::after {
            display: none;
        }

        .tree-children > .tree-node:only-child::before {
            height: calc(0.6rem + 0.5rem);
            top: calc(-0.6rem - 0.5rem);
        }

        .tree-node-level-0 .tree-node-avatar {
            background: linear-gradient(135deg, #dc3545, #a71d2a);
        }

        .tree-node-level-1 .tree-node-avatar {
            background: linear-gradient(135deg, #ffc107, #d39e00);
        }

        .tree-node-level-2 .tree-node-avatar {
            background: linear-gradient(135deg, #20c997, #0f6b56);
        }

        .tree-node-level-3 .tree-node-avatar {
            background: linear-gradient(135deg, #0dcaf0, #087a95);
        }

        .tree-node-level-4 .tree-node-avatar {
            background: linear-gradient(135deg, #6610f2, #3b0764);
        }
    </style>
@endif
