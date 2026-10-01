<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SponsorshipController extends Controller
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

        $query = Employee::with(['user', 'project', 'leaveRequests' => fn($q) => $q->where('status', 'approved')])
            ->whereHas('user', fn($q) => $q->where('account_status', 'active'));

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('id_card', 'like', "%{$search}%"));
            });
        }

        // Project filter
        if ($projectId = $request->get('project_id')) {
            $query->where('project_id', $projectId);
        }

        // ID/Residency expiry filter
        if ($expiryFilter = $request->get('id_expiry')) {
            $today = Carbon::today();
            $query->whereNotNull('id_expiry_date');
            match ($expiryFilter) {
                'expired'  => $query->where('id_expiry_date', '<', $today),
                '1month'   => $query->whereBetween('id_expiry_date', [$today, $today->copy()->addMonth()]),
                '3months'  => $query->whereBetween('id_expiry_date', [$today, $today->copy()->addMonths(3)]),
                '6months'  => $query->whereBetween('id_expiry_date', [$today, $today->copy()->addMonths(6)]),
                default    => null,
            };
        }

        // Passport expiry filter (using passport_issue_date + 5 years as estimate)
        if ($passportFilter = $request->get('passport_expiry')) {
            $today = Carbon::today();
            $query->whereNotNull('passport_issue_date');
            $estimatedExpiry = match ($passportFilter) {
                'expired' => fn($q) => $q->where(
                    \Illuminate\Support\Facades\DB::raw("DATE_ADD(passport_issue_date, INTERVAL 5 YEAR)"), '<', $today
                ),
                '3months' => fn($q) => $q->whereRaw(
                    "DATE_ADD(passport_issue_date, INTERVAL 5 YEAR) BETWEEN ? AND ?",
                    [$today, $today->copy()->addMonths(3)]
                ),
                '6months' => fn($q) => $q->whereRaw(
                    "DATE_ADD(passport_issue_date, INTERVAL 5 YEAR) BETWEEN ? AND ?",
                    [$today, $today->copy()->addMonths(6)]
                ),
                default => fn($q) => null,
            };
            if (is_callable($estimatedExpiry)) {
                $estimatedExpiry($query);
            }
        }

        // Service years filter
        if ($serviceFilter = $request->get('service_years')) {
            $today = Carbon::today();
            match ($serviceFilter) {
                'under5'      => $query->whereNotNull('joining_date')
                                       ->where('joining_date', '>', $today->copy()->subYears(5)),
                'over5'       => $query->whereNotNull('joining_date')
                                       ->where('joining_date', '<=', $today->copy()->subYears(5)),
                'near5'       => $query->whereNotNull('joining_date')
                                       ->whereBetween('joining_date', [
                                           $today->copy()->subYears(5)->subMonths(6),
                                           $today->copy()->subYears(5)->addMonths(6),
                                       ]),
                default       => null,
            };
        }

        $employees = $query->get();
        $projects  = Project::orderBy('name')->get(['id', 'name']);

        return view('Sponsorship.index', compact('employees', 'projects'));
    }

    public function profile(Employee $employee)
    {
        $this->authorizeHR();

        $employee->load([
            'user', 'project',
            'leaveRequests' => fn($q) => $q->with('reviewer')->orderByDesc('start_date'),
        ]);

        $totalAccrued  = $employee->getTotalAccruedLeaveDays();
        $totalTaken    = $employee->getTotalLeaveDaysTaken();
        $remaining     = $employee->getLeaveDaysRemaining();
        $entitlement   = $employee->getAnnualLeaveEntitlement();
        $flightTicket  = $employee->getFlightTicketEntitlement();
        $idExpiryDays  = $employee->getIdExpiryDays();

        $serviceYears  = $employee->joining_date
            ? (int) $employee->joining_date->diffInYears(Carbon::now())
            : null;
        $serviceMonths = $employee->joining_date
            ? (int) $employee->joining_date->diffInMonths(Carbon::now()) % 12
            : null;

        return view('Sponsorship.profile', compact(
            'employee', 'totalAccrued', 'totalTaken', 'remaining',
            'entitlement', 'flightTicket', 'idExpiryDays',
            'serviceYears', 'serviceMonths'
        ));
    }
}
