<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name', 'hospital', 'blood_group', 'city_id', 'units_required', 
        'contact_name', 'contact_phone', 'status'
    ];

    protected $casts = [
        'units_required' => 'integer',
        'city_id' => 'integer',
    ];

    public function callLogs()
    {
        return $this->hasMany(CallLog::class);
    }

    public function bloodDonations()
    {
        return $this->hasMany(BloodDonation::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
