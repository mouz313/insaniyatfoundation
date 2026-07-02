<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function donors()
    {
        return $this->hasMany(Donor::class);
    }
}
