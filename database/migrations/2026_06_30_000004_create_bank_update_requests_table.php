<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_update_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            $table->string('full_name');
            $table->string('account_status'); // active|inactive (self-reported by the employee)
            $table->string('id_card_number');
            $table->string('mobile_number');
            $table->string('city');

            $table->string('current_iban')->nullable();
            $table->string('current_bank_name')->nullable();
            $table->string('current_owner_account_name')->nullable();

            $table->string('new_iban');
            $table->string('new_bank_name');
            $table->string('new_owner_account_name');
            $table->json('id_card_images');
            $table->text('notes')->nullable();

            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_update_requests');
    }
};
