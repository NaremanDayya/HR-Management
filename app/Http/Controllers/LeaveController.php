<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    private function authorizeHR(): void
    {
        abort_unless(
            in_array(Auth::user()->role, ['admin', 'hr_manager', 'hr_assistant']),
            403,
            'ليس لديك صلاحية الوصول إلى هذه الصفحة.'
        );
    }

    public function index(Request $request)
    {
        $this->authorizeHR();

        $query = LeaveRequest::with(['employee.user', 'reviewer'])->latest();

        if ($employeeId = $request->get('employee_id')) {
            $query->where('employee_id', $employeeId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('leave_type')) {
            $query->where('leave_type', $type);
        }

        $leaveRequests = $query->paginate(30)->withQueryString();
        $employees = Employee::with('user')
            ->whereHas('user', fn($q) => $q->where('account_status', 'active'))
            ->orderBy('name')
            ->get(['id', 'name', 'user_id']);

        return view('Leaves.index', compact('leaveRequests', 'employees'));
    }

    public function store(Request $request)
    {
        $this->authorizeHR();

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type'  => 'required|in:annual,sick,emergency,maternity,unpaid',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'status'      => 'required|in:pending,approved,rejected',
            'notes'       => 'nullable|string|max:500',
        ]);

        $start = Carbon::parse($data['start_date']);
        $end   = Carbon::parse($data['end_date']);
        $days  = $start->diffInDays($end) + 1;

        LeaveRequest::create([
            ...$data,
            'days_count'  => $days,
            'reviewed_by' => $data['status'] !== 'pending' ? Auth::id() : null,
            'reviewed_at' => $data['status'] !== 'pending' ? now() : null,
        ]);

        return response()->json(['success' => true, 'message' => 'تم إضافة الإجازة بنجاح']);
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorizeHR();

        $data = $request->validate([
            'status'         => 'required|in:pending,approved,rejected',
            'response_notes' => 'nullable|string|max:500',
        ]);

        $leaveRequest->update([
            'status'         => $data['status'],
            'response_notes' => $data['response_notes'] ?? null,
            'reviewed_by'    => Auth::id(),
            'reviewed_at'    => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'تم تحديث حالة الإجازة']);
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $this->authorizeHR();
        $leaveRequest->delete();
        return response()->json(['success' => true, 'message' => 'تم حذف الإجازة']);
    }
}
