<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReplaceEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation()
    {
        // dd($this->all());
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'old_employee_id' => 'required|exists:employees,id',
            'last_working_date' => 'required|date',
            'replacement_reason' => 'required|string|max:255',
            'other_reason' => 'nullable|string|max:255',
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $blacklisted = User::where('name', $value)
                        ->where('account_status', 'deactivated')
                        ->whereHas('employee', function ($query) {
                            $query->whereIn('stop_reason', ['سوء اداء', 'سوء أداء']);
                        })
                        ->exists();

                    if ($blacklisted) {
                        $fail('blacklisted_employee');
                    }
                },
            ],
            'email' => 'required|email',
            'job' => 'nullable|string|max:255',
            'joining_date' => 'required|date',
            'birthday' => 'required|date',
            'age' => 'required|integer|min:10|max:100',
            'vehicle_type' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_ID' => [
                'required',
                'string',
                'max:255',
                Rule::unique('employees', 'vehicle_info->vehicle_ID')
            ],
            'residence' => 'required|string|max:255',
            'residence_neighborhood' => 'required|string|max:255',
            'Tshirt_size' => 'nullable|string|max:10',
            'pants_size' => 'nullable|string|max:10',
            'Shoes_size' => 'nullable|string|max:10',
            'bank_name' => 'required|string',
            'iban' => ['required', 'string', 'digits:22', 'unique:employees,iban'],
            'owner_account_name' => 'required|string|max:255',
            'gender' => 'required|in:female,male',
            'health_card' => 'nullable|string|max:255',
            'work_area' => 'nullable|string|max:255',
            'id_card' => 'required|string|max:20|unique:users,id_card',
            'phone_number' => 'required|string|digits:10',
            'password' => [
                Rule::requiredIf(function () {
                    return (Auth::user()->role === 'admin');
                }),
                'string',
                'min:8',
            ],
            'phone_type' => 'nullable|in:android,iphone',
            'nationality' => 'nullable|string|max:100',
            'role' => 'nullable|string|max:100',
            'project' => 'nullable|string|max:255',
            'personal_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'salary' => 'required|numeric|min:0|max:999999.99',
            'english_level' => 'required|in:basic,intermediate,advanced',
            'certificate_type' => 'nullable|string|max:100|in:high_school,diploma,bachelor,master,phd',
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
            'old_employee_id.required' => 'يجب تحديد الموظف المراد استبداله',
            'last_working_date.required' => 'تاريخ آخر يوم عمل مطلوب',
            'replacement_reason.required' => 'سبب الاستبدال مطلوب',
            'salary.required' => 'حقل الراتب الزامي',
            'salary.numeric' => 'يجب أن يكون الراتب رقم',
            'salary.min' => 'الراتب لا يمكن أن يكون أقل من صفر',
            'salary.max' => 'الراتب لا يمكن أن يتجاوز 999,999.99',
            'english_level.required' => 'مستوى اللغة الإنجليزية مطلوب',
            'blacklisted_employee' => 'لا يمكن إضافة هذا الموظف لأنه في القائمة السوداء بسبب سوء الأداء.',
            'marital_status.required' => 'الحالة الاجتماعية مطلوبة',
            'members_number.required' => 'عدد الأفراد مطلوب',
            'members_number.integer' => 'يجب أن يكون عدد الأفراد رقم صحيح',
            'members_number.min' => 'عدد الأفراد لا يمكن أن يكون أقل من صفر',

        ];
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
