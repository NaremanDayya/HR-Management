<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PublicEmployeeRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Blacklist is evaluated purely by name + stop_reason, regardless of
                    // current account status, id_card, or phone number.
                    $blacklisted = Employee::where('name', $value)
                        ->whereIn('stop_reason', ['سوء اداء', 'سوء أداء'])
                        ->exists();

                    if ($blacklisted) {
                        $fail('blacklisted_employee');
                    }
                },
            ],
            'email' => 'required|email|unique:users,email',
            'job' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'birthday' => 'required|date',
            'bank_name' => 'required|string',
            'age' => 'required|integer|min:10|max:100',
            'owner_account_name' => 'required|string|max:255',
            'iban' => ['required', 'string', 'digits:22'],
            'vehicle_ID' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'vehicle_info->vehicle_ID'),
            ],
            'vehicle_type' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'residence' => 'required|string|max:255',
            'residence_neighborhood' => 'required|string|max:255',
            'Tshirt_size' => 'nullable|string|max:10',
            'pants_size' => 'nullable|string|max:10',
            'Shoes_size' => 'nullable|string|max:10',
            'gender' => 'required|in:female,male',
            'health_card' => 'required|string|max:255',
            'work_area' => 'required|string|max:255',
            'id_card' => 'required|string|size:10|regex:/^[12][0-9]{9}$/|unique:users,id_card',
            'phone_number' => [
                'required',
                'string',
                'digits:10',
                Rule::unique('users', 'contact_info->phone_number'),
            ],
            'phone_type' => 'required|in:android,iphone',
            'nationality' => 'required|string|max:100',
            'supervisor' => [
                Rule::requiredIf(fn () => $this->route('role') === 'shelf_stacker' && $this->projectHasEmployeesWithRole('supervisor')),
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        return;
                    }
                    $employee = Employee::with('user')->find($value);

                    if (!$employee || $employee->user->role !== 'supervisor') {
                        $fail('المشرف المحدد غير صالح أو لا يملك الدور المناسب.');
                    }
                },
            ],
            'area_manager' => [
                Rule::requiredIf(fn () => $this->route('role') === 'supervisor' && $this->projectHasEmployeesWithRole('area_manager')),
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        return;
                    }
                    $employee = Employee::with('user')->find($value);

                    if (!$employee || $employee->user->role !== 'area_manager') {
                        $fail('مشرف المشرفين المحدد غير صالح أو لا يملك الدور المناسب.');
                    }
                },
            ],
            'personal_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'salary' => 'required|numeric|min:0|max:999999.99',
            'english_level' => 'required|in:basic,intermediate,advanced',
            'certificate_type' => 'required|string|max:100|in:high_school,diploma,bachelor,master,phd',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'members_number' => [
                Rule::requiredIf(function () {
                    return $this->marital_status !== 'single';
                }),
                'nullable',
                'integer',
                'min:0',
                'max:20',
            ],
        ];
    }

    public function messages()
    {
        return [
            'salary.required' => 'حقل الراتب الزامي',
            'salary.numeric' => 'يجب أن يكون الراتب رقم',
            'salary.min' => 'الراتب لا يمكن أن يكون أقل من صفر',
            'salary.max' => 'الراتب لا يمكن أن يتجاوز 999,999.99',
            'english_level.required' => 'مستوى اللغة الإنجليزية مطلوب',
            'blacklisted_employee' => 'لا يمكن إضافة هذا الموظف لأنه في القائمة السوداء بسبب سوء الأداء.',
            'email.unique' => 'الإيميل مستخدم من قبل',
            'id_card.unique' => 'رقم الهوية موجود لموظف اخر',
            'marital_status.required' => 'الحالة الاجتماعية مطلوبة',
            'iban.digits' => 'رقم الايبان يجب ان يكون 22 رقم',
            'members_number.required' => 'عدد الأفراد مطلوب',
            'supervisor.required' => 'يجب اختيار المشرف المباشر',
            'area_manager.required' => 'يجب اختيار مشرف المشرفين',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $birthday = $this->input('birthday');
            $age = $this->input('age');
            $phone_number = $this->input('phone_number');
            if ($birthday && $age) {
                $calculatedAge = Carbon::parse($birthday)->age;
                if ($calculatedAge != $age) {
                    $validator->errors()->add('age', 'العمر لا يتطابق مع تاريخ الميلاد المدخل.');
                }
            }
            if ($phone_number) {
                $exists = DB::table('users')
                    ->where('contact_info->phone_number', $phone_number)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('phone_number', 'رقم الجوال مستخدم من قبل.');
                }
            }
        });
    }

    private function projectHasEmployeesWithRole(string $role): bool
    {
        $project = $this->route('project');

        if (! $project) {
            return false;
        }

        return Employee::where('project_id', $project->id)
            ->whereHas('user', fn ($q) => $q->where('role', $role)->where('account_status', 'active'))
            ->exists();
    }
}
