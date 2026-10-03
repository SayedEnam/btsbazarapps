@php
    /** @var array{name: string, code: string, registered_at: string, members: int, children: array<int, mixed>} $node */
    /** @var int $level */
@endphp

<div class="tree-node tree-node-level-{{ min($level, 4) }}">
    <div class="tree-node-card">
        <div class="tree-node-avatar">{{ strtoupper(substr($node['name'], 0, 1)) }}</div>
        <div class="tree-node-title">{{ $node['name'] }}</div>
        <div class="tree-node-meta">
            <code>{{ $node['code'] }}</code>
            <span class="text-muted">Reg: {{ \Carbon\Carbon::parse($node['registered_at'])->format('d M Y') }}</span>
        </div>
        <div class="tree-node-meta">
            <span class="badge bg-primary">Members: {{ $node['members'] }}</span>
        </div>
    </div>

    @if ($node['children']->isNotEmpty())
        <div class="tree-children">
            @foreach ($node['children'] as $child)
                @include('livewire.officer.referrals.partials.tree-node', ['node' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
