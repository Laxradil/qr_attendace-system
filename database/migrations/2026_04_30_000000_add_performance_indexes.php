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
        // Index for classes.professor_id (foreign key)
        Schema::table('classes', function (Blueprint $table) {
            $table->index('professor_id');
        });

        // Indexes for attendance records
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->index('class_id');
            $table->index('student_id');
            $table->index(['class_id', 'student_id']); // Composite index for reports queries
            $table->index('recorded_at'); // For date-based queries
        });

        // Index for QR codes
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->index('class_id');
            $table->index('professor_id');
            $table->index('uuid'); // For lookups by UUID
        });

        // Composite index for class_student pivot
        Schema::table('class_student', function (Blueprint $table) {
            $table->index('class_id');
            $table->index('student_id');
            $table->index(['class_id', 'student_id']);
        });

        // Index for system logs
        Schema::table('system_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
        });

        // Index for users role-based queries
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });
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
