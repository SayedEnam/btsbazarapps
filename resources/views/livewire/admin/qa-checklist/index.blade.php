<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">QA Checklist</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <p class="mb-2">
                A screenshot record of every admin module's List, Add, Edit, and View screens, captured in one
                QA sweep so regressions in layout or missing fields are easy to spot at a glance. Click any
                screenshot to open it full-size in a new tab.
            </p>
            <p class="text-muted small mb-0">
                Captured {{ $capturedAt }} &middot; {{ count($modules) }} modules
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="h6 text-uppercase text-muted small fw-bold mb-3">Jump to a module</h2>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($modules as $module)
                    <a href="#module-{{ \Illuminate\Support\Str::slug($module['module']) }}" class="btn btn-sm btn-outline-secondary">
                        {{ $module['module'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @foreach ($modules as $module)
        <div class="card mb-4" id="module-{{ \Illuminate\Support\Str::slug($module['module']) }}">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h2 class="h6 mb-0">{{ $module['module'] }}</h2>
                @if ($module['route'] && Route::has($module['route']))
                    <a href="{{ route($module['route']) }}" wire:navigate class="small text-brand text-decoration-none">
                        Open module <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($module['shots'] as $shot)
                        <div class="col-md-6 col-lg-4">
                            <div class="text-muted small fw-semibold mb-1">{{ $shot['label'] }}</div>
                            <a href="{{ asset('images/qa-checklist/'.$shot['file']) }}" target="_blank" rel="noopener">
                                <img
                                    src="{{ asset('images/qa-checklist/'.$shot['file']) }}"
                                    alt="{{ $module['module'] }} — {{ $shot['label'] }}"
                                    class="img-fluid rounded border"
                                    loading="lazy"
                                >
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
