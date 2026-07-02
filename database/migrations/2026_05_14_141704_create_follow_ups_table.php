<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // re_engagement, eligible_reminder, call_back
            $table->text('notes')->nullable();
            $table->timestamp('scheduled_at');
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pending'); // pending, completed, skipped
            $table->timestamps();

            $table->index(['status', 'scheduled_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
