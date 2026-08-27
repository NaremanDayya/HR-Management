<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->default('active')->after('description'); // active|stopped
            $table->text('stop_reason')->nullable()->after('status');
            $table->timestamp('stopped_at')->nullable()->after('stop_reason');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['status', 'stop_reason', 'stopped_at']);
        });
    }
};
