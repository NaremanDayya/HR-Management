<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EmployeeFactory extends Factory
{
    protected $model = \App\Models\Employee::class;

    public function definition()
    {
        $faker = $this->faker;
        $faker->locale('ar_SA');

        $joiningDate = $faker->dateTimeBetween('-5 years', 'now');

        return [
            'name' => $faker->name,
            'joining_date' => $joiningDate,
            'work_duration' => null, // dynamic
            'job' => $faker->jobTitle,
            'vehicle_info' => [
                'vehicle_type' => $faker->randomElement(['سيارة', 'دراجة نارية', 'دراجة هوائية']),
                'vehicle_model' => $faker->word,
                'vehicle_ID' => $faker->bothify('??#####'),
            ],
            'health_card' => $faker->numerify('##########'),
            'work_area' => $faker->city,
            'project_id' => null,
        ];
    }
}
