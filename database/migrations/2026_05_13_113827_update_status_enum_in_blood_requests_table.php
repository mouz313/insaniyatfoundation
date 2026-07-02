<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE blood_requests MODIFY COLUMN status ENUM('pending', 'fulfilled', 'partial', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE blood_requests MODIFY COLUMN status ENUM('pending', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
    }
};
