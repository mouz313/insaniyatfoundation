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
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('cnic')->unique()->nullable();
            $table->string('phone')->unique();
            $table->date('dob')->nullable();
            $table->string('blood_group');
            $table->text('address')->nullable();
            $table->foreignId('city_id')->nullable()->constrained();
            $table->foreignId('area_id')->nullable()->constrained();
            $table->string('photo')->nullable();
            $table->boolean('is_student')->default(false);
            $table->string('university_name')->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('donors');
            $table->enum('status', ['active', 'inactive', 'ineligible'])->default('active');
            $table->date('last_donation_date')->nullable();
            $table->timestamp('card_printed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
