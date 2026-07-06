<?php

namespace App\Console\Commands;

use App\Jobs\SendDonorSmsJob;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\FollowUp;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessDonorFollowUps extends Command
{
    protected $signature = 'app:process-donor-follow-ups';

    protected $description = 'Generate follow-up tasks for donor re-engagement, eligibility reminders, and call-backs';

    public function handle(): int
    {
        $this->processReEngagement();
        $this->processEligibleReminders();
        $this->processCallBacks();

        $this->info('Follow-ups processed successfully.');

        return Command::SUCCESS;
    }

    private function processReEngagement(): void
    {
        $threshold = now()->subMonths(6);
        $donors = Donor::where('status', 'active')
            ->where('last_donation_date', '<', $threshold)
            ->whereDoesntHave('followUps', function ($q) {
                $q->where('type', 're_engagement')->where('status', 'pending');
            })
            ->get();

        foreach ($donors as $donor) {
            FollowUp::create([
                'donor_id' => $donor->id,
                'type' => 're_engagement',
                'notes' => 'Donor has not donated in 6+ months. Last donation: ' .
                    ($donor->last_donation_date?->format('Y-m-d') ?? 'never'),
                'scheduled_at' => now(),
                'status' => 'pending',
            ]);
        }

        $this->line("Created {$donors->count()} re-engagement follow-ups.");
    }

    private function processEligibleReminders(): void
    {
        $donors = Donor::where('status', 'active')
            ->whereNotNull('last_donation_date')
            ->where('last_donation_date', '<=', now()->subMonths(3))
            ->whereDoesntHave('followUps', function ($q) {
                $q->where('type', 'eligible_reminder')
                  ->where('status', 'pending')
                  ->where('scheduled_at', '>=', now()->subDays(90));
            })
            ->get();

        foreach ($donors as $donor) {
            FollowUp::create([
                'donor_id' => $donor->id,
                'type' => 'eligible_reminder',
                'notes' => 'Donor became eligible for donation on ' . now()->format('Y-m-d'),
                'scheduled_at' => now(),
                'status' => 'pending',
            ]);

            $reminderMessage = "Dear {$donor->name}, you are now eligible to donate blood again! Your last donation was {$donor->last_donation_date?->format('Y-m-d')}. Please visit your nearest blood bank to save a life.";
            SendDonorSmsJob::dispatch($donor->phone, $reminderMessage, 'eligible_reminder:' . $donor->id);
        }

        $this->line("Created {$donors->count()} eligible reminder follow-ups and dispatched SMS.");
    }

    private function processCallBacks(): void
    {
        $pendingRequests = BloodRequest::where('status', 'pending')
            ->whereHas('callLogs', function ($q) {
                $q->where('outcome', 'call_back')
                  ->where('created_at', '<=', now()->subDay());
            })
            ->get();

        $count = 0;

        foreach ($pendingRequests as $request) {
            $callBackLogs = $request->callLogs()
                ->where('outcome', 'call_back')
                ->where('created_at', '<=', now()->subDay())
                ->get();

            foreach ($callBackLogs as $log) {
                $exists = FollowUp::where('donor_id', $log->donor_id)
                    ->where('type', 'call_back')
                    ->where('status', 'pending')
                    ->where('scheduled_at', '>=', now()->subDays(7))
                    ->exists();

                if (!$exists) {
                    FollowUp::create([
                        'donor_id' => $log->donor_id,
                        'type' => 'call_back',
                        'notes' => "Call-back follow-up for blood request #{$request->id} ({$request->patient_name})",
                        'scheduled_at' => now(),
                        'status' => 'pending',
                    ]);
                    $count++;
                }
            }
        }

        $this->line("Created {$count} call-back follow-ups.");
    }
}
