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
        Schema::table('courses', function (Blueprint $table) {
            $table->timestamp('start_date')->after('id');
            $table->timestamp('end_date')->after('id');
            $table->json('schedule');
            $table->text('description')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('rooms');
    }
};
