<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('passport_number')->nullable()->after('iban');
            $table->date('passport_issue_date')->nullable()->after('passport_number');
            $table->date('id_expiry_date')->nullable()->after('passport_issue_date');
            $table->string('driver_license_number')->nullable()->after('id_expiry_date');
            $table->string('medical_insurance')->nullable()->after('driver_license_number');
            $table->tinyInteger('wives_count')->unsigned()->nullable()->after('medical_insurance');
            $table->json('languages')->nullable()->after('wives_count');
            $table->json('residential_address')->nullable()->after('languages'); // {city, neighborhood}
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('tshirt_size')->nullable()->after('size_info');
            $table->string('trousers_size')->nullable()->after('tshirt_size');
            $table->string('shoes_size')->nullable()->after('trousers_size');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'passport_number', 'passport_issue_date', 'id_expiry_date',
                'driver_license_number', 'medical_insurance', 'wives_count',
                'languages', 'residential_address',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tshirt_size', 'trousers_size', 'shoes_size']);
        });
    }
};
