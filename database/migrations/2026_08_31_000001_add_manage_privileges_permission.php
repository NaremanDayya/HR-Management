<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        Permission::firstOrCreate(['name' => 'manage_privileges', 'guard_name' => 'web']);
    }

    public function down(): void
    {
        Permission::where('name', 'manage_privileges')->delete();
    }
};
