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
        Schema::table('salary_increases', function (Blueprint $table) {
            $table->enum('increase_type', ['static', 'reward'])->default('static');
            $table->tinyInteger('reward_month')->nullable();
            $table->boolean('is_reward')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_increases', function (Blueprint $table) {
            $table->dropColumn(['increase_type', 'reward_month', 'is_reward']);
        });
    }
};
