<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uniforms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unsignedTinyInteger('tshirt_count')->default(0);
            $table->unsignedTinyInteger('hat_count')->default(0);
            $table->unsignedTinyInteger('id_card_count')->default(0);
            $table->unsignedTinyInteger('tool_bag_count')->default(0);
            $table->date('received_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uniforms');
    }
};
