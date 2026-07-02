<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'anonymous_name', 'amount', 'donation_date', 
        'payment_method', 'campaign_id', 'receipt_number'
    ];

    protected $casts = [
        'donation_date' => 'date',
    ];

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
