<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:database-backup --keep=10')->dailyAt('02:00');
Schedule::command('app:process-donor-follow-ups')->dailyAt('03:00');
