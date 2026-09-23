<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    protected const CACHE_KEY = 'settings.all';

    /**
     * Defaults for the theme/appearance settings — chosen to exactly match
     * what was previously hardcoded in the layout `<style>` blocks, so
     * turning on theme customization changes nothing visually until an
     * admin actually edits a value.
     */
    public const THEME_DEFAULTS = [
        'theme_primary_color' => '#146356',
        'theme_primary_dark_color' => '#0f3d3e',
        'theme_primary_light_color' => '#1b8a6b',
        'theme_footer_bg_color' => '#0f3d3e',
        'theme_footer_text_color' => '#e6e6e6',
        'theme_h1_size' => '2.5rem',
        'theme_h2_size' => '2rem',
        'theme_h3_size' => '1.75rem',
        'theme_h4_size' => '1.5rem',
        'theme_h5_size' => '1.25rem',
        'theme_h6_size' => '1rem',
        'theme_body_font_size' => '1rem',
    ];

    /**
     * A theme setting with its own built-in default (see THEME_DEFAULTS) —
     * so callers never need to repeat the default value themselves.
     */
    public static function theme(string $key): string
    {
        return (string) static::get($key, static::THEME_DEFAULTS[$key] ?? '');
    }

    /**
     * Get a setting value by key, cached for the whole request lifecycle
     * and beyond (until the next write). Falls back to $default when the
     * key doesn't exist yet, so views never need to hardcode company info.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::allCached()->get($key, $default);
    }

    /**
     * Set (create or update) a setting value and refresh the cache.
     */
    public static function set(string $key, ?string $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);

        Cache::forget(static::CACHE_KEY);
    }

    public static function logoUrl(): ?string
    {
        $path = static::get('logo_path');

        return $path ? asset('storage/'.$path) : null;
    }

    public static function faviconUrl(): ?string
    {
        $path = static::get('favicon_path');

        return $path ? asset('storage/'.$path) : null;
    }

    /**
     * @return \Illuminate\Support\Collection<string, string|null>
     */
    protected static function allCached(): \Illuminate\Support\Collection
    {
        return Cache::rememberForever(static::CACHE_KEY, fn () => static::query()->pluck('value', 'key'));
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(static::CACHE_KEY));
        static::deleted(fn () => Cache::forget(static::CACHE_KEY));
    }
}
