<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('professor_id');
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('professor_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('is_used');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
