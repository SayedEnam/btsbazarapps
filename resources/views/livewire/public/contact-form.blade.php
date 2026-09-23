<div>
    @if ($sent)
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill fs-4"></i>
            <div>
                <strong>Message sent.</strong> Thank you for reaching out — we'll get back to you shortly.
            </div>
        </div>
    @endif

    <form wire:submit="send">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Address</label>
                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone <span class="text-muted small">(optional)</span></label>
                <input type="text" wire:model="phone" class="form-control @error('phone') is-invalid @enderror">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Subject <span class="text-muted small">(optional)</span></label>
                <input type="text" wire:model="subject" class="form-control @error('subject') is-invalid @enderror">
                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label">Message</label>
                <textarea wire:model="message" rows="5" class="form-control @error('message') is-invalid @enderror"></textarea>
                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-brand px-4" wire:loading.attr="disabled" wire:target="send">
                    <span wire:loading.remove wire:target="send">Send Message</span>
                    <span wire:loading wire:target="send">
                        <span class="spinner-border spinner-border-sm me-1"></span> Sending...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
