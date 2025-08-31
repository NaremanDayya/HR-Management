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
        Schema::create('advances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
      $table->foreignId('request_id')->nullable()->constrained('employee_requests')->nullOnDelete();
        $table->decimal('amount', 10, 2);
        $table->decimal('percentage', 5, 2)->nullable();
        $table->decimal('salary', 10, 2);
        $table->string('status')->default('pending');
        $table->timestamp('requested_at')->useCurrent();
        $table->timestamp('approved_at')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
