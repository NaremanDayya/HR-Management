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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('iban')->nullable();
            $table->string('owner_account_name')->nullable();
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->onDelete('cascade');
            $table->foreignId('manager_id')->nullable()->constrained('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['supervisor_id']);
            $table->dropForeign(['manager_id']);
            $table->dropColumn(['iban', 'owner_account_name', 'supervisor_id', 'manager_id']);
        });
    }
};
