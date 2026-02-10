<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advance_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('scheduled_date');
            $table->enum('status', ['pending', 'paid', 'postponed'])->default('pending');
            $table->integer('payment_number');
            $table->date('original_scheduled_date')->nullable();
            $table->text('postpone_reason')->nullable();
            $table->foreignId('created_from_payment_id')->nullable()->constrained('advance_payments')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advance_payments');
    }
};
