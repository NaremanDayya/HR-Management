<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmployeeEditRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'manager_id',
        'status',
        'request_type',
        'description',
        'response_date',
        'edited_field',
    ];
    protected $casts = [
        'response_date' => 'datetime',
    ];
    public function getResponseDateAttribute($value)
    {
        return $this->getRawOriginal('response_date')
            ? Carbon::parse($this->getRawOriginal('response_date'))
            : null;
    }
    public static function editableFields(): array
    {
        return [
            'name' => 'الاسم',
            'joining_date' => 'تاريخ الانضمام',
            'job' => 'الوظيفة',
            'vehicle_ID' => 'رقم لوحة المركبة',
            'health_card' => 'البطاقة الصحية',
            'work_area' => 'منطقة العمل',
            'salary' => 'الراتب',
            'english_level' => 'مستوى اللغة الإنجليزية',
            'certificate_type' => 'نوع الشهادة',
            'marital_status' => 'الحالة الاجتماعية',
            'members_number' => 'عدد أفراد الأسرة',
            'id_card' => 'رقم الهوية',
            'birthday' => 'تاريخ الميلاد',
            'nationality' => 'الجنسية',
            'gender' => 'الجنس',
            'personal_image' => 'الصورة الشخصية',
            'phone_number' => 'رقم الهاتف',
            'phone_type' => 'نوع الهاتف',
            'residence' => 'مقر الإقامة',
            'residence_neighborhood' => 'الحي السكني',
            'Tshirt_size' => 'مقاس التيشيرت',
            'pants_size' => 'مقاس البنطال',
            'area' => 'منطقة السكن',
            'vehicle_type' => 'نوع المركبة',
            'vehicle_model' => 'موديل الركبة',
            'email' => 'البريد الإلكتروني',
            'role' => 'المنصب',
            'iban' => 'رقم الاّيبان',
            'bank_name' => 'اسم البنك',
            'owner_account_name' => 'اسم صاحب الحساب البنكي',
            'supervisor_id' => 'المشرف',
        ];
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
