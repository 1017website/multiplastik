<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return self::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'text'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
        Cache::forget('site_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('site_settings'));
        static::deleted(fn() => Cache::forget('site_settings'));
    }
}
