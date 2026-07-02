<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donor_stories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('quote');
            $table->string('photo')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('city')->nullable();
            $table->integer('donations_count')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donor_stories');
    }
};
