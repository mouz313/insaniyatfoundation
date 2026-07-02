<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Donor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'father_name', 'cnic', 'phone', 'dob', 'blood_group',
        'address', 'city_id', 'area_id', 'photo', 'weight', 'hemoglobin',
        'health_flags', 'gender', 'is_student',
        'university_name', 'university_id', 'education', 'registration_no', 'referred_by',
        'status', 'last_donation_date', 'card_printed_at', 'total_donations'
    ];

    protected $casts = [
        'dob' => 'date',
        'last_donation_date' => 'date',
        'is_student' => 'boolean',
        'card_printed_at' => 'datetime',
        'health_flags' => 'array',
        'total_donations' => 'integer',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function referrer()
    {
        return $this->belongsTo(Donor::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(Donor::class, 'referred_by');
    }

    public function getUniversityDisplayAttribute(): string
    {
        return $this->university?->name ?? $this->university_name ?? '';
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'donor_badge')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }

    public function donations()
    {
        return $this->hasMany(BloodDonation::class);
    }

    public function moneyDonations()
    {
        return $this->hasMany(MoneyDonation::class);
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->dob) return null;
        return $this->dob->age;
    }

    public function getHealthFlagsListAttribute(): array
    {
        return is_array($this->health_flags) ? $this->health_flags : [];
    }

    public function getIsEligibleAttribute(): bool
    {
        $reasons = $this->eligibilityReasons();
        return empty($reasons);
    }

    public function getEligibilityStatusAttribute(): string
    {
        if ($this->status === 'ineligible') return 'ineligible';
        if (!$this->is_eligible) return 'temporarily';
        return 'eligible';
    }

    public function eligibilityReasons(): array
    {
        $reasons = [];

        if ($this->status === 'ineligible') {
            $reasons[] = 'Marked as permanently ineligible';
            return $reasons;
        }

        if ($this->dob) {
            $age = $this->age;
            if ($age < 18) $reasons[] = 'Under 18 years old';
            if ($age > 60) $reasons[] = 'Over 60 years old';
        }

        if ($this->weight && $this->weight < 45) {
            $reasons[] = 'Weight below 45 kg';
        }

        if ($this->hemoglobin && $this->hemoglobin < 12.5) {
            $reasons[] = 'Hemoglobin below 12.5 g/dL';
        }

        $flags = $this->health_flags_list;
        if (in_array('recent_illness', $flags)) $reasons[] = 'Recent illness/surgery flagged';
        if (in_array('pregnant', $flags)) $reasons[] = 'Currently pregnant';
        if (in_array('recent_tattoo', $flags)) $reasons[] = 'Recent tattoo/piercing (6-month deferral)';
        if (in_array('medication', $flags)) $reasons[] = 'On restricted medication';
        if (in_array('chronic_disease', $flags)) $reasons[] = 'Chronic disease flagged';
        if (in_array('low_risk', $flags)) $reasons[] = 'High-risk behavior flagged';

        if ($this->last_donation_date) {
            $nextEligible = $this->last_donation_date->copy()->addMonths(3);
            $daysLeft = Carbon::now()->startOfDay()->diffInDays($nextEligible, false);
            if ($daysLeft > 0) {
                $reasons[] = "Last donation was " . $this->last_donation_date->diffForHumans() . " ({$daysLeft} days until eligible)";
            }
        }

        return $reasons;
    }

    public function getReliabilityScoreAttribute(): int
    {
        return $this->total_donations > 0 ? 100 : 0;
    }

    public function syncBadges(): void
    {
        $definitions = [
            ['slug' => 'first_donation', 'name' => 'First Drop', 'description' => 'Completed your first blood donation', 'icon' => 'fa-tint', 'color' => '#28a745'],
            ['slug' => 'regular_donor', 'name' => 'Regular Donor', 'description' => 'Completed 5 or more donations', 'icon' => 'fa-tint', 'color' => '#17a2b8'],
            ['slug' => 'super_donor', 'name' => 'Super Donor', 'description' => 'Completed 10 or more donations', 'icon' => 'fa-star', 'color' => '#ffc107'],
            ['slug' => 'lifesaver', 'name' => 'Lifesaver', 'description' => 'Completed 20 or more donations', 'icon' => 'fa-heart', 'color' => '#dc3545'],
            ['slug' => 'reliable', 'name' => 'Highly Reliable', 'description' => 'Completed 15 or more donations', 'icon' => 'fa-handshake', 'color' => '#6610f2'],
            ['slug' => 'referrer', 'name' => 'Referrer', 'description' => 'Referred 3 or more donors', 'icon' => 'fa-user-plus', 'color' => '#20c997'],
        ];

        foreach ($definitions as $def) {
            Badge::updateOrCreate(['slug' => $def['slug']], $def);
        }

        $thresholds = [
            'first_donation' => 1,
            'regular_donor' => 5,
            'super_donor' => 10,
            'lifesaver' => 20,
            'reliable' => 15,
        ];

        foreach ($thresholds as $slug => $min) {
            if ($this->total_donations >= $min) {
                $badge = Badge::where('slug', $slug)->first();
                if ($badge) {
                    $badge->donors()->syncWithoutDetaching([$this->id => ['awarded_at' => now()]]);
                }
            }
        }

        $referralsCount = $this->referrals()->count();
        if ($referralsCount >= 3) {
            $badge = Badge::where('slug', 'referrer')->first();
            if ($badge) {
                $badge->donors()->syncWithoutDetaching([$this->id => ['awarded_at' => now()]]);
            }
        }
    }

    public function getNextEligibleDateAttribute(): ?Carbon
    {
        if (!$this->last_donation_date) return null;
        return $this->last_donation_date->copy()->addMonths(3);
    }

    public function getDaysUntilEligibleAttribute(): int
    {
        if (!$this->next_eligible_date) return 0;
        $days = Carbon::now()->startOfDay()->diffInDays($this->next_eligible_date, false);
        return $days < 0 ? 0 : (int) $days;
    }

    public function scopeEligible($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('last_donation_date')
                  ->orWhere('last_donation_date', '<=', now()->subMonths(3));
            });
    }

    public function scopeByBloodGroup($query, $group)
    {
        return $query->where('blood_group', $group);
    }
}
