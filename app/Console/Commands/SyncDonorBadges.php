<?php

namespace App\Console\Commands;

use App\Models\Badge;
use App\Models\Donor;
use Illuminate\Console\Command;

class SyncDonorBadges extends Command
{
    protected $signature = 'app:sync-donor-badges';

    protected $description = 'Seed badge definitions and auto-assign badges to eligible donors';

    private array $definitions = [
        [
            'slug' => 'first_donation',
            'name' => 'First Drop',
            'description' => 'Completed your first blood donation',
            'icon' => 'fa-tint',
            'color' => '#28a745',
        ],
        [
            'slug' => 'regular_donor',
            'name' => 'Regular Donor',
            'description' => 'Completed 5 or more donations',
            'icon' => 'fa-tint',
            'color' => '#17a2b8',
        ],
        [
            'slug' => 'super_donor',
            'name' => 'Super Donor',
            'description' => 'Completed 10 or more donations',
            'icon' => 'fa-star',
            'color' => '#ffc107',
        ],
        [
            'slug' => 'lifesaver',
            'name' => 'Lifesaver',
            'description' => 'Completed 20 or more donations',
            'icon' => 'fa-heart',
            'color' => '#dc3545',
        ],
        [
            'slug' => 'reliable',
            'name' => 'Highly Reliable',
            'description' => 'Completed 15 or more donations',
            'icon' => 'fa-handshake',
            'color' => '#6610f2',
        ],
        [
            'slug' => 'referrer',
            'name' => 'Referrer',
            'description' => 'Referred 3 or more donors',
            'icon' => 'fa-user-plus',
            'color' => '#20c997',
        ],
    ];

    public function handle(): int
    {
        $this->seedBadges();
        $this->assignBadges();

        $this->info('Badge sync completed.');

        return Command::SUCCESS;
    }

    private function seedBadges(): void
    {
        foreach ($this->definitions as $def) {
            Badge::updateOrCreate(['slug' => $def['slug']], $def);
        }

        $this->line('Seeded ' . count($this->definitions) . ' badge definitions.');
    }

    private function assignBadges(): void
    {
        $this->assignFirstDonation();
        $this->assignRegularDonor();
        $this->assignSuperDonor();
        $this->assignLifesaver();
        $this->assignReliable();
        $this->assignReferrer();
    }

    private function award(int $donorId, string $slug): void
    {
        $badge = Badge::where('slug', $slug)->first();
        if (!$badge) return;

        $badge->donors()->syncWithoutDetaching([$donorId => ['awarded_at' => now()]]);
    }

    private function assignFirstDonation(): void
    {
        $ids = Donor::where('total_donations', '>=', 1)->pluck('id');
        foreach ($ids as $id) {
            $this->award($id, 'first_donation');
        }
        $this->line("Assigned 'First Drop' to {$ids->count()} donors.");
    }

    private function assignRegularDonor(): void
    {
        $ids = Donor::where('total_donations', '>=', 5)->pluck('id');
        foreach ($ids as $id) {
            $this->award($id, 'regular_donor');
        }
        $this->line("Assigned 'Regular Donor' to {$ids->count()} donors.");
    }

    private function assignSuperDonor(): void
    {
        $ids = Donor::where('total_donations', '>=', 10)->pluck('id');
        foreach ($ids as $id) {
            $this->award($id, 'super_donor');
        }
        $this->line("Assigned 'Super Donor' to {$ids->count()} donors.");
    }

    private function assignLifesaver(): void
    {
        $ids = Donor::where('total_donations', '>=', 20)->pluck('id');
        foreach ($ids as $id) {
            $this->award($id, 'lifesaver');
        }
        $this->line("Assigned 'Lifesaver' to {$ids->count()} donors.");
    }

    private function assignReliable(): void
    {
        $ids = Donor::where('total_donations', '>=', 15)->pluck('id');
        foreach ($ids as $id) {
            $this->award($id, 'reliable');
        }
        $this->line("Assigned 'Highly Reliable' to {$ids->count()} donors.");
    }

    private function assignReferrer(): void
    {
        $ids = Donor::has('referrals', '>=', 3)->pluck('id');
        foreach ($ids as $id) {
            $this->award($id, 'referrer');
        }
        $this->line("Assigned 'Referrer' to {$ids->count()} donors.");
    }
}
