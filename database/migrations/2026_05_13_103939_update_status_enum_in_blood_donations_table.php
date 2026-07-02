<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE blood_donations MODIFY COLUMN status ENUM('completed', 'pending', 'cancelled') NOT NULL DEFAULT 'completed'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE blood_donations MODIFY COLUMN status ENUM('donated', 'deferred', 'pending') NOT NULL DEFAULT 'donated'");
    }
};
