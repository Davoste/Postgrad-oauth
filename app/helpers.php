<?php

use App\Models\Setting;

if (!function_exists('brand')) {
    /**
     * Get a branding value: DB setting (Admin > Branding) wins, falling back
     * to config/brand.php (env-based) defaults set at install time.
     *
     * Usage: brand('name'), brand('primary_color'), brand('logo_url'), brand('hero_image_url')
     */
    function brand(string $key, $default = null)
    {
        if ($key === 'logo_url') {
            return _brand_uploaded_asset_url('brand.logo_path', config('brand.logo_path'));
        }

        if ($key === 'hero_image_url') {
            // No packaged default here — an empty return means "no custom
            // hero image", and callers fall back to the CSS gradient.
            return _brand_uploaded_asset_url('brand.hero_image_path', null);
        }

        // On-disk filesystem path for the logo, for contexts that can't
        // fetch a URL (DomPDF with remote fetching disabled — used for the
        // admission letter / transcripts).
        if ($key === 'logo_disk_path') {
            $stored = Setting::get('brand.logo_path');
            $path = $stored ?: config('brand.logo_path');
            return $stored ? storage_path('app/public/' . ltrim($path, '/')) : public_path($path);
        }

        try {
            $fromDb = Setting::get('brand.' . $key);
            if ($fromDb !== null && $fromDb !== '') {
                return $fromDb;
            }
        } catch (\Throwable $e) {
            // settings table may not exist yet (e.g. mid-migration) — fall
            // through to config defaults rather than breaking the page.
        }

        return $default ?? config('brand.' . $key);
    }
}

if (!function_exists('_brand_uploaded_asset_url')) {
    /**
     * Resolve a branding file to a public URL. Admin-uploaded files (stored
     * on the 'public' disk under branding/) are served through the
     * /brand-asset/{path} route — NOT the storage:link symlink — because
     * that symlink is frequently missing on freshly deployed servers, which
     * is exactly why uploaded logos were showing as broken images. The
     * packaged default asset (shipped in /public) doesn't need any of this.
     */
    function _brand_uploaded_asset_url(string $settingKey, ?string $packagedDefault): ?string
    {
        $stored = Setting::get($settingKey);

        if (!$stored) {
            return $packagedDefault ? asset($packagedDefault) : null;
        }

        if (\Illuminate\Support\Str::startsWith($stored, ['http://', 'https://'])) {
            return $stored;
        }

        // $stored looks like "branding/xxxx.png" — strip the leading
        // "branding/" since the route already scopes to that folder.
        $relative = \Illuminate\Support\Str::after($stored, 'branding/');

        return route('brand.asset', ['path' => $relative]);
    }
}
