<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('money_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->nullable()->constrained();
            $table->string('anonymous_name')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('donation_date');
            $table->string('payment_method'); // cash, bank, JazzCash, Easypaisa
            $table->foreignId('campaign_id')->nullable()->constrained();
            $table->string('receipt_number')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('money_donations');
    }
};
