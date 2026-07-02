<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:database-backup --keep=10')->dailyAt('02:00');
Schedule::command('app:process-donor-follow-ups')->dailyAt('03:00');
Schedule::command('app:sync-donor-badges')->weeklyOn(7, '04:00');
