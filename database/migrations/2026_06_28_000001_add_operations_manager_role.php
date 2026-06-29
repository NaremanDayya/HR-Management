<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $role = Role::firstOrCreate(['name' => 'operations_manager', 'guard_name' => 'web']);

        $permissions = Permission::whereIn('name', [
            'add_employee',
            'add_project_manager',
            'review_employee_requests',
            'view_financials',
            'view_credentials',
            'change_employees_password',
        ])->get();

        $role->syncPermissions($permissions);
    }

    public function down(): void
    {
        Role::where('name', 'operations_manager')->delete();
    }
};
