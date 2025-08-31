<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('joining_date')->nullable();
            $table->string('work_duration')->nullable();
            $table->string('job')->nullable();
            $table->text('stop_reason')->nullable();
            $table->json('vehicle_info')->nullable();
            $table->string('health_card')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('work_area')->nullable();
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('iban')->nullable()->after('bank_name');
            $table->string('owner_account_name')->nullable()->after('iban');
            $table->foreignId('supervisor_id')->constrained('employees')->nullOnDelete();
            $table->foreignId('manager_id')->constrained('employees')->nullOnDelete();
            $table->json('payload')->nullable()->after('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
