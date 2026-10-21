<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Uniform;
use App\Models\UniformRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UniformController extends Controller
{
    // Roles that see every employee and may approve/reject uniform requests.
    private const REVIEWER_ROLES = ['admin', 'hr_manager', 'hr_assistant'];

    private function isReviewer(): bool
    {
        return in_array(Auth::user()->role, self::REVIEWER_ROLES);
    }

    private function isProjectManager(): bool
    {
        return Auth::user()->role === 'project_manager';
    }

    /** Project managers may only touch employees that belong to a project they manage. */
    private function authorizeEmployeeAccess(int $employeeId): void
    {
        if ($this->isReviewer()) {
            return;
        }

        abort_unless($this->isProjectManager(), 403, 'ليس لديك صلاحية الوصول إلى اليونيفورم.');

        $allowed = Employee::where('id', $employeeId)
            ->whereIn('project_id', $this->managedProjectIds())
            ->exists();

        abort_unless($allowed, 403, 'هذا الموظف لا يتبع أحد مشاريعك.');
    }

    private function managedProjectIds()
    {
        return Project::withoutGlobalScopes()
            ->where('manager_id', Auth::id())
            ->pluck('id');
    }

    public function index()
    {
        abort_unless($this->isReviewer() || $this->isProjectManager(), 403, 'ليس لديك صلاحية الوصول إلى اليونيفورم.');

        $employeesQuery = Employee::with(['user', 'uniform', 'uniformRequests'])
            ->whereHas('user', fn($q) => $q
                ->where('account_status', 'active')
                ->whereIn('role', ['shelf_stacker', 'supervisor', 'area_manager'])
            );

        $requestsQuery = UniformRequest::with(['employee.user'])->latest();

        if ($this->isProjectManager()) {
            $projectIds = $this->managedProjectIds();
            $employeesQuery->whereIn('project_id', $projectIds);
            $requestsQuery->whereHas('employee', fn($q) => $q->whereIn('project_id', $projectIds));
        }

        $employees = $employeesQuery
            ->get()
            ->sortBy(fn($e) => [
                $e->uniform ? 0 : 1,
                $e->uniform?->received_at?->format('Y-m-d') ?? '9999-99-99',
            ])
            ->values();

        $requests = $requestsQuery->get();

        return view('uniforms.index', compact('employees', 'requests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'tshirt_count'  => 'required|integer|min:0',
            'hat_count'     => 'required|integer|min:0',
            'id_card_count' => 'required|integer|min:0',
            'tool_bag_count'=> 'required|integer|min:0',
            'received_at'   => 'required|date',
        ]);

        $this->authorizeEmployeeAccess((int) $validated['employee_id']);

        Uniform::updateOrCreate(
            ['employee_id' => $validated['employee_id']],
            $validated
        );

        return response()->json(['success' => true, 'message' => 'تم حفظ بيانات اليونيفورم']);
    }

    public function submitRequest(Request $request)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'type'          => 'required|in:request,return',
            'tshirt_count'  => 'required|integer|min:0',
            'hat_count'     => 'required|integer|min:0',
            'id_card_count' => 'required|integer|min:0',
            'tool_bag_count'=> 'required|integer|min:0',
            'notes'         => 'nullable|string|max:500',
        ]);

        $this->authorizeEmployeeAccess((int) $validated['employee_id']);

        $employee = Employee::with('uniform')->findOrFail($validated['employee_id']);

        $costAmount = 0;
        $deductCost = null;

        if ($validated['type'] === 'request' && $validated['tshirt_count'] > 0) {
            $uniform = $employee->uniform;
            $eligible = $uniform && $uniform->isEligibleForFree();
            if (!$eligible) {
                $costAmount = $validated['tshirt_count'] * 50;
                $deductCost = null; // admin will decide
            }
        }

        $req = UniformRequest::create(array_merge($validated, [
            'status'      => 'pending',
            'cost_amount' => $costAmount,
            'deduct_cost' => $deductCost,
        ]));

        return response()->json([
            'success'     => true,
            'message'     => 'تم إرسال الطلب بنجاح',
            'cost_amount' => $costAmount,
        ]);
    }

    public function reviewRequest(Request $request, UniformRequest $uniformRequest)
    {
        abort_unless($this->isReviewer(), 403, 'مراجعة طلبات اليونيفورم متاحة للإدارة فقط.');

        $validated = $request->validate([
            'status'      => 'required|in:approved,rejected',
            'deduct_cost' => 'nullable|boolean',
            'notes'       => 'nullable|string|max:500',
        ]);

        $uniformRequest->update([
            'status'      => $validated['status'],
            'deduct_cost' => $validated['deduct_cost'] ?? false,
            'cost_amount' => ($validated['deduct_cost'] ?? false) ? $uniformRequest->tshirt_count * 50 : 0,
            'notes'       => $validated['notes'] ?? $uniformRequest->notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        if ($validated['status'] === 'approved' && $uniformRequest->type === 'request') {
            Uniform::updateOrCreate(
                ['employee_id' => $uniformRequest->employee_id],
                [
                    'tshirt_count'  => $uniformRequest->tshirt_count,
                    'hat_count'     => $uniformRequest->hat_count,
                    'id_card_count' => $uniformRequest->id_card_count,
                    'tool_bag_count'=> $uniformRequest->tool_bag_count,
                    'received_at'   => now()->toDateString(),
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'تم تحديث حالة الطلب']);
    }
}
