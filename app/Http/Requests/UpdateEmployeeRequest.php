<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $blacklisted = Employee::where('name', $value)
                        ->whereHas('user', function ($query) {
                            $query->where('account_status', 'inactive');
                        })
                        ->whereIn('stop_reason', ['سوء اداء', 'سوء أداء'])
                        ->exists();
                    if ($blacklisted) {
                        $fail('blacklisted_employee');
                    }
                },
            ],
            'job' => 'nullable|string|max:255|regex:/[a-zA-Zأ-ي\s]+/u',
            'id_card' => 'nullable|string|size:10|regex:/^[12][0-9]{9}$/|unique:users,id_card',
            'nationality' => 'nullable|string|max:100',
            'birthday' => 'nullable|date',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female',
            'residence' => 'nullable|string|max:255',
            'residence_neighborhood' => 'nullable|string|max:255',
            'vehicle_type' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_ID' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'vehicle_info->vehicle_ID')
            ],
            'members_number' => 'nullable|integer|min:0',
            'certificate_type' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'phone_type' => 'nullable|string|in:android,iphone',
            'role' => 'nullable|string|max:255',
            'work_area' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'Tshirt_size' => 'nullable|string|max:50',
            'pants_size' => 'nullable|string|max:50',
            'Shoes_size' => 'nullable|string|max:10',
            'health_card' => 'nullable|boolean',
            'project' => 'nullable|exists:projects,id',
            'salary' => 'nullable|numeric|min:0',
            'english_level' => 'nullable|string|max:50',
            'marital_status' => 'nullable|string|max:50',
            'personal_image' => 'nullable|image|max:2048',
            'bank_name' => 'nullable|string',
            'iban' => ['required', 'string', 'digits:22', 'unique:employees,iban'],
            'owner_account_name' => 'nullable|string|max:255',
            'supervisor' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->role('supervisor');
                }),
            ],
            'area_manager' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->role('area_manager');
                }),
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
            'marital_status.required' => 'الحالة الاجتماعية مطلوبة',
            'members_number.required' => 'عدد الأفراد مطلوب',
            'members_number.integer' => 'يجب أن يكون عدد الأفراد رقم صحيح',
            'members_number.min' => 'عدد الأفراد لا يمكن أن يكون أقل من صفر',
            'job.regex' => 'يجب ان تكون الوظيفة نص',
            'blacklisted_employee' => 'لا يمكن إضافة هذا الموظف لأنه في القائمة السوداء بسبب سوء الأداء.',


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
                    ->where('id', '!=', $this->route('employee')->user_id) // Exclude current employee
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('phone_number', 'رقم الجوال مستخدم من قبل.');
                }
            }
        });
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
