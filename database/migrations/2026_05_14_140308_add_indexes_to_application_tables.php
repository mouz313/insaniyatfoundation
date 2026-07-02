<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->index('blood_group');
            $table->index('status');
            $table->index('last_donation_date');
        });

        Schema::table('blood_donations', function (Blueprint $table) {
            $table->index('blood_group');
            $table->index('status');
            $table->index('donation_date');
        });

        Schema::table('blood_requests', function (Blueprint $table) {
            $table->index('blood_group');
            $table->index('status');
        });

        Schema::table('call_logs', function (Blueprint $table) {
            $table->index('outcome');
        });

        Schema::table('money_donations', function (Blueprint $table) {
            $table->index('donation_date');
            $table->index('payment_method');
        });

        Schema::table('blood_inventory', function (Blueprint $table) {
            $table->index('blood_group');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropIndex(['blood_group']);
            $table->dropIndex(['status']);
            $table->dropIndex(['last_donation_date']);
        });

        Schema::table('blood_donations', function (Blueprint $table) {
            $table->dropIndex(['blood_group']);
            $table->dropIndex(['status']);
            $table->dropIndex(['donation_date']);
        });

        Schema::table('blood_requests', function (Blueprint $table) {
            $table->dropIndex(['blood_group']);
            $table->dropIndex(['status']);
        });

        Schema::table('call_logs', function (Blueprint $table) {
            $table->dropIndex(['outcome']);
        });

        Schema::table('money_donations', function (Blueprint $table) {
            $table->dropIndex(['donation_date']);
            $table->dropIndex(['payment_method']);
        });

        Schema::table('blood_inventory', function (Blueprint $table) {
            $table->dropIndex(['blood_group']);
            $table->dropIndex(['status']);
        });
    }
};
