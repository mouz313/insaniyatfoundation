<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE blood_donations MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'");

        DB::table('blood_donations')
            ->where('status', 'completed')
            ->update(['status' => 'donated']);

        DB::table('blood_donations')
            ->where('status', 'cancelled')
            ->update(['status' => 'deferred']);

        DB::statement("ALTER TABLE blood_donations MODIFY COLUMN status ENUM('donated', 'deferred', 'pending') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE blood_donations MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'");

        DB::table('blood_donations')
            ->where('status', 'donated')
            ->update(['status' => 'completed']);

        DB::table('blood_donations')
            ->where('status', 'deferred')
            ->update(['status' => 'cancelled']);

        DB::statement("ALTER TABLE blood_donations MODIFY COLUMN status ENUM('completed', 'pending', 'cancelled') NOT NULL DEFAULT 'completed'");
    }
};
