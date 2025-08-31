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
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('salary', 10, 2)->nullable();
            $table->enum('english_level', ['basic', 'intermediate', 'advanced'])->nullable();
            $table->enum('certificate_type', ['high_school', 'diploma', 'bachelor', 'master', 'phd'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->unsignedTinyInteger('members_number')->nullable();
            $table->unsignedTinyInteger('alerts_number')->default(0);
            $table->unsignedTinyInteger('deductions_number')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'salary',
                'english_level',
                'certificate_type',
                'marital_status',
                'members_number',
                'alerts_number',
                'deductions_number',
            ]);
        });
    }
};
