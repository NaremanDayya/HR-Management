<?php

namespace App\Services;

use App\Models\Advance;
use App\Models\AdvancePayment;
use App\Models\Employee;
use Carbon\Carbon;

/**
 * Works out how much salary-advance an employee can be given against the work
 * he has already done ("work-day balance"), so an advance larger than that
 * balance can be flagged before it is requested.
 *
 *   available = earned pay this month (worked days x daily rate, minus absence days)
 *             + rewards approved this month
 *             - deductions this month
 *             - advances still owed (approved & unpaid, plus pending requests)
 *
 * The daily rate matches the financials page: monthly salary / calendar days in
 * the month (Fridays included).
 */
class AdvanceBalanceService
{
    public function forEmployee(Employee $employee, ?Carbon $asOf = null): array
    {
        $today = ($asOf ?? now())->copy()->startOfDay();
        $monthStart = $today->copy()->startOfMonth();
        $daysInMonth = $today->daysInMonth;

        $salary = (float) ($employee->salary ?? 0);
        $dailyRate = $daysInMonth > 0 ? $salary / $daysInMonth : 0.0;

        // Count from the 1st of the month, or from the joining date if he joined this month.
        $periodStart = $employee->joining_date && $employee->joining_date->copy()->startOfDay()->gt($monthStart)
            ? $employee->joining_date->copy()->startOfDay()
            : $monthStart;

        $elapsedDays = $periodStart->gt($today) ? 0 : (int) $periodStart->diffInDays($today) + 1;
        $workedDays = max(0, $elapsedDays - (int) ($employee->absence_days ?? 0));

        $earned = round($dailyRate * $workedDays, 2);

        $rewards = (float) $employee->increases()
            ->where('is_reward', true)
            ->whereMonth('effective_date', $today->month)
            ->whereYear('effective_date', $today->year)
            ->sum('increase_amount');

        $deductions = (float) $employee->deductions()
            ->whereMonth('created_at', $today->month)
            ->whereYear('created_at', $today->year)
            ->sum('value');

        $outstanding = $this->outstandingAdvances($employee);

        $available = max(0, round($earned + $rewards - $deductions - $outstanding, 2));

        return [
            'salary' => round($salary, 2),
            'daily_rate' => round($dailyRate, 2),
            'worked_days' => $workedDays,
            'earned' => $earned,
            'rewards' => round($rewards, 2),
            'deductions' => round($deductions, 2),
            'outstanding' => round($outstanding, 2),
            'available' => $available,
        ];
    }

    public function exceeds(float $requestedAmount, array $balance): bool
    {
        return $requestedAmount > $balance['available'] + 0.005;
    }

    /**
     * Money the employee still owes on advances: the unpaid part of approved
     * advances plus the full amount of requests still waiting for a decision.
     * Year scopes are bypassed on purpose — an advance taken last year is still a debt.
     */
    private function outstandingAdvances(Employee $employee): float
    {
        $total = 0.0;

        $advances = Advance::withoutGlobalScopes()
            ->where('employee_id', $employee->id)
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        foreach ($advances as $advance) {
            if ($advance->status === 'pending') {
                $total += (float) $advance->amount;
                continue;
            }

            if ($advance->is_fully_paid) {
                continue;
            }

            $payments = AdvancePayment::withoutGlobalScopes()->where('advance_id', $advance->id);

            if ((clone $payments)->exists()) {
                $total += (float) (clone $payments)->where('status', 'pending')->sum('amount');
            } else {
                $total += (float) $advance->monthly_deduction * max(0, (int) $advance->months_remaining);
            }
        }

        return $total;
    }
}
