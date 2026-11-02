<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function edit()
    {
        $fields = [
            'name', 'short_name', 'tagline', 'favicon_emoji',
            'primary_color', 'accent_color', 'gold_color',
            'support_email', 'support_phone', 'address', 'footer_text',
        ];

        $values = collect($fields)->mapWithKeys(fn ($f) => [$f => brand($f)]);

        return view('admin.branding', [
            'values'       => $values,
            'logoUrl'      => brand('logo_url'),
            'heroImageUrl' => brand('hero_image_url'),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'short_name'     => ['required', 'string', 'max:50'],
            'tagline'        => ['nullable', 'string', 'max:150'],
            'favicon_emoji'  => ['nullable', 'string', 'max:8'],
            'primary_color'  => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color'   => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'gold_color'     => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'support_email'  => ['nullable', 'email', 'max:150'],
            'support_phone'  => ['nullable', 'string', 'max:30'],
            'address'        => ['nullable', 'string', 'max:255'],
            'footer_text'    => ['nullable', 'string', 'max:255'],
            'logo'           => ['nullable', 'image', 'max:2048'],
            'hero_image'     => ['nullable', 'image', 'max:4096'],
            'remove_hero_image' => ['nullable', 'boolean'],
        ]);

        unset($validated['logo'], $validated['hero_image'], $validated['remove_hero_image']);

        foreach ($validated as $key => $value) {
            Setting::set('brand.' . $key, $value, 'brand');
        }

        if ($request->hasFile('logo')) {
            $this->replaceUpload($request->file('logo'), 'brand.logo_path');
        }

        if ($request->hasFile('hero_image')) {
            $this->replaceUpload($request->file('hero_image'), 'brand.hero_image_path');
        } elseif ($request->boolean('remove_hero_image')) {
            $this->deleteUpload('brand.hero_image_path');
            Setting::set('brand.hero_image_path', null, 'brand');
        }

        return redirect()->route('admin.branding.edit')->with('success', 'Branding updated. Changes apply portal-wide immediately — no restart needed.');
    }

    public function reset()
    {
        $this->deleteUpload('brand.logo_path');
        $this->deleteUpload('brand.hero_image_path');
        Setting::clearGroup('brand');

        return redirect()->route('admin.branding.edit')->with('success', 'Branding reset to install defaults.');
    }

    private function replaceUpload($file, string $settingKey): void
    {
        $this->deleteUpload($settingKey);
        $path = $file->store('branding', 'public');
        Setting::set($settingKey, $path, 'brand');
    }

    private function deleteUpload(string $settingKey): void
    {
        $existing = Setting::get($settingKey);
        if ($existing && Storage::disk('public')->exists($existing)) {
            Storage::disk('public')->delete($existing);
        }
    }
}
