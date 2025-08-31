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
        Schema::table('advances', function (Blueprint $table) {
            $table->decimal('monthly_deduction', 10, 2)->nullable();
            $table->integer('months_to_repay')->nullable();
            $table->integer('months_remaining')->nullable();
            $table->date('start_deduction_at')->nullable();
            $table->boolean('is_fully_paid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advances', function (Blueprint $table) {
            //
        });
    }
};
