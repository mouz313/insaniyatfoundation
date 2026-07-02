<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->index('cnic');
            $table->index('phone');
            $table->index('city_id');
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropIndex(['cnic']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['city_id']);
        });
    }
};