<?php

namespace App\Http\Controllers;

use App\Mail\AlertNotificationMail;
use App\Models\Advance;
use App\Models\Alert;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\EmployeeRequest;
use App\Models\EmployeeWorkHistory;
use App\Models\RequestType;
use App\Models\SalaryIncrease;
use App\Models\TemporaryProjectAssignment;
use App\Models\User;
use App\Notifications\NewEmployeeAlertNotification;
use App\Notifications\NewEmployeeDeductionNotification;
use App\Notifications\NewEmployeeRequestNotification;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class EmployeeActionController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }
    public function bulkAction(Request $request, $action)
    {
        // Validate employee IDs for all actions
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'الرجاء تحديد موظفين بشكل صحيح',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Extract validated employee IDs
        $employeeIds = $validator->validated()['ids'];

        try {
            switch ($action) {
                case 'activate':
                    return $this->handleActivate($employeeIds, $request);

                case 'deactivate':
                    return $this->handleDeactivate($employeeIds, $request);

                case 'change-password':
                    return $this->updatePassword($employeeIds, $request);

                case 'united_clothes':
                    return $this->handleUnitedClothes($employeeIds, $request);

                case 'tool_bag':
                    return $this->handleToolBag($employeeIds, $request);

                case 'salary_advance':
                    return $this->handleSalaryAdvance($employeeIds, $request);

                case 'generate_health_card':
                    return $this->handleHealthCard($employeeIds, $request);

                case 'salary_increase':
                    return $this->handleSalaryIncrease($employeeIds, $request);

                case 'add_alert':
                    return $this->handleAddAlert($employeeIds, $request);

                case 'add_deduction':
                    return $this->handleAddDeduction($employeeIds, $request);
                case 'replacement_request':
                    return $this->handleReplacementRequest($employeeIds, $request);
                case 'temporary_assignment':
                    return $this->handleTemporaryAssignment($employeeIds, $request);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'الإجراء المطلوب غير معروف',
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تنفيذ العملية: ' . $e->getMessage(),
            ], 500);
        }
    }


    private function handleActivate(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
        ]);

        $userIds = Employee::whereIn('id', $employeeIds)->pluck('user_id');

        User::whereIn('id', $userIds)->update([
            'account_status' => 'active',
        ]);

        Employee::whereIn('id', $employeeIds)->update([
            'joining_date' => $validated['start_date'],
        ]);

        foreach ($employeeIds as $id) {
            EmployeeWorkHistory::create([
                'employee_id' => $id,
                'start_date' => $validated['start_date'],
                'end_date' => null,
                'status' => 'active',
                'work_days' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تفعيل الحسابات المحددة بنجاح'
        ]);
    }

    private function handleDeactivate(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'stop_reason' => 'required|string|max:255',
            'stop_date' => 'required|date',
            'other_stop_reason' => 'nullable|string|max:255',
            'stop_description' => 'nullable|string|max:500',
        ]);

        $finalReason = ($validated['stop_reason'] === 'آخر' && !empty($validated['other_stop_reason']))
            ? $validated['other_stop_reason']
            : $validated['stop_reason'];

        User::whereIn('id', function ($query) use ($employeeIds) {
            $query->select('user_id')
                ->from('employees')
                ->whereIn('id', $employeeIds);
        })->update([
            'account_status' => 'inactive',
            'updated_at' => now(),
        ]);
        foreach ($employeeIds as $id) {
            $employee = Employee::find($id);
            if ($employee) {
                $payload = $employee->payload ?? [];
                $payload['stop_description'] = $validated['stop_description'] ?? null;
                $payload['stop_date'] = $validated['stop_date'] ?? now();

                $employee->update([
                    'stop_reason' => $finalReason,
                    'payload' => $payload,
                    'updated_at' => now(),
                ]);
                EmployeeWorkHistory::create([
                    'employee_id' => $id,
                    'start_date' => $employee->joining_date,
                    'end_date' => $validated['stop_date'] ?? now(),
                    'status' => 'stopped',
                    'stop_reason' => $finalReason,
                    'work_days' => 0
                ]);
            }
        }


        return response()->json([
            'success' => true,
            'message' => 'تم إيقاف حسابات الموظفين المحددة بنجاح',
            'data' => [
                'reason_used' => $finalReason,
                'employees_affected' => count($employeeIds)
            ]
        ]);
    }


    private function handleUnitedClothes(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'clothing_types' => 'required|array|min:1',
            'clothing_types.*' => 'in:tshirt,pants,cap',
        ]);

        $clothingTypes = $validated['clothing_types'];

        foreach ($employeeIds as $employeeId) {
            $employee = Employee::with('user')->findOrFail($employeeId);

            foreach ($clothingTypes as $type) {
                $sizeKey = match ($type) {
                    'tshirt' => 'Tshirt_size',
                    'pants' => 'pants_size',
                    'cap' => 'cap_size',
                    default => null,
                };

                $size = $employee->user->size_info[$sizeKey] ?? 'one size';

                $empRequest = EmployeeRequest::create([
                    'employee_id' => $employeeId,
                    'request_type_id' => RequestType::getIdByKey('united_clothes'),
                    'status' => 'pending',
                    'requester_type' => 'App\Models\User',
                    'requester_id' => Auth::id(),
                    'payload' => [
                        'size' => $size,
                        'type' => $type,
                        'quantity' => 1,
                    ],
                ]);
            }
        }
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($empRequest, 'united_clothes'));



        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلب الملابس الموحدة للموظفين المحددين',
        ]);
    }


    private function handleToolBag(array $employeeIds, Request $request)
    {

        foreach ($employeeIds as $employeeId) {
            $empRequest = EmployeeRequest::create([
                'employee_id' => $employeeId,
                'request_type_id' => RequestType::getIdByKey('tool_bag'),
                'status' => 'pending',
                'requester_type' => 'App\Models\User',
                'requester_id' => Auth::id(),
            ]);
        }
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($empRequest, 'tool_bag'));

        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلب حقيبة الأدوات للموظفين المحددين'
        ]);
    }

    private function handleSalaryAdvance(array $employeeIds, Request $request)
    {
        $validated = Validator::make($request->all(), [
            'advance_amount' => 'required|numeric|min:100',
            'months_to_repay' => 'required|integer|min:1',
            'start_deduction_at' => 'required|date|after_or_equal:today',
        ])->validate();

        $advanceAmount = $validated['advance_amount'];
        $monthsToRepay = $validated['months_to_repay'];
        $startDate = $validated['start_deduction_at'];
        $monthlyDeduction = round($advanceAmount / $monthsToRepay, 2);

        foreach ($employeeIds as $employeeId) {
            $employee = Employee::findOrFail($employeeId);
            $salary = $employee->salary;

            // Prevent division by zero
            $percentage = $salary > 0 ? round(($advanceAmount / $salary) * 100, 2) : 0;

            $requestRecord = EmployeeRequest::create([
                'employee_id' => $employeeId,
                'request_type_id' => RequestType::getIdByKey('salary_advance'),
                'status' => 'pending',
                'requested_at' => now(),
                'requester_type' => 'App\Models\User',
                'requester_id' => Auth::id(),
                'payload' => [
                    'amount' => $advanceAmount,
                    'salary' => $salary,
                    'percentage' => $percentage,
                    'months_to_repay' => $monthsToRepay,
                    'start_deduction_at' => $startDate,
                    'monthly_deduction' => $monthlyDeduction,
                ],
            ]);

            Advance::create([
                'employee_id'       => $employeeId,
                'manager_id'        => Auth::id(),
                'amount'            => $advanceAmount,
                'percentage'        => $percentage,
                'salary'            => $salary,
                'status'            => 'pending',
                'requested_at'      => now(),
                'request_id'        => $requestRecord->id,
                'months_to_repay'   => $monthsToRepay,
                'months_remaining'  => $monthsToRepay,
                'start_deduction_at' => $startDate,
                'monthly_deduction' => $monthlyDeduction,
            ]);
        }

        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($requestRecord, 'salary_advance'));

        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلب السلفة للموظفين المحددين'
        ]);
    }



    private function handleHealthCard(array $employeeIds, Request $request)
    {


        foreach ($employeeIds as $employeeId) {
            $empRequest = EmployeeRequest::create([
                'employee_id' => $employeeId,
                'request_type_id' => RequestType::getIdByKey('generate_health_card'),
                'status' => 'pending',
                'requested_at' => now(),
                'requester_type' => 'App\Models\User',
                'requester_id' => Auth::id(),
            ]);
        }
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($empRequest, 'generate_health_card'));


        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلب البطاقات الصحية للموظفين المحددين'
        ]);
    }
    private function handleTemporaryAssignment(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'target_project_id' => 'required|exists:projects,id',
            'start_date'        => 'required|date|after_or_equal:today',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'reason'            => 'required|string|max:500',
        ]);

        foreach ($employeeIds as $employeeId) {
            $employee = Employee::findOrFail($employeeId);
            $primaryProjectId = $employee->project_id;

            $requestRecord = EmployeeRequest::create([
                'employee_id'     => $employeeId,
                'request_type_id' => RequestType::getIdByKey('temporary_assignment'),
                'status'          => 'pending',
                'requester_type'  => 'App\Models\User',
                'requester_id'    => Auth::id(),
                'description'     => $validated['reason'],
                'payload'         => [
                    'primary_project_id' => $primaryProjectId,
                    'target_project_id'  => $validated['target_project_id'],
                    'start_date'         => $validated['start_date'],
                    'end_date'           => $validated['end_date'],
                ],
            ]);

            TemporaryProjectAssignment::create([
                'employee_id'       => $employeeId,
                'from_project_id' => $primaryProjectId,
                'to_project_id' => $validated['target_project_id'],
                'start_date'        => $validated['start_date'],
                'end_date'          => $validated['end_date'],
                'reason'            => $validated['reason'],
                'status'            => 'pending',
                'manager_id'        => Auth::id(),
                'request_id'        => $requestRecord->id,
            ]);
        }
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($requestRecord, 'temporary_assignment'));


        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلبات التكليف المؤقت للموظفين المحددين.'
        ]);
    }

    private function handleSalaryIncrease(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'increase_amount' => 'required|numeric|min:100',
            'increase_percentage' => 'required|numeric|min:1|max:100',
            'reason' => 'required|string|max:500',
            'increase_type' => 'required|in:static,reward',
            'reward_month' => 'nullable|required_if:increase_type,reward|integer|between:1,12',
        ]);

        foreach ($employeeIds as $employeeId) {
            $employee = Employee::findOrFail($employeeId);
            $newSalary = $employee->salary + $validated['increase_amount'];

            $payload = [
                'current_salary' => $employee->salary,
                'increase_amount' => $validated['increase_amount'],
                'increase_percentage' => $validated['increase_percentage'],
                'new_salary' => $newSalary,
                'increase_type' => $validated['increase_type'],
            ];

            if ($validated['increase_type'] === 'reward') {
                $payload['reward_month'] = $validated['reward_month'];
            }

            $requestRecord = EmployeeRequest::create([
                'employee_id' => $employeeId,
                'request_type_id' => RequestType::getIdByKey('salary_increase'),
                'status' => 'pending',
                'requester_type' => 'App\Models\User',
                'description' => $validated['reason'],
                'requester_id' => Auth::id(),
                'payload' => $payload,
            ]);

            $salaryIncreaseData = [
                'employee_id' => $employeeId,
                'manager_id' => Auth::id(),
                'previous_salary' => $employee->salary,
                'increase_amount' => $validated['increase_amount'],
                'increase_percentage' => $validated['increase_percentage'],
                'new_salary' => $newSalary,
                'reason' => $validated['reason'],
                'status' => 'pending',
                'requested_at' => now(),
                'request_id' => $requestRecord->id,
                'increase_type' => $validated['increase_type'],
            ];

            if ($validated['increase_type'] === 'reward') {
                $salaryIncreaseData['reward_month'] = $validated['reward_month'];
                $salaryIncreaseData['is_reward'] = true;
            }

            SalaryIncrease::create($salaryIncreaseData);
        }

        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($requestRecord, 'salary_increase'));

        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلب زيادة الراتب للموظفين المحددين'
        ]);
    }
    private function handleReplacementRequest(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'replacement_reason' => 'required|string|max:255',
            'other_reason' => 'nullable|string|max:255',
            'replacement_description' => 'nullable|string|max:500',
        ]);

        // Determine the final reason
        $finalReason = $validated['replacement_reason'] === 'آخر'
            ? $validated['other_reason']
            : $validated['replacement_reason'];

        foreach ($employeeIds as $employeeId) {
            $employee = Employee::findOrFail($employeeId);

            $empRequest = EmployeeRequest::create([
                'employee_id' => $employeeId,
                'request_type_id' => RequestType::getIdByKey('replace_employee'),
                'status' => 'pending',
                'requester_type' => 'App\Models\User',
                'requester_id' => Auth::id(),
                'description' => $finalReason,
                'payload' => [
                    'current_employee_name' => $employee->user?->name,
                    'current_position' => $employee->job_title,
                    'details' => $validated['replacement_description'] ?? null,
                ],
            ]);
        }

        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($empRequest, 'replace_employee'));

        return response()->json([
            'success' => true,
            'message' => 'تم تقديم طلب الاستبدال للموظفين المحددين',
        ]);
    }


    private function handleAddAlert(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'alert_reason' => 'required|string|max:500',
            'alert_title' => 'required|string'
        ]);

        $successCount = 0;
        $failedCount = 0;
        $manager = Auth::user();
        $companyName = "افاق الخليج-نظام إدارة الموارد البشرية";

        foreach ($employeeIds as $employeeId) {
            try {
                $employee = Employee::with('user')->findOrFail($employeeId);

                // Validate required data exists
                if (!$employee->user) {
                    throw new \Exception('Employee user record not found');
                }

                if (!isset($employee->user->contact_info['phone_number'])) {
                    throw new \Exception('Employee phone number missing');
                }

                if (!isset($manager->contact_info['phone_number'])) {
                    throw new \Exception('Manager phone number missing');
                }

                // Prepare message first
                $messageData = [
                    'company_name' => $companyName,
                    'manager_name' => $manager->name,
                    'employee_name' => $employee->user->name,
                    'manager_whatsapp' => $manager->contact_info['phone_number'],
                    'alert_title' => $validated['alert_title'],
                    'alert_message' => $validated['alert_reason']
                ];

                $message = $this->whatsAppService->formatAlertMessage($messageData);



                if (empty($message)) {
                    throw new \Exception('Failed to generate alert message');
                }

                $sendResult = $this->whatsAppService->send(
                    $employee->user->contact_info['phone_number'],
                    $message
                );
                if (!empty($employee->user->email)) {
                    Mail::to($employee->user->email)->send(new AlertNotificationMail($messageData));
                }

                $alertData = [
                    'employee_id' => $employeeId,
                    'reason' => $validated['alert_reason'],
                    'title' => $validated['alert_title'],
                    'manager_id' => Auth::id(),
                    'message_sent' => $message, // Ensure this is set
                    'message_sid' => $sendResult['message_sid'] ?? null,
                    'delivery_status' => $sendResult['success'] ? 'queued' : 'failed'
                ];

                $alert = Alert::create($alertData);

                if ($sendResult['success']) {
                    $successCount++;
                    Employee::where('id', $employeeId)->increment('alerts_number');
                } else {
                    $failedCount++;
                    Log::error('Failed to send alert to employee ' . $employeeId, [
                        'sendResult' => $sendResult,
                        'alertData' => $alertData
                    ]);
                }
            } catch (\Exception $e) {
                $failedCount++;

                Alert::create([
                    'employee_id' => $employeeId,
                    'reason' => $validated['alert_reason'],
                    'title' => $validated['alert_title'],
                    'manager_id' => Auth::id(),
                    'message_sent' => 'Failed to generate message: ' . $e->getMessage(),
                    'delivery_status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);

                Log::error('Alert processing failed for employee ' . $employeeId, [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                continue;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الإنذار للموظفين المحددين',
            'stats' => [
                'total' => count($employeeIds),
                'success' => $successCount,
                'failed' => $failedCount
            ]
        ]);
    }

    private function handleAddDeduction(array $employeeIds, Request $request)
    {
        $validated = $request->validate([
            'deduction_amount' => 'required|numeric|min:1',
            'deduction_reason' => 'required|string|max:500',
            'deduction_percentage' => 'required|numeric|min:1|max:100',

        ]);

        foreach ($employeeIds as $employeeId) {
            $employee = Employee::findOrFail($employeeId);
            Deduction::create([
                'employee_id' => $employeeId,
                'manager_id' => Auth::id(),
                'value' => $validated['deduction_amount'],
                'reason' => $validated['deduction_reason'],
                'payload' => [
                    'current_salary' => $employee->salary,
                    'deduction_amount' => $validated['deduction_amount'],
                    'deduction_percentage' => $validated['deduction_percentage'],
                    'new_salary' => $employee->salary - $validated['deduction_amount'],
                ],

            ]);

            // Update employee's deductions count
            $employee = Employee::where('id', $employeeId)->first();
            $employee->increment('deductions_number');
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                $admin->notify(new NewEmployeeDeductionNotification(
                    $employee,
                    $validated['deduction_amount'],
                    $validated['deduction_reason']
                ));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تطبيق الخصم على الموظفين المحددين'
        ]);
    }
    public function updatePassword(array $employeeIds, Request $request)
    {
        try {
            $validated = $request->validate([
                'employeePassword' => [
                    'required',
                    'confirmed',
                    Password::min(8)->mixedCase()->numbers(),
                ],
            ]);

            $csvPath = 'exports/employees_credentials.csv';

            if (!Storage::exists($csvPath)) {
                Storage::put($csvPath, "إسم الموظف,البريد الإلكتروني,كلمة المرور\n");
            }

            $existingContent = Storage::get($csvPath);
            $lines = explode("\n", $existingContent);
            $headers = array_shift($lines);

            $newContent = [$headers];
            $csvEmails = [];

            foreach ($lines as $line) {
                if (empty(trim($line))) continue;
                $data = str_getcsv($line);
                $csvEmails[$data[1]] = $line;
            }

            foreach ($employeeIds as $employeeId) {
                $employee = Employee::with('user')->findOrFail($employeeId);
                $user = $employee->user;

                if (!$user) {
                    continue;
                }

                $user->update([
                    'password' => Hash::make($validated['employeePassword']),
                ]);

                $credentials = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $validated['employeePassword'],
                ];

                $csvEmails[$credentials['email']] = implode(',', $credentials);
            }

            foreach ($csvEmails as $line) {
                $newContent[] = $line;
            }

            Storage::put($csvPath, implode("\n", $newContent));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث كلمات المرور بنجاح وتحديث سجل البيانات.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'فشل التحقق من صحة البيانات.'
            ], 422);
        } catch (\Exception $e) {
            Log::error('Bulk password update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث كلمات المرور: ' . $e->getMessage()
            ], 500);
        }
    }
}
