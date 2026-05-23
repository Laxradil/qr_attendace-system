<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code', 20);
            $table->string('subject_name', 255);
            $table->unsignedBigInteger('professor_id');
            $table->string('professor', 255);
            $table->string('days', 100);
            $table->string('time', 50);
            $table->string('room', 20);
            $table->timestamps();
            
            $table->foreign('professor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};