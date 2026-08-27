<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->string('channel')->nullable()->after('reason'); // whatsapp|email|both
            $table->string('message_sid')->nullable()->after('message_sent');
            $table->string('delivery_status')->nullable()->after('message_sid'); // queued|failed
            $table->text('error_message')->nullable()->after('delivery_status');
        });
    }

    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropColumn(['channel', 'message_sid', 'delivery_status', 'error_message']);
        });
    }
};
