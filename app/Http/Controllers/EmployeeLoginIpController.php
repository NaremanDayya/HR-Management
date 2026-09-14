<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLoginIp;
use App\Models\PendingLoginAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeLoginIpController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status', 'all');

        $pendingQuery = PendingLoginAttempt::with('employee.user')
            ->where('status', 'pending')
            ->latest('attempted_at');

        if ($search) {
            $pendingQuery->whereHas('employee.user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $pendingAttempts = $pendingQuery->get();

        $employeesQuery = Employee::with(['user', 'loginIps' => fn ($q) => $q->latest()])
            ->whereHas('loginIps');

        if ($search) {
            $employeesQuery->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $employees = $employeesQuery->get();

        $stats = [
            'pending'   => PendingLoginAttempt::where('status', 'pending')->count(),
            'approved'  => PendingLoginAttempt::where('status', 'approved')->count(),
            'rejected'  => PendingLoginAttempt::where('status', 'rejected')->count(),
            'employees' => Employee::whereHas('loginIps')->count(),
        ];

        return view('Employees.loginIps', compact('employees', 'pendingAttempts', 'stats', 'search', 'statusFilter'));
    }

    public function approvePending(PendingLoginAttempt $attempt)
    {
        $attempt->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Add the device as an allowed temporary entry (no expiry by default).
        EmployeeLoginIp::updateOrCreate(
            [
                'employee_id'  => $attempt->employee_id,
                'device_token' => $attempt->device_token,
            ],
            [
                'ip_address'   => $attempt->ip_address,
                'is_allowed'   => true,
                'is_temporary' => true,
                'blocked_at'   => null,
            ]
        );

        return response()->json(['success' => true, 'message' => 'تمت الموافقة على الجهاز']);
    }

    public function rejectPending(PendingLoginAttempt $attempt)
    {
        $attempt->update([
            'status'      => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'تم رفض الجهاز']);
    }

    public function block(EmployeeLoginIp $employeeLoginIp)
    {
        $employeeLoginIp->update([
            'is_allowed' => false,
            'blocked_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'تم حظر الجهاز بنجاح']);
    }

    public function unblock(EmployeeLoginIp $employeeLoginIp)
    {
        $employeeLoginIp->update([
            'is_allowed' => true,
            'blocked_at' => null,
        ]);

        return response()->json(['success' => true, 'message' => 'تم رفع الحظر بنجاح']);
    }

    public function addTemporaryIp(Request $request, Employee $employee)
    {
        $request->validate([
            'ip_address'   => 'required|ip',
            'allowed_until' => 'nullable|date|after:now',
        ]);

        $employee->loginIps()->create([
            'ip_address'   => $request->ip_address,
            'is_allowed'   => true,
            'is_temporary' => true,
            'allowed_until' => $request->allowed_until ? Carbon::parse($request->allowed_until) : null,
        ]);

        return response()->json(['success' => true, 'message' => 'تمت إضافة الجهاز المؤقت بنجاح']);
    }
}
