<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employee_login_ips', function (Blueprint $table) {
            $table->string('device_token', 64)->nullable()->after('ip_address');
            $table->index('device_token');
        });
    }

    public function down(): void
    {
        Schema::table('employee_login_ips', function (Blueprint $table) {
            $table->dropIndex(['device_token']);
            $table->dropColumn('device_token');
        });
    }
};
