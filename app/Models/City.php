<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function booted()
    {
        static::saved(fn () => Cache::forget('cities.all'));
        static::deleted(fn () => Cache::forget('cities.all'));
    }

    public static function getAllCached()
    {
        $cities = Cache::remember('cities.all', 3600, function () {
            return static::orderBy('name')->get(['id', 'name']);
        });

        // Failsafe: if cache returns malformed data, strings, or scalars, invalidate and re-query
        if (! $cities instanceof \Illuminate\Support\Collection ||
            ($cities->isNotEmpty() && ! (is_object($cities->first()) && isset($cities->first()->id)))) {
            Cache::forget('cities.all');
            $cities = static::orderBy('name')->get(['id', 'name']);
        }

        return $cities;
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function donors()
    {
        return $this->hasMany(Donor::class);
    }
}
