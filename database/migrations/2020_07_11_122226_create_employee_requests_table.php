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
        Schema::create('employee_requests', function (Blueprint $table) {
            $table->id();
            $table->morphs('requester');
            $table->morphs('recipient');
            $table->string('status')->default('pending');
            $table->string('request_type');
            $table->text('description')->nullable();
            $table->timestamp('response_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('edited_field')->nullable();
            $table->foreignId('request_type_id')->constrained('requests_type')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_requests');
    }
};
