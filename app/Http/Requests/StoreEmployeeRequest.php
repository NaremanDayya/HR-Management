<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
        // dd('I am inside rules');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $blacklisted = Employee::where('name', $value)
                        ->whereHas('user', function ($query) {
                            $query->where('account_status', 'deactivated');
                        })
                        ->whereIn('stop_reason', ['سوء اداء', 'سوء أداء'])
                        ->exists();

                    if ($blacklisted) {
                        $fail('blacklisted_employee');
                    }
                },
            ],
            'email' => 'required|email|unique:users,email',
            'password' => [
                Rule::requiredIf(function () {
                    return (Auth::user()->role === 'admin');
                }),
                'string',
                'min:8',
            ],
            'job' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'birthday' => 'required|date',
            'bank_name' => 'required|string',
            'age' => 'required|integer|min:10|max:100',
            'owner_account_name' => 'required|string|max:255',
            'iban' => ['required', 'string', 'digits:22', 'unique:employees,iban'],
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
            'role' => 'required|string|max:100',
            'project' => [
                Rule::requiredIf(function () {
                    return Auth::user()->role === 'project_manager';
                }),
                'string',
                'max:255',
            ],
            'supervisor' => [
                Rule::requiredIf(function () {
                    return  request('role') == 'shelf_stacker';
                }),
                'integer',
                function ($attribute, $value, $fail) {
                    $employee = Employee::with('user')->find($value);

                    if (!$employee || $employee->user->role !== 'supervisor') {
                        $fail('المشرف المحدد غير صالح أو لا يملك الدور المناسب.');
                    }
                },
            ],
            'area_manager' => [
                Rule::requiredIf(function () {
//                    return Auth::user()->role === 'project_manager' && request('role') == 'supervisor';
                    return request('role') == 'supervisor';
                }),
                'integer',
                function ($attribute, $value, $fail) {
                    $employee = Employee::with('user')->find($value);

                    if (!$employee || $employee->user->role !== 'area_manager') {
                        $fail('مدير المنطقة المحدد غير صالح أو لا يملك الدور المناسب.');
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
            'members_number.integer' => 'يجب أن يكون عدد الأفراد رقم صحيح',
            'members_number.min' => 'عدد الأفراد لا يمكن أن يكون أقل من صفر',
            'supervisor.exists' => 'المشرف غير موجود',

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
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new JsonResponse([
            'success' => false,
            'message' => 'يوجد أخطاء في المدخلات',
            'errors' => $validator->errors()
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
