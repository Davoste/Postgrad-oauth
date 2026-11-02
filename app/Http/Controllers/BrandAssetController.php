<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

/**
 * Serves admin-uploaded branding files (logo, hero background) straight from
 * the 'public' disk. This deliberately does NOT depend on the storage:link
 * symlink existing (public/storage -> storage/app/public) — a very common
 * cause of "I uploaded a logo but it doesn't show up" after deploying to a
 * fresh server where that command was never run. Reading through Storage's
 * disk response works regardless of whether the symlink is there.
 */
class BrandAssetController extends Controller
{
    public function show(string $path)
    {
        $path = 'branding/' . ltrim($path, '/');

        // Guard against path traversal (../../etc) — only ever serve files
        // that actually live under the branding/ folder on the public disk.
        if (str_contains($path, '..') || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path, null, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
