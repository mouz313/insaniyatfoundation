<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->decimal('weight', 5, 1)->nullable()->after('photo');
            $table->decimal('hemoglobin', 4, 1)->nullable()->after('weight');
            $table->json('health_flags')->nullable()->after('hemoglobin');
            $table->string('gender')->nullable()->after('health_flags');
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->text('attendance_notes')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn(['weight', 'hemoglobin', 'health_flags', 'gender']);
        });
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('attendance_notes');
        });
    }
};
