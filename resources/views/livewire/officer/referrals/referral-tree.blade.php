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

            <div class="referral-tree">
                @foreach ($tree as $node)
                    @include('livewire.officer.referrals.partials.tree-node', ['node' => $node, 'level' => 0])
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .referral-tree {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tree-node {
            position: relative;
            padding-left: 1.5rem;
        }

        .tree-node::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 1px;
            background: #dee2e6;
        }

        .tree-node::after {
            content: '';
            position: absolute;
            left: 0;
            top: 1.25rem;
            width: 1rem;
            height: 1px;
            background: #dee2e6;
        }

        .tree-node-card {
            border: 1px solid #e9ecef;
            border-radius: .5rem;
            padding: .75rem 1rem;
            background: #fff;
            position: relative;
            z-index: 1;
        }

        .tree-node-title {
            font-weight: 600;
            color: #212529;
        }

        .tree-node-meta {
            display: flex;
            align-items: center;
            gap: .5rem;
            flex-wrap: wrap;
            margin-top: .25rem;
            font-size: .85rem;
            color: #6c757d;
        }

        .tree-children {
            margin-top: .75rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .tree-children::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 1px;
            background: #dee2e6;
        }

        .tree-children > .tree-node {
            margin-top: .75rem;
        }

        .tree-children > .tree-node::before {
            content: '';
            position: absolute;
            left: 0;
            top: 1.25rem;
            width: 1rem;
            height: 1px;
            background: #dee2e6;
        }
    </style>
@endif
