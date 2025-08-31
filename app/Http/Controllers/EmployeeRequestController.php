<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeRequestResource;
use App\Models\EmployeeEditRequest;
use App\Models\EmployeeRequest;
use App\Models\Project;
use App\Models\RequestType;
use Carbon\Carbon;
use App\Models\SalaryIncrease;
use App\Models\TemporaryPermission;
use App\Models\User;
use App\Notifications\EditEmployeeDataRequestNotification;
use App\Notifications\EmployeeRequestStatusNotification;
use App\Notifications\NewEmployeeRequestNotification;
use App\Services\EmployeeRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EmployeeRequestController extends Controller
{
    public function __construct(protected EmployeeRequestService $employeeRequestService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'request_type', 'search', 'project_id', 'request_type_id']);

        $requests = $this->employeeRequestService->filterRequests($filters);

        $resources = EmployeeRequestResource::collection($requests);
        $projects = Project::all();
        $requestTypes = RequestType::all();
        $role = $role = Role::where('name', Auth::user()->role)->first();
        return view('EmployeeEditRequests.table', compact('resources', 'requestTypes', 'projects', 'role'));
    }
    public function storeEditRequest(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255',
            'edited_field' => [
                'required_if:type,edit_employee_data',
                'string',
                Rule::in(array_keys(EmployeeRequest::editableFields())),
            ],
            'description' => 'required|string|max:1000',
        ]);

        $requestType = RequestType::where('key', 'edit_employee_data')->firstOrFail();

        $empRequest = EmployeeRequest::create([
            'request_type_id' => $requestType->id,
            'employee_id' => $validated['employee_id'],
            'description' => $validated['description'],
            'edited_field' => $validated['edited_field'],
            'status' => 'pending',
            'requester_type' => 'App\Models\User',
            'requester_id' => Auth::id(),

        ]);
        $admin = User::where('role', 'admin')->first();
        $admin->notify(new NewEmployeeRequestNotification($empRequest, 'edit_employee_data'));



        return redirect()->back()->with('success', 'تم إرسال طلب التعديل بنجاح، بانتظار الموافقة.');
    }
    public function changeStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $editRequest = EmployeeRequest::findOrFail($id);
        // dd($editRequest);
        $editRequest->status = $request->status;
        $editRequest->response_date = now();
        $editRequest->save();
        // dd($editRequest->response_date);

        // dd($editRequest->status);
        $editTypeKey = optional($editRequest->requestType)->key;

        if ($editRequest->status === 'approved') {

            if ($editTypeKey === 'edit_employee_data') {
                TemporaryPermission::create([
                    'manager_id' => $editRequest->requester_id,
                    'employee_id' => $editRequest->employee_id,
                    'request_id' => $editRequest->id,
                    'edited_field' => $editRequest->edited_field,
                    'used' => false,
                ]);
            } elseif ($editTypeKey === 'replace_employee') {
                TemporaryPermission::create([
                    'manager_id' => $editRequest->requester_id,
                    'employee_id' => $editRequest->employee_id,
                    'request_id' => $editRequest->id,
                    'edited_field' => null,
                    'used' => false,
                ]);
            }
        }
        if ($editTypeKey === 'salary_advance') {
            $advance = $editRequest->employee->advances()
                ->where('request_id', $editRequest->id)
                ->first();

            if ($advance) {
                $advance->update([
                    'status' => $advance->request->status,
                    'approved_at' => now(),
                ]);
            }
        }
        if ($editTypeKey === 'salary_increase') {
            $payload = $editRequest->payload;

            $salaryIncrease = $editRequest->employee->increases()
                ->where('request_id', $editRequest->id)
                ->first();

            if ($salaryIncrease) {
                $salaryIncrease->update([
                    'status' => $salaryIncrease->request->status,
                    'approved_at' => now(),
                    'effective_date' => Carbon::createFromDate(now()->year, $salaryIncrease->reward_month, 1),
                ]);
            }
        }
        if ($editTypeKey === 'temporary_assignment') {
            $payload = $editRequest->payload;

            $temporaryAssignment = $editRequest->employee->temporaryAssignments()
                ->where('request_id', $editRequest->id)
                ->first();

            if ($temporaryAssignment) {
                $temporaryAssignment->update([
                    'status' => $editRequest->status,
                    'approved_at' => now(),
                ]);
            }
        }

        $editRequest->requester->notify(new EmployeeRequestStatusNotification(
            $editRequest,
            $editTypeKey,
            $editRequest->status
        ));
        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
