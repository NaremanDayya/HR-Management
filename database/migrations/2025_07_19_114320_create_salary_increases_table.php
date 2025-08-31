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
        Schema::create('salary_increases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('manager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('request_id')->nullable()->constrained('employee_requests')->onDelete('set null');
            $table->timestamp('effective_date')->nullable();
            $table->boolean('is_applied')->default(false);
            $table->decimal('previous_salary', 10, 2);
            $table->decimal('increase_amount', 10, 2);
            $table->decimal('increase_percentage', 5, 2);
            $table->decimal('new_salary', 10, 2);
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_increases');
    }
};
