<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'description', 'icon', 'color', 'criteria',
    ];

    protected $casts = [
        'criteria' => 'array',
    ];

    public function donors(): BelongsToMany
    {
        return $this->belongsToMany(Donor::class, 'donor_badge')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }
}
