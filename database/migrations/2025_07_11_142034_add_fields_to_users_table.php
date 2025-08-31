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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['manager', 'employee', 'admin'])->nullable();
            $table->json('privileges')->nullable();
            $table->string('account_status')->default('active');
            $table->json('contact_info')->nullable();
            $table->json('size_info')->nullable();
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('id_card')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
            'role',
            'privileges',
            'account_status',
            'contact_info',
            'size_info',
            'birthday',
            'age',
            'id_card',
            'nationality',
            'gender',
        ]);
        });
    }
};
