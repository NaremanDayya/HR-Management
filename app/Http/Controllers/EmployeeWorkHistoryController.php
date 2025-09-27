<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeWorkHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exports\EmployeeWorkHistoryExport;
use App\Exports\EmployeeWorkHistoryPDF;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeWorkHistoryController extends Controller
{
    /**
     * Get employee work history with filters
     */
    public function getWorkHistory(Request $request, $employeeId = null)
    {
        $query = EmployeeWorkHistory::with('employee.user');

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        // Search by employee name
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('employee.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Date range filter
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->whereNull('end_date');
            } else if ($request->status === 'inactive') {
                $query->whereNotNull('end_date');
            }
        }

        $histories = $query->orderBy('start_date', 'desc')->paginate(15);

        // Get summary from the paginated results, not a new query
        $summary = $this->getSummary($histories->items());

        // Return JSON if AJAX request (for filters)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $histories->items(),
                'summary' => $summary,
                'pagination' => [
                    'current_page' => $histories->currentPage(),
                    'last_page' => $histories->lastPage(),
                    'total' => $histories->total(),
                ]
            ]);
        }

        return view('employees.histories', [
            'success' => true,
            'histories' => $histories,
            'summary' => $summary,
        ]);
    }

    private function getSummary($histories)
    {
        $totalPeriods = count($histories);
        $activePeriods = collect($histories)->where('is_active', true)->count();
        $totalWorkDays = collect($histories)->sum('work_days');
        $averagePeriodDays = $totalPeriods > 0 ? $totalWorkDays / $totalPeriods : 0;

        return [
            'total_periods' => $totalPeriods,
            'active_periods' => $activePeriods,
            'total_work_days' => $totalWorkDays,
            'average_period_days' => $averagePeriodDays,
        ];
    }


}
