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
            [
                'key' => 'united_clothes',
                'label' => 'طلب يونيفورم',
            ],
            [
                'key' => 'tool_bag',
                'label' => 'طلب حقيبة أدوات',
            ],
            [
                'key' => 'generate_health_card',
                'label' => 'طلب إصدار بطاقة صحية',
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
