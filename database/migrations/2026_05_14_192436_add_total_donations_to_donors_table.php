<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->integer('total_donations')->default(0)->after('status');
        });

        DB::statement('UPDATE donors d SET d.total_donations = (SELECT COUNT(*) FROM blood_donations bd WHERE bd.donor_id = d.id AND bd.status = ?)', ['donated']);
    }

    public function down(): void
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('total_donations');
        });
    }
};
