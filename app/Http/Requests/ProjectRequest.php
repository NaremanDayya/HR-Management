<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProjectRequest extends FormRequest
{
    public $manager_user ;
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $projectId = $this->route('project') ? $this->route('project')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('projects', 'name')->ignore($projectId)
            ],
            'manager_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'manager_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم المشروع مطلوب',
            'name.unique' => 'اسم المشروع مسجل مسبقاً',
            'name.max' => 'اسم المشروع يجب ألا يتجاوز 255 حرف',
            'manager_name.max' => 'اسم المدير يجب ألا يتجاوز 255 حرف',
            'description.max' => 'الوصف يجب ألا يتجاوز 1000 حرف',
            'manager_id.exists' => 'المدير المحدد غير موجود في النظام'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->manager_name) {
            $this->manager_user = User::where('name', $this->manager_name)->first();

            $managerId = null;

            if ($this->manager_user && $this->manager_user->role === 'project_manager') {
                $managerId = $this->manager_user->id;
            }

            $this->merge([
                'manager_id' => $managerId,
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->manager_name) {
                if (!$this->manager_user) {
                    $validator->errors()->add(
                        'manager_name',
                        'المدير غير موجود في النظام'
                    );
                } elseif ($this->manager_user->role !== 'project_manager') {
                    $validator->errors()->add(
                        'manager_name',
                        'المدير موجود ولكن لا يمتلك صلاحية مدير مشروع'
                    );
                }
            }
        });
    }
}
