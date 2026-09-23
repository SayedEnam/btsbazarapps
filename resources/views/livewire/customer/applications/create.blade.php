<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Apply for a Package</h1>
    </div>

    @if ($packages->isEmpty())
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-box-seam display-4 d-block mb-3"></i>
                No packages are available to apply for right now. Please check back soon.
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($packages as $package)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold">{{ $package->name }}</h5>
                            <div class="display-6 fw-bold text-brand my-2">
                                ৳{{ number_format((float) $package->price) }}
                                @if ($package->duration)
                                    <span class="fs-6 text-muted fw-normal">/ {{ strtolower($package->duration) }}</span>
                                @endif
                            </div>
                            <p class="text-muted small flex-grow-1">{{ $package->description }}</p>
                            <button type="button" class="btn btn-brand mt-2" wire:click="apply({{ $package->id }})" wire:loading.attr="disabled" wire:target="apply({{ $package->id }})">
                                Apply for This Package
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <label class="form-label">Notes for the reviewing officer <span class="text-muted small">(optional, applies to whichever package you choose above)</span></label>
            <textarea wire:model="notes" rows="2" class="form-control" style="max-width: 600px;"></textarea>
        </div>
    @endif
</div>
