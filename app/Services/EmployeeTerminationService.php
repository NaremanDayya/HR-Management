<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Advance;
use App\Models\AdvancePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeTerminationService
{
    public function terminateEmployee(Employee $employee, array $data)
    {
        DB::beginTransaction();
        try {
            $terminationDate = Carbon::parse($data['termination_date']);
            $workDays = $data['work_days'] ?? $this->calculateWorkDays($employee, $terminationDate);
            $absenceDays = $data['absence_days'] ?? $employee->absence_days ?? 0;

            $finalSalary = $this->calculateFinalSalary($employee, $workDays, $absenceDays);

            $unpaidAdvances = $this->getUnpaidAdvanceAmount($employee);

            $netFinalSalary = $finalSalary - $unpaidAdvances;
            $outstandingDebt = 0;

            if ($netFinalSalary < 0) {
                $outstandingDebt = abs($netFinalSalary);
                $netFinalSalary = 0;
            }

            $employee->update([
                'is_terminated' => true,
                'termination_date' => $terminationDate,
                'termination_notes' => $data['notes'] ?? null,
                'outstanding_advance_debt' => $outstandingDebt,
                'work_days' => $workDays,
                'absence_days' => $absenceDays,
                'last_working_date' => $terminationDate,
            ]);

            if ($unpaidAdvances > 0) {
                $this->settleAdvances($employee, $finalSalary);
            }

            DB::commit();

            return [
                'success' => true,
                'final_salary' => $finalSalary,
                'unpaid_advances' => $unpaidAdvances,
                'net_final_salary' => $netFinalSalary,
                'outstanding_debt' => $outstandingDebt,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function calculateWorkDays(Employee $employee, Carbon $terminationDate)
    {
        $joiningDate = Carbon::parse($employee->joining_date);
        return $joiningDate->diffInDays($terminationDate);
    }

    private function calculateFinalSalary(Employee $employee, int $workDays, int $absenceDays)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $daysInMonth = now()->daysInMonth;

        $dailyRate = $employee->salary / 26;

        $workedDaysThisMonth = min($workDays, $daysInMonth);
        $netPayableDays = $workedDaysThisMonth - $absenceDays;

        $baseSalary = ($netPayableDays / 26) * $employee->salary;

        $currentMonthIncreases = $employee->increases()
            ->where('is_reward', true)
            ->whereMonth('effective_date', $currentMonth)
            ->whereYear('effective_date', $currentYear)
            ->sum('increase_amount');

        $currentMonthDeductions = $employee->deductions()
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('value');

        return $baseSalary + $currentMonthIncreases - $currentMonthDeductions;
    }

    private function getUnpaidAdvanceAmount(Employee $employee)
    {
        return $employee->advancePayments()
            ->where('status', 'pending')
            ->sum('amount');
    }

    private function settleAdvances(Employee $employee, float $finalSalary)
    {
        $pendingPayments = $employee->advancePayments()
            ->where('status', 'pending')
            ->orderBy('scheduled_date')
            ->get();

        $remainingAmount = $finalSalary;

        foreach ($pendingPayments as $payment) {
            if ($remainingAmount >= $payment->amount) {
                $payment->update(['status' => 'paid']);
                $remainingAmount -= $payment->amount;

                $advance = $payment->advance;
                $advance->decrement('months_remaining');

                $remainingPendingPayments = $advance->payments()
                    ->where('status', 'pending')
                    ->count();

                if ($remainingPendingPayments === 0) {
                    $advance->update([
                        'is_fully_paid' => true,
                        'months_remaining' => 0,
                    ]);
                }
            } else {
                break;
            }
        }
    }

    public function getTerminationSummary(Employee $employee)
    {
        $workDays = $this->calculateWorkDays($employee, now());
        $absenceDays = $employee->absence_days ?? 0;
        $finalSalary = $this->calculateFinalSalary($employee, $workDays, $absenceDays);
        $unpaidAdvances = $this->getUnpaidAdvanceAmount($employee);

        return [
            'work_days' => $workDays,
            'absence_days' => $absenceDays,
            'final_salary' => $finalSalary,
            'unpaid_advances' => $unpaidAdvances,
            'net_final_salary' => max(0, $finalSalary - $unpaidAdvances),
            'outstanding_debt' => max(0, $unpaidAdvances - $finalSalary),
        ];
    }
}
