<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'quote', 'photo', 'blood_group',
        'city', 'donations_count', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'donations_count' => 'integer',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
