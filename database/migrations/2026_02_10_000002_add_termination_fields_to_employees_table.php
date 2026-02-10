<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('outstanding_advance_debt', 10, 2)->default(0);
            $table->boolean('is_terminated')->default(false);
            $table->date('termination_date')->nullable();
            $table->text('termination_notes')->nullable();
            $table->integer('work_days')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'outstanding_advance_debt',
                'is_terminated',
                'termination_date',
                'termination_notes',
                'work_days'
            ]);
        });
    }
};
