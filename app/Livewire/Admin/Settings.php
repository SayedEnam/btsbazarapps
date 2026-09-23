<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ValidatesOnUpdate;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Settings')]
class Settings extends Component
{
    use WithFileUploads, ValidatesOnUpdate;

    public string $company_name = '';

    public string $tagline = '';

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $google_map_embed = '';

    public string $facebook_url = '';

    public string $youtube_url = '';

    public string $whatsapp_number = '';

    public string $footer_text = '';

    public string $currency_symbol = '';

    public string $timezone = '';

    public string $theme_primary_color = '';

    public string $theme_primary_dark_color = '';

    public string $theme_primary_light_color = '';

    public string $theme_footer_bg_color = '';

    public string $theme_footer_text_color = '';

    public string $theme_h1_size = '';

    public string $theme_h2_size = '';

    public string $theme_h3_size = '';

    public string $theme_h4_size = '';

    public string $theme_h5_size = '';

    public string $theme_h6_size = '';

    public string $theme_body_font_size = '';

    public $logo = null;

    public $favicon = null;

    public function mount(): void
    {
        Gate::authorize('settings.view');

        foreach ($this->fields() as $key) {
            $this->{$key} = (string) Setting::get($key, '');
        }

        foreach (Setting::THEME_DEFAULTS as $key => $default) {
            $this->{$key} = Setting::theme($key);
        }
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'google_map_embed' => ['nullable', 'string'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'currency_symbol' => ['required', 'string', 'max:5'],
            'timezone' => ['required', 'string', 'max:100'],

            'theme_primary_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_primary_dark_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_primary_light_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_footer_bg_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_footer_text_color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'theme_h1_size' => ['required', 'string', 'regex:/^\d+(\.\d+)?(rem|px|em)$/'],
            'theme_h2_size' => ['required', 'string', 'regex:/^\d+(\.\d+)?(rem|px|em)$/'],
            'theme_h3_size' => ['required', 'string', 'regex:/^\d+(\.\d+)?(rem|px|em)$/'],
            'theme_h4_size' => ['required', 'string', 'regex:/^\d+(\.\d+)?(rem|px|em)$/'],
            'theme_h5_size' => ['required', 'string', 'regex:/^\d+(\.\d+)?(rem|px|em)$/'],
            'theme_h6_size' => ['required', 'string', 'regex:/^\d+(\.\d+)?(rem|px|em)$/'],
            'theme_body_font_size' => ['required', 'string', 'regex:/^\d+(\.\d+)?(rem|px|em)$/'],
        ];
    }

    public function save(): void
    {
        Gate::authorize('settings.edit');

        $validated = $this->validate();

        $groups = [
            'company_name' => 'company', 'tagline' => 'company', 'phone' => 'company',
            'email' => 'company', 'address' => 'company', 'google_map_embed' => 'company',
            'facebook_url' => 'social', 'youtube_url' => 'social', 'whatsapp_number' => 'social',
            'footer_text' => 'general', 'currency_symbol' => 'general', 'timezone' => 'general',
        ];

        foreach ($validated as $key => $value) {
            $group = $groups[$key] ?? (str_starts_with($key, 'theme_') ? 'theme' : 'general');
            Setting::set($key, $value, $group);
        }

        $this->dispatch('notify', type: 'success', message: 'Settings updated successfully.');
    }

    public function resetTheme(): void
    {
        Gate::authorize('settings.edit');

        foreach (Setting::THEME_DEFAULTS as $key => $default) {
            Setting::set($key, $default, 'theme');
            $this->{$key} = $default;
        }

        $this->dispatch('notify', type: 'success', message: 'Theme reset to defaults.');
    }

    public function uploadLogo(): void
    {
        Gate::authorize('settings.edit');

        $this->validate(['logo' => ['required', 'image', 'max:1024']]);

        $path = $this->logo->store('branding', 'public');
        Setting::set('logo_path', $path, 'branding');

        $this->logo = null;
        $this->dispatch('notify', type: 'success', message: 'Logo updated successfully.');
    }

    public function removeLogo(): void
    {
        Gate::authorize('settings.edit');

        Setting::set('logo_path', '', 'branding');
        $this->dispatch('notify', type: 'success', message: 'Logo removed.');
    }

    /**
     * `image` alone won't accept .ico — most favicon files are .ico or a
     * small .png/.svg, so this validates the mimes actually used for
     * favicons instead of the broader photo-upload `image` rule.
     */
    public function uploadFavicon(): void
    {
        Gate::authorize('settings.edit');

        $this->validate(['favicon' => ['required', 'file', 'mimes:ico,png,svg,jpg,jpeg', 'max:512']]);

        $path = $this->favicon->store('branding', 'public');
        Setting::set('favicon_path', $path, 'branding');

        $this->favicon = null;
        $this->dispatch('notify', type: 'success', message: 'Favicon updated successfully.');
    }

    public function removeFavicon(): void
    {
        Gate::authorize('settings.edit');

        Setting::set('favicon_path', '', 'branding');
        $this->dispatch('notify', type: 'success', message: 'Favicon removed.');
    }

    /**
     * @return array<int, string>
     */
    protected function fields(): array
    {
        return [
            'company_name', 'tagline', 'phone', 'email', 'address', 'google_map_embed',
            'facebook_url', 'youtube_url', 'whatsapp_number', 'footer_text', 'currency_symbol', 'timezone',
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings', [
            'logoUrl' => Setting::logoUrl(),
            'faviconUrl' => Setting::faviconUrl(),
        ]);
    }
}
