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
<<<<<<<< HEAD:database/migrations/2026_04_30_021911_add_indexes_to_attendance_and_pivot.php
        Schema::table('attendance_and_pivot', function (Blueprint $table) {
            //
        });
========
        // Table already exists in database - skipping
>>>>>>>> 3054c492ecd1d990f715fa110f025b7ae40e41e2:database/migrations/2026_04_27_124954_create_schedules_table.php
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_and_pivot', function (Blueprint $table) {
            //
        });
    }
};
