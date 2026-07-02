<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE blood_requests MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'");

        DB::table('blood_requests')
            ->where('status', 'fulfilled')
            ->update(['status' => 'resolved']);

        DB::table('blood_requests')
            ->where('status', 'partial')
            ->update(['status' => 'resolved']);

        DB::table('blood_requests')
            ->where('status', 'cancelled')
            ->update(['status' => 'closed']);

        DB::statement("ALTER TABLE blood_requests MODIFY COLUMN status ENUM('pending', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE blood_requests MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'");

        DB::table('blood_requests')
            ->where('status', 'resolved')
            ->update(['status' => 'fulfilled']);

        DB::table('blood_requests')
            ->where('status', 'closed')
            ->update(['status' => 'cancelled']);

        DB::statement("ALTER TABLE blood_requests MODIFY COLUMN status ENUM('pending', 'fulfilled', 'partial', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
