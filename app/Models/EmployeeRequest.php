<?php

namespace App\Models;

use App\Scopes\YearScope;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    protected $fillable = [
        'status',
        'description',
        'response_date',
        'notes',
        'request_type_id',
        'edited_field',
        'employee_id',
        'requester_type',
        'requester_id',
        'payload',
    ];
    protected $casts = [
        'payload' => 'array',
        'response_date' => 'date',
    ];
    protected static function booted()
    {
        static::addGlobalScope(new YearScope);
    }
    public function requestType()
    {
        return $this->belongsTo(RequestType::class, 'request_type_id');
    }
    public function requester()
    {
        return $this->morphTo();
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function manager()
    {
        return $this->belongsTo(User::class, 'requester_id');
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
            'last_working_date' => 'تاريخ اخر يوم عمل',
            'iban' => 'رقم الاّيبان',
            'owner_account_name' => 'اسم صاحب الحساب البنكي',
            'supervisor_id' => 'المشرف',
            'bank_name' => 'اسم البنك',

        ];
    }
}
