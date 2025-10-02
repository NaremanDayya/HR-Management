<?php

namespace App\Services;

use App\Models\EmployeeWorkHistory;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    public function create(array $data)
    {
        $imagePath = null;
        if (isset($data['personal_image']) && $data['personal_image']) {
            $imagePath = $data['personal_image']->store('employees/images', 's3');
            Storage::disk('s3')->setVisibility($imagePath, 'public');
        }
        // dd($imagePath);
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password'] ?? 'Password'),
            'name' => $data['name'],
            'id_card' => $data['id_card'],
            'birthday' => $data['birthday'],
            'nationality' => $data['nationality'],
            'account_status' => 'active',
            'gender' => $data['gender'],
            'personal_image' => $imagePath,
            'role' => $data['role'],
            'contact_info' => [
                'phone_number' => $data['phone_number'],
                'phone_type' => $data['phone_type'],
                'residence' => $data['residence'],
                'area' => $data['work_area'],
                'residence_neighborhood' => $data['residence_neighborhood'],
            ],
            'size_info' => [
                'Tshirt_size' => $data['Tshirt_size'],
                'pants_size' => $data['pants_size'],
                'Shoes_size' => $data['Shoes_size'],
            ],
        ]);


        $employee = Employee::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'job' => $data['job'],
            'joining_date' => $data['joining_date'],
            'vehicle_info' => [
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_model' => $data['vehicle_model'],
                'vehicle_ID' => $data['vehicle_ID'],
            ],
            'health_card' => $data['health_card'],
            'work_area' => $data['work_area'],
            'salary' => $data['salary'],
            'english_level' => $data['english_level'],
            'certificate_type' => $data['certificate_type'],
            'marital_status' => $data['marital_status'],
            'members_number' => $data['members_number'],
            'owner_account_name' => $data['owner_account_name'],
            'iban' => 'SA' . $data['iban'],
            'bank_name' => $data['bank_name'],
        ]);
        EmployeeWorkHistory::create([
            'employee_id' => $employee->id,
            'start_date' => $employee->joining_date,
            'end_date' => null,
            'status' => 'active',
        ]);
        if (isset($data['project'])) {
            $employee->project_id = $data['project'];
            $employee->manager_id = $employee->project->manager_id;
        }

        if (isset($data['supervisor'])) {
            $employee->supervisor_id = $data['supervisor'];
        }
        if (isset($data['area_manager'])) {
            $employee->area_manager_id = $data['area_manager'];
        }

        $employee->save();
        $credentials = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'] ?? 'Password',
        ];

        $csvPath = 'exports/employees_credentials.csv';
        if (!Storage::exists($csvPath)) {
            Storage::put($csvPath, "إسم الموظف,البريد الإلكتروني,كلمة المرور\n");
        }
        Storage::append($csvPath, implode(',', $credentials) . "\n");
        return $employee;
    }

    public function updateEmployee(Employee $employee, array $data, ?UploadedFile $image = null): Employee
    {

        $user = $employee->user;

        $editableField = request()->input('editable_field');
        $hasPermission = $employee->hasActivePermission($employee, 'edit_employee_data', $editableField, true);

        // dd($editableField);
        switch ($editableField) {
            case 'name':
                $user->name = $data['name'];
                break;

            case 'id_card':
                $user->id_card = $data['id_card'];
                break;
            case 'role':
                $user->assignRole($data['role']);
                break;

            case 'nationality':
                $user->nationality = $data['nationality'];
                break;

            case 'birthday':
                $user->birthday = $data['birthday'] ?? null;
                break;

            case 'gender':
                $user->gender = $data['gender'];
                break;

            case 'residence':
            case 'residence_neighborhood':
            case 'phone_number':
            case 'phone_type':
            case 'work_area':
                $contactInfo = $user->contact_info ?? [];
                $contactInfo[$editableField] = $data[$editableField] ?? null;
                $user->contact_info = $contactInfo;
                break;

            case 'Tshirt_size':
            case 'pants_size':
            case 'Shoes_size':
                $sizeInfo = $user->size_info ?? [];
                $sizeInfo[$editableField] = $data[$editableField] ?? null;
                $user->size_info = $sizeInfo;
                break;

            case 'certificate_type':
            case 'english_level':
            case 'marital_status':
                $user->$editableField = $data[$editableField] ?? $user->$editableField;
                break;

            case 'email':
                $user->email = $data['email'];
                break;

            case 'personal_image':
                if ($image) {
                    $path = $image->store('personal_images', 'public');
                    $user->personal_image = $path;
                }
                break;

            case 'job':
                $employee->job = $data['job'];
                break;
            case 'iban':
                $employee->iban = 'SA' . $data['iban'];

                break;
            case 'owner_account_name':
                $employee->owner_account_name = $data['owner_account_name'];
                break;

            case 'last_working_date':
                $employee->job = $data['last_working_date'];
                $user->account_status = 'inactive';
                break;

            case 'supervisor_id':
                $employee->supervisor_id = $data['supervisor_id'];
                break;
            case 'bank_name':
                $employee->bank_name = $data['bank_name'];
                break;
            case 'account_status':
                $employee->user->account_status = $data['account_status'];
                break;

            case 'vehicle_type':
            case 'vehicle_model':
            case 'vehicle_ID':
                $vehicle = $employee->vehicle_info ?? [];
                $vehicle[$editableField] = $data[$editableField] ?? null;
                $employee->vehicle_info = $vehicle;
                break;

            case 'members_number':
            case 'joining_date':
            case 'health_card':
            case 'project':
            case 'salary':
                $employee->$editableField = $data[$editableField] ?? $employee->$editableField;
                break;

            default:
                throw new \Exception('لا يمكن تعديل هذا الحقل.');
        }

        $user->save();
        $employee->save();
        $hasPermission->update(['used' => true]);

        return $employee;
    }
    public function filterEmployees(array $filters)
    {
        $query = Employee::with(['user', 'project.manager', 'managedProjects', 'deductions', 'advanceDeductions']);
        $authUser = Auth::user();

        if ($authUser->role === 'project_manager') {
            $query->whereHas('project', function ($q) use ($authUser) {
                $q->where('manager_id', $authUser->id);
            });
        } elseif (in_array($authUser->role, ['admin', 'hr_manager', 'hr_assistant'])) {
            $query->where('user_id', '!=', $authUser->id);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('job', 'like', "%$search%")
                    ->orWhere('salary', 'like', "%$search%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%")
                            ->orWhere('id_card', 'like', "%$search%")
                            ->orWhere('nationality', 'like', "%$search%")
                            ->orWhere('contact_info->phone_number', 'like', "%$search%");
                    })
                    ->orWhereHas('project', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    })->orWhereHas('supervisor', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        if (!empty($filters['account_status'])) {
            if ($filters['account_status'] === 'blacklist') {
                $query->whereIn('stop_reason', ['سوء اداء', 'سوء أداء']);
            } else {
                $query->whereHas('user', function ($q) use ($filters) {
                    $q->where('account_status', $filters['account_status']);
                });
            }
        }

        if (!empty($filters['project'])) {
            $query->where('project_id', $filters['project']);
        }
        if (!empty($filters['health_card'])) {
            $query->where('health_card', $filters['health_card']);
        }
        if (!empty($filters['nationality'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('nationality', $filters['nationality']);
            });
        }

        if (!empty($filters['marital_status'])) {
            $query->where('marital_status', $filters['marital_status']);
        }

        if (!empty($filters['english_level'])) {
            $query->where('english_level', $filters['english_level']);
        }

        if (!empty($filters['residence'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where(function ($subQuery) use ($filters) {
                    $subQuery->where('contact_info->residence', $filters['residence']);
                });
            });
        }
         if (!empty($filters['residence_neighborhood'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where(function ($subQuery) use ($filters) {
                    $subQuery->where('contact_info->residence_neighborhood', $filters['residence_neighborhood']);
             });
            });
        }


        if (!empty($filters['role'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('role', $filters['role']);
            });
        }

        return $query->get();
    }

    public function replaceEmployee(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $oldEmployee = Employee::findOrFail($data['old_employee_id']);
                $finalReason = $data['replacement_reason'] === 'آخر'
                    ? $data['other_reason']
                    : $data['replacement_reason'];
                $oldEmployee->update([
                    'stop_reason' => $finalReason,
                    'stop_date' => $data['last_working_date'],
                ]);

                $newEmployee = $this->create($data);

                $oldEmployee->user()->update([
                    'account_status' => 'inactive',
                ]);

                $replacement = $oldEmployee->replacements()->create([
                    'new_employee_id' => $newEmployee->id,
                    'replacement_date' => now(),
                    'last_working_date' => $data['last_working_date'],
                    'reason' => $data['replacement_reason']
                ]);

                $oldEmployee->update([
                    'replaced_by_id' => $newEmployee->id,
                ]);

                $hasPermission = $oldEmployee->hasActivePermission($oldEmployee, 'replace_employee', null, true);
                if ($hasPermission) {
                    $hasPermission->update(['used' => true]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'تم استبدال الموظف بنجاح',
                    'data' => [
                        'old_employee' => $oldEmployee->only(['id', 'name']),
                        'new_employee' => $newEmployee->only(['id', 'name']),
                        'replacement' => $replacement
                    ]
                ]);
            });
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'فشل عملية الاستبدال: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ], 500);
        }
    }
    public function getReplacementHistory($employeeId)
    {
        return Employee::with(['replacements.newEmployee.user', 'replacedBy.oldEmployee.user'])
            ->where('id', $employeeId)
            ->orWhereHas('replacements', function ($q) use ($employeeId) {
                $q->where('new_employee_id', $employeeId);
            })
            ->first();
    }
}
