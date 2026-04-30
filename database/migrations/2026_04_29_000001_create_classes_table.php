<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('professor_id');
            $table->integer('student_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('professor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
