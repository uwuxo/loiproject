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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained();
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'late'])->default('absent');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
        Schema::create('loggeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tokenable_id')->constrained('personal_access_tokens')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
