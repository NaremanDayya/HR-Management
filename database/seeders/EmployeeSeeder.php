<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            ->count(10)
            ->create([
                'role' => 'employee',
            ])
            ->each(function ($user) {
                // For each user, create one linked employee
                Employee::factory()->create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    
                ]);
            });


    }
}
