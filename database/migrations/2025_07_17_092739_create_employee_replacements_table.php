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
        Schema::create('employee_replacements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('old_employee_id')->constrained('employees');
            $table->foreignId('new_employee_id')->constrained('employees');
            $table->date('last_working_date');
            $table->date('replacement_date');
            $table->string('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_replacements');
    }
};
