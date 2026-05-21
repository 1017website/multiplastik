<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    /** In-memory cache untuk 1 request — hindari N+1 */
    protected static ?array $memo = null;

    public static function get(string $key, $default = null)
    {
        if (static::$memo === null) {
            static::$memo = Cache::remember('site_settings', 3600, function () {
                return self::all()->pluck('value', 'key')->toArray();
            });
        }
        return static::$memo[$key] ?? $default;
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'text'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
        Cache::forget('site_settings');
        static::$memo = null;
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('site_settings');
            static::$memo = null;
        });
        static::deleted(function () {
            Cache::forget('site_settings');
            static::$memo = null;
        });
    }
}