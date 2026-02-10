<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\RequestType;

class RequestTypeSeeder extends Seeder
{
    public function run(): void
    {
        $requestTypes = [
            [
                'key' => 'edit_employee_data',
                'label' => 'طلب تعديل بيانات موظف',
            ],
            [
                'key' => 'replace_employee',
                'label' => 'طلب استبدال موظف',
            ],
            [
                'key' => 'salary_advance',
                'label' => 'طلب سلفة',
            ],
            [
                'key' => 'salary_increase',
                'label' => 'طلب زيادة راتب',
            ],
            [
                'key' => 'temporary_assignment',
                'label' => 'طلب نقل مؤقت',
            ],
        ];

        foreach ($requestTypes as $type) {
            RequestType::updateOrCreate(
                ['key' => $type['key']],
                ['label' => $type['label']]
            );
        }

        $this->command->info('Request types seeded successfully!');
    }
}
