<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'venue', 'target_units', 'description', 'is_featured'];

    protected $casts = [
        'date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function donations()
    {
        return $this->hasMany(BloodDonation::class);
    }

    public function moneyDonations()
    {
        return $this->hasMany(MoneyDonation::class);
    }
}
