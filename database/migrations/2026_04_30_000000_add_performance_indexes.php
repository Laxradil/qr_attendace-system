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
        // Skip for now - indexes already exist
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex(['professor_id']);
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropIndex(['class_id']);
            $table->dropIndex(['student_id']);
            $table->dropIndex(['class_id', 'student_id']);
            $table->dropIndex(['recorded_at']);
        });

        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropIndex(['class_id']);
            $table->dropIndex(['professor_id']);
            $table->dropIndex(['uuid']);
        });

        Schema::table('class_student', function (Blueprint $table) {
            $table->dropIndex(['class_id']);
            $table->dropIndex(['student_id']);
            $table->dropIndex(['class_id', 'student_id']);
        });

        Schema::table('system_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });
    }
};
