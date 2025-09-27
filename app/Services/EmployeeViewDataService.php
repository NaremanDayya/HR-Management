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

        $roleLabels = [
            'project_manager' => 'مدير مشروع',
            'hr_manager' => 'مدير موارد بشرية',
            'hr_assistant' => 'مساعد مدير موارد بشرية',
//            'shelf_stacker' => 'مصفف أرفف',
//            'area_manager' => 'مدير منطقة',
//            'supervisor' => 'مشرف',
        ];
        $allowedForHrManager = [
            'project_manager' => 'مدير مشروع',
            'hr_assistant' => 'مساعد مدير موارد بشرية',
//            'shelf_stacker' => 'مصفف أرفف',
//            'area_manager' => 'مدير منطقة',
//            'supervisor' => 'مشرف',
        ];

        $allowedForProjectManager = [
            'shelf_stacker' => 'مصفف أرفف',
            'area_manager' => 'مدير منطقة',
            'supervisor' => 'مشرف',
        ];


        $nationalityFlags = [
            'فلسطيني' => 'ps',
            'سوري' => 'sy',
            'مصري' => 'eg',
            'أردني' => 'jo',
            'لبناني' => 'lb',
            'سوداني' => 'sd',
            'عراقي' => 'iq',
            'يمني' => 'ye',
            'كويتي' => 'kw',
            'قطري' => 'qa',
            'إماراتي' => 'ae',
            'سعودي' => 'sa',
            'ليبي' => 'ly',
            'جزائري' => 'dz',
            'تونسي' => 'tn',
            'مغربي' => 'ma',
            'بحريني' => 'bh',
            'موريتاني' => 'mr',
            'صومالي' => 'so',
            'فلبيني' => 'ph',
            'هندي' => 'in',
            'تركي' => 'tr',
            'باكستاني' => 'pk',
            'بنغالي' => 'bd',
            'نيجيري' => 'ng',
            'اثيوبي' => 'et',
            'بورما' => 'mm',
            'ارتيري' => 'er',
            'نيبالي' => 'np',
            'سيريلانكي' => 'lk',
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
            'projects' => in_array(Auth::user()->role, ['admin', 'hr_manager', 'hr_assistant'])
                ? Project::pluck('name', 'id')->toArray()
                : Project::where('manager_id', Auth::id())->pluck('name', 'id')->toArray(),

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
            'roles' => Role::where('name', '!=', 'admin')
                ->get()
                ->mapWithKeys(fn($role) => [$role->name => $roleLabels[$role->name] ?? $role->name]),

            'allowedForProjectManager' => $allowedForProjectManager,
            'allowedForHrManager' => $allowedForHrManager,

        ];
    }
}
