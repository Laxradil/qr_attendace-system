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
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable()->after('professor_id');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropIndex(['student_id']);
            $table->dropColumn('student_id');
        });
    }
};
