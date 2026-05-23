<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add professor_id index to classes table for faster professor lookups
        Schema::table('classes', function (Blueprint $table) {
            $table->index('professor_id');
        });

        // Add foreign key indexes to qr_codes table for faster lookups
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->index('class_id');
            $table->index('professor_id');
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropIndex(['professor_id']);
        });

        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropIndex(['class_id']);
            $table->dropIndex(['professor_id']);
        });
    }
};
