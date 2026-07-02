<?php

namespace App\Services;

use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Database\Eloquent\Collection;

class MatchingService
{
    const int WEIGHT_BLOOD_GROUP = 30;
    const int WEIGHT_SAME_CITY = 20;
    const int WEIGHT_RELIABILITY = 20;
    const int WEIGHT_DAYS_SINCE_DONATION = 15;
    const int WEIGHT_DONATION_HISTORY = 15;

    const array COMPATIBLE_BLOOD_GROUPS = [
        'A+' => ['A+', 'A-', 'O+', 'O-'],
        'A-' => ['A-', 'O-'],
        'B+' => ['B+', 'B-', 'O+', 'O-'],
        'B-' => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'O+' => ['O+', 'O-'],
        'O-' => ['O-'],
    ];

    public function findMatches(BloodRequest $bloodRequest): Collection
    {
        $bloodRequest->load('city');

        $compatibleGroups = self::COMPATIBLE_BLOOD_GROUPS[$bloodRequest->blood_group] ?? [$bloodRequest->blood_group];

        $donors = Donor::with('city')
            ->select('donors.*')
            ->addSelect([
                'last_contacted_at' => \App\Models\CallLog::select('created_at')
                    ->whereColumn('donor_id', 'donors.id')
                    ->latest()
                    ->take(1)
            ])
            ->whereIn('blood_group', $compatibleGroups)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('last_donation_date')
                  ->orWhere('last_donation_date', '<=', now()->subMonths(3));
            })
            ->take(200)
            ->get()
            ->map(function ($donor) use ($bloodRequest) {
                $scores = $this->calculateScores($donor, $bloodRequest);
                $donor->match_breakdown = $scores;
                $donor->match_score = $scores['total'];
                $donor->reliability_score = $scores['reliability'];
                $donor->same_city = $donor->city_id && $donor->city_id === $bloodRequest->city_id;
                $donor->days_since_last = $donor->last_donation_date
                    ? $donor->last_donation_date->diffInDays(now())
                    : 9999;
                $donor->last_contacted = $donor->last_contacted_at
                    ? \Carbon\Carbon::parse($donor->last_contacted_at)
                    : null;

                return $donor;
            })
            ->sortByDesc('match_score')
            ->values();

        return $donors;
    }

    public function calculateScores(Donor $donor, BloodRequest $bloodRequest): array
    {
        $bloodScore = $this->bloodGroupScore($donor->blood_group, $bloodRequest->blood_group);
        $cityScore = $this->cityScore($donor->city_id, $bloodRequest->city_id);
        $reliabilityScore = $this->reliabilityScore($donor);
        $daysScore = $this->daysSinceDonationScore($donor);
        $historyScore = $this->donationHistoryScore($donor);

        return [
            'blood_group' => $bloodScore,
            'same_city' => $cityScore,
            'reliability' => $reliabilityScore,
            'days_since_donation' => $daysScore,
            'donation_history' => $historyScore,
            'total' => $bloodScore + $cityScore + $reliabilityScore + $daysScore + $historyScore,
        ];
    }

    public function bloodGroupScore(string $donorGroup, string $requestGroup): int
    {
        if ($donorGroup === $requestGroup) {
            return self::WEIGHT_BLOOD_GROUP;
        }

        $compatible = self::COMPATIBLE_BLOOD_GROUPS[$requestGroup] ?? [];
        if (in_array($donorGroup, $compatible, true)) {
            return (int) round(self::WEIGHT_BLOOD_GROUP * 0.7);
        }

        return 0;
    }

    public function cityScore(?int $donorCityId, ?int $requestCityId): int
    {
        if ($donorCityId && $requestCityId && $donorCityId === $requestCityId) {
            return self::WEIGHT_SAME_CITY;
        }

        return 0;
    }

    public function reliabilityScore(Donor $donor): int
    {
        return $donor->total_donations > 0 ? self::WEIGHT_RELIABILITY : 0;
    }

    public function daysSinceDonationScore(Donor $donor): int
    {
        if (!$donor->last_donation_date) {
            return self::WEIGHT_DAYS_SINCE_DONATION;
        }

        $days = $donor->last_donation_date->diffInDays(now());

        if ($days >= 730) {
            return self::WEIGHT_DAYS_SINCE_DONATION;
        }

        if ($days <= 90) {
            return 0;
        }

        return (int) round(self::WEIGHT_DAYS_SINCE_DONATION * ($days / 730));
    }

    public function donationHistoryScore(Donor $donor): int
    {
        return min(self::WEIGHT_DONATION_HISTORY, (int) round($donor->total_donations * 3));
    }
}
