<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
            'supervisor',
            'employee',
            'admin',
            'hr_assistant',
            'hr_manager',
            'project_manager',
            'shelf_stacker',
            'area_manager',
            'senior_project_manager',
            'operations_manager'
        ) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
            'supervisor',
            'employee',
            'admin',
            'hr_assistant',
            'hr_manager',
            'project_manager',
            'shelf_stacker',
            'area_manager'
        ) NULL");
    }
};
