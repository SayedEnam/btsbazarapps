<div>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 mb-0">Settings</h1>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Logo</h2></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo" style="max-height: 56px; max-width: 200px;" class="border rounded p-1">
                        @else
                            <div class="text-muted small">No logo uploaded yet — the site shows a default icon.</div>
                        @endif
                    </div>
                    <form wire:submit="uploadLogo" class="d-flex align-items-start gap-2 flex-wrap">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <input type="file" wire:model="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="logo" class="small text-muted mt-1">Uploading...</div>
                        </div>
                        <button type="submit" class="btn btn-brand" wire:loading.attr="disabled" wire:target="uploadLogo">Upload</button>
                        @if ($logoUrl)
                            <button type="button" class="btn btn-outline-danger" wire:click="removeLogo" wire:confirm="Remove the current logo?">Remove</button>
                        @endif
                    </form>
                    <div class="small text-muted mt-2">PNG, JPG, or SVG — up to 1MB.</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Favicon</h2></div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if ($faviconUrl)
                            <img src="{{ $faviconUrl }}" alt="Favicon" style="height: 32px; width: 32px;" class="border rounded p-1">
                        @else
                            <div class="text-muted small">No favicon uploaded yet — the browser tab shows the default icon.</div>
                        @endif
                    </div>
                    <form wire:submit="uploadFavicon" class="d-flex align-items-start gap-2 flex-wrap">
                        <div class="flex-grow-1" style="min-width: 200px;">
                            <input type="file" wire:model="favicon" class="form-control @error('favicon') is-invalid @enderror" accept=".ico,.png,.svg,.jpg,.jpeg">
                            @error('favicon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div wire:loading wire:target="favicon" class="small text-muted mt-1">Uploading...</div>
                        </div>
                        <button type="submit" class="btn btn-brand" wire:loading.attr="disabled" wire:target="uploadFavicon">Upload</button>
                        @if ($faviconUrl)
                            <button type="button" class="btn btn-outline-danger" wire:click="removeFavicon" wire:confirm="Remove the current favicon?">Remove</button>
                        @endif
                    </form>
                    <div class="small text-muted mt-2">ICO, PNG, or SVG — up to 512KB. A square image works best.</div>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit="save">
        <div class="row g-3">
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-white"><h2 class="h6 mb-0">Company Information</h2></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" wire:model.blur="company_name" class="form-control @error('company_name') is-invalid @enderror">
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tagline</label>
                            <input type="text" wire:model.blur="tagline" class="form-control @error('tagline') is-invalid @enderror">
                            @error('tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" wire:model.blur="phone" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model.blur="email" class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea wire:model.blur="address" rows="2" class="form-control @error('address') is-invalid @enderror"></textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Google Map Embed URL</label>
                            <input type="text" wire:model.blur="google_map_embed" class="form-control @error('google_map_embed') is-invalid @enderror" placeholder="https://www.google.com/maps/embed?...">
                            @error('google_map_embed') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card mb-3">
                    <div class="card-header bg-white"><h2 class="h6 mb-0">Social & Contact Links</h2></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Facebook URL</label>
                            <input type="text" wire:model.blur="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror">
                            @error('facebook_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">YouTube URL</label>
                            <input type="text" wire:model.blur="youtube_url" class="form-control @error('youtube_url') is-invalid @enderror">
                            @error('youtube_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" wire:model.blur="whatsapp_number" class="form-control @error('whatsapp_number') is-invalid @enderror" placeholder="8801700000000">
                            @error('whatsapp_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-white"><h2 class="h6 mb-0">General</h2></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Footer Text</label>
                            <input type="text" wire:model.blur="footer_text" class="form-control @error('footer_text') is-invalid @enderror">
                            @error('footer_text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Currency Symbol</label>
                                <input type="text" wire:model.blur="currency_symbol" class="form-control @error('currency_symbol') is-invalid @enderror">
                                @error('currency_symbol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">Timezone</label>
                                <input type="text" wire:model.blur="timezone" class="form-control @error('timezone') is-invalid @enderror">
                                @error('timezone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h2 class="h6 mb-0">Theme &amp; Appearance</h2>
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetTheme" wire:confirm="Reset all theme colors and text sizes back to the defaults?">
                    Reset to Defaults
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted small">
                    Controls the public site's colors and heading/paragraph sizes — the hero banner and buttons
                    follow the Primary colors below, so changing them updates the hero automatically. The admin
                    panel itself keeps its own fixed colors.
                </p>

                <h3 class="h6 text-uppercase text-muted small fw-bold mt-4 mb-3">Colors</h3>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <label class="form-label">Primary Color</label>
                        <div class="input-group">
                            <input type="color" wire:model.blur="theme_primary_color" class="form-control form-control-color @error('theme_primary_color') is-invalid @enderror" style="max-width: 3.5rem;">
                            <input type="text" wire:model.blur="theme_primary_color" class="form-control @error('theme_primary_color') is-invalid @enderror">
                        </div>
                        @error('theme_primary_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Primary Dark</label>
                        <div class="input-group">
                            <input type="color" wire:model.blur="theme_primary_dark_color" class="form-control form-control-color @error('theme_primary_dark_color') is-invalid @enderror" style="max-width: 3.5rem;">
                            <input type="text" wire:model.blur="theme_primary_dark_color" class="form-control @error('theme_primary_dark_color') is-invalid @enderror">
                        </div>
                        @error('theme_primary_dark_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Primary Light</label>
                        <div class="input-group">
                            <input type="color" wire:model.blur="theme_primary_light_color" class="form-control form-control-color @error('theme_primary_light_color') is-invalid @enderror" style="max-width: 3.5rem;">
                            <input type="text" wire:model.blur="theme_primary_light_color" class="form-control @error('theme_primary_light_color') is-invalid @enderror">
                        </div>
                        @error('theme_primary_light_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        <div class="form-text">Hero banner: a gradient across these three colors.</div>
                    </div>
                    <div class="col-6 col-md-3"></div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Footer Background</label>
                        <div class="input-group">
                            <input type="color" wire:model.blur="theme_footer_bg_color" class="form-control form-control-color @error('theme_footer_bg_color') is-invalid @enderror" style="max-width: 3.5rem;">
                            <input type="text" wire:model.blur="theme_footer_bg_color" class="form-control @error('theme_footer_bg_color') is-invalid @enderror">
                        </div>
                        @error('theme_footer_bg_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Footer Text</label>
                        <div class="input-group">
                            <input type="color" wire:model.blur="theme_footer_text_color" class="form-control form-control-color @error('theme_footer_text_color') is-invalid @enderror" style="max-width: 3.5rem;">
                            <input type="text" wire:model.blur="theme_footer_text_color" class="form-control @error('theme_footer_text_color') is-invalid @enderror">
                        </div>
                        @error('theme_footer_text_color') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                @php
                    $headingSizeOptions = ['1rem', '1.25rem', '1.5rem', '1.75rem', '2rem', '2.25rem', '2.5rem', '2.75rem', '3rem', '3.5rem', '4rem'];
                    $bodySizeOptions = ['0.875rem', '1rem', '1.125rem', '1.25rem'];
                @endphp

                <h3 class="h6 text-uppercase text-muted small fw-bold mt-4 mb-3">Heading &amp; Paragraph Sizes</h3>
                <div class="row g-3">
                    @foreach (['theme_h1_size' => 'H1', 'theme_h2_size' => 'H2', 'theme_h3_size' => 'H3', 'theme_h4_size' => 'H4', 'theme_h5_size' => 'H5', 'theme_h6_size' => 'H6'] as $field => $label)
                        <div class="col-6 col-md-2">
                            <label class="form-label">{{ $label }}</label>
                            <select wire:model.blur="{{ $field }}" class="form-select @error($field) is-invalid @enderror">
                                @foreach ($headingSizeOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                    <div class="col-6 col-md-2">
                        <label class="form-label">Paragraph</label>
                        <select wire:model.blur="theme_body_font_size" class="form-select @error('theme_body_font_size') is-invalid @enderror">
                            @foreach ($bodySizeOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @error('theme_body_font_size') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-brand" wire:loading.attr="disabled" wire:target="save">Save Settings</button>
        </div>
    </form>
</div>
