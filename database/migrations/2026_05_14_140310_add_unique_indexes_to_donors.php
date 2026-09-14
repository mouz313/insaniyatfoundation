<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            if (!Schema::hasIndex('donors', 'donors_cnic_unique')) {
                $table->unique('cnic');
            }
            if (!Schema::hasIndex('donors', 'donors_registration_no_unique')) {
                $table->unique('registration_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            if (Schema::hasIndex('donors', 'donors_cnic_unique')) {
                $table->dropUnique(['cnic']);
            }
            if (Schema::hasIndex('donors', 'donors_registration_no_unique')) {
                $table->dropUnique(['registration_no']);
            }
        });
    }
};
