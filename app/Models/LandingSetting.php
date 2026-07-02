<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null): ?string
    {
        $settings = static::getAll();
        return $settings[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('landing_settings');
    }

    public static function getAll(): array
    {
        return Cache::rememberForever('landing_settings', function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('landing_settings'));
        static::deleted(fn () => Cache::forget('landing_settings'));
    }
}
