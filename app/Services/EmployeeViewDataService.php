<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class EmployeeViewDataService
{
    public function getDropdownData(): array
    {
        $authRole = Auth::user()->role;

        // Ordered hierarchy — highest first. Each role may filter/see only roles BELOW it.
        $hierarchy = [
            'admin',
            'hr_manager',
            'hr_assistant',
            'senior_project_manager',
            'project_manager',
            'area_manager',
            'supervisor',
            'shelf_stacker',
        ];

        $roleLabels = [
            'admin'                  => 'مسؤول النظام',
            'hr_manager'             => 'مدير موارد بشرية',
            'hr_assistant'           => 'مساعد مدير موارد بشرية',
            'senior_project_manager' => 'مدير مديري المشاريع',
            'project_manager'        => 'مدير مشروع',
            'area_manager'           => 'مشرف المشرفين',
            'supervisor'             => 'مشرف',
            'shelf_stacker'          => 'مصفف أرفف',
        ];

        // Compute roles strictly below the current user in the hierarchy
        $currentIndex = array_search($authRole, $hierarchy);
        $subordinateRoles = $currentIndex !== false
            ? array_slice($hierarchy, $currentIndex + 1)
            : [];

        // Admin sees every non-admin role; others see only subordinate roles
        $allowedRoles = $authRole === 'admin'
            ? array_filter($roleLabels, fn ($key) => $key !== 'admin', ARRAY_FILTER_USE_KEY)
            : array_intersect_key($roleLabels, array_flip($subordinateRoles));

        // Legacy aliases kept for any view still referencing them
        $allowedForProjectManager = array_intersect_key($roleLabels, array_flip(['area_manager', 'supervisor', 'shelf_stacker']));
        $allowedForHrManager      = array_intersect_key($roleLabels, array_flip(['hr_assistant', 'senior_project_manager', 'project_manager', 'area_manager', 'supervisor', 'shelf_stacker']));


        // Keys must match NationalityHelper::normalize() canonical output
        $nationalityFlags = [
            'فلسطين'    => 'ps',
            'سوريا'     => 'sy',
            'مصر'       => 'eg',
            'الأردن'    => 'jo',
            'لبنان'     => 'lb',
            'السودان'   => 'sd',
            'العراق'    => 'iq',
            'اليمن'     => 'ye',
            'الكويت'    => 'kw',
            'قطر'       => 'qa',
            'الإمارات'  => 'ae',
            'سعودي'     => 'sa',
            'ليبيا'     => 'ly',
            'الجزائر'   => 'dz',
            'تونس'      => 'tn',
            'المغرب'    => 'ma',
            'البحرين'   => 'bh',
            'موريتانيا' => 'mr',
            'الصومال'   => 'so',
            'الفلبين'   => 'ph',
            'الهند'     => 'in',
            'تركيا'     => 'tr',
            'باكستان'   => 'pk',
            'بنغلاديش'  => 'bd',
            'نيجيريا'   => 'ng',
            'إثيوبيا'   => 'et',
            'ميانمار'   => 'mm',
            'إريتريا'   => 'er',
            'نيبال'     => 'np',
            'سريلانكا'  => 'lk',
        ];


        return [
            'pantsSizes' => [
                '28' => '28',
                '30' => '30',
                '32' => '32',
                '34' => '34',
                '36' => '36',
                '38' => '38',
                '40' => '40',
                '42' => '42',
                '44' => '44',
                '46' => '46',
                '48' => '48',
                'xs' => 'XS',
                's' => 'S',
                'm' => 'M',
                'l' => 'L',
                'xl' => 'XL',
                'xxl' => 'XXL'
            ],
            'shirtSizes' => [
                'xxs' => 'XXS',
                'xs' => 'XS',
                's' => 'S',
                'm' => 'M',
                'l' => 'L',
                'xl' => 'XL',
                'xxl' => 'XXL',
                '3xl' => '3XL',
                '4xl' => '4XL',
                '5xl' => '5XL'
            ],
            'shoesSizes' => [
                '36' => '36',
                '37' => '37',
                '38' => '38',
                '39' => '39',
                '40' => '40',
                '41' => '41',
                '42' => '42',
                '43' => '43',
                '44' => '44',
                '45' => '45',
                '46' => '46',
                '47' => '47',
                '48' => '48',
                '49' => '49',
                '50' => '50',
            ],
            'projects' => in_array(Auth::user()->role, ['admin', 'hr_manager', 'hr_assistant', 'operations_manager'])
                ? Project::active()->pluck('name', 'id')->toArray()
                : Project::active()->where('manager_id', Auth::id())->pluck('name', 'id')->toArray(),

            'projectAllowedRoles' => Project::all()->mapWithKeys(
                fn($project) => [$project->id => $project->allowed_roles_or_default]
            ),

            'maritalStatuses' => [
                'single' => 'أعزب',
                'married' => 'متزوج',
                'divorced' => 'مطلق',
                'widowed' => 'أرمل',
            ],
            'englishLevels' => [
                'basic' => 'مبتدئ',
                'intermediate' => 'متوسط',
                'advanced' => 'متقدم',
            ],
            'certificateTypes' => [
                'high_school' => 'ثانوية عامة',
                'diploma' => 'دبلوم',
                'bachelor' => 'بكالوريوس',
                'master' => 'ماجستير',
                'phd' => 'دكتوراه',
            ],
            'residences' => User::whereNotNull('contact_info')
                ->get()
                ->pluck('contact_info.residence')
                ->filter()
                ->unique()
                ->values(),
            'residence_neighborhood' => User::whereNotNull('contact_info')
                ->get()
                ->pluck('contact_info.residence_neighborhood')
                ->filter()
                ->unique()
                ->values(),
            'nationalityFlags' => $nationalityFlags,
            'roleLabels' => $roleLabels,
            'allowedRoles' => $allowedRoles,
            'roles' => Role::where('name', '!=', 'admin')
                ->get()
                ->mapWithKeys(fn($role) => [$role->name => $roleLabels[$role->name] ?? $role->name]),

            'allowedForProjectManager' => $allowedForProjectManager,
            'allowedForHrManager' => $allowedForHrManager,

        ];
    }
}
