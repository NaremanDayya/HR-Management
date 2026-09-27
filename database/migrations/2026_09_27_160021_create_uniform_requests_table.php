<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uniform_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            // type: 'request' = requesting new uniform, 'return' = returning uniform
            $table->enum('type', ['request', 'return']);
            $table->unsignedTinyInteger('tshirt_count')->default(0);
            $table->unsignedTinyInteger('hat_count')->default(0);
            $table->unsignedTinyInteger('id_card_count')->default(0);
            $table->unsignedTinyInteger('tool_bag_count')->default(0);
            // status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            // deduct_cost: null = not decided yet, true = deduct 50 SAR per tshirt, false = waived
            $table->boolean('deduct_cost')->nullable();
            $table->decimal('cost_amount', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uniform_requests');
    }
};
