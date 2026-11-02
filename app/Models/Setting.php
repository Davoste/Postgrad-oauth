<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings.all'));
        static::deleted(fn () => Cache::forget('settings.all'));
    }

    /** All settings as a flat [key => value] map, cached. */
    public static function allCached(): array
    {
        return Cache::rememberForever('settings.all', function () {
            return static::query()->pluck('value', 'key')->all();
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = static::allCached();
        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public static function set(string $key, ?string $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }

    public static function setMany(array $pairs, string $group = 'general'): void
    {
        foreach ($pairs as $key => $value) {
            static::set($key, $value, $group);
        }
    }

    /**
     * Bulk-clear every setting in a group (e.g. resetting all branding
     * overrides). Uses a query-builder delete for efficiency, which does NOT
     * fire per-model 'deleted' events — so the cache must be forgotten here
     * explicitly, or a reset would silently keep serving stale values from
     * the 'settings.all' cache until something else happened to bust it.
     */
    public static function clearGroup(string $group): void
    {
        static::query()->where('group', $group)->delete();
        Cache::forget('settings.all');
    }
}
