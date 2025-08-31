<?php

namespace App\Console\Commands;

use App\Models\SalaryIncrease;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ApplySalaryIncreases extends Command
{
    protected $signature = 'app:apply-salary-increases';
    protected $description = 'Apply pending salary increases at the beginning of each month';

    public function handle()
    {
        $today = now()->startOfDay();

        // Only get static increases
        $staticIncreases = SalaryIncrease::where('is_applied', false)
            ->where('is_reward', 0)
            ->whereDate('effective_date', '<=', $today)
            ->where('status', 'approved')
            ->get();

        $appliedCount = 0;

        foreach ($staticIncreases as $increase) {
            try {
                $employee = $increase->employee;

                // Apply salary update
                $employee->update([
                    'salary' => $increase->new_salary
                ]);

                $increase->update(['is_applied' => true]);
                $appliedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to apply salary increase", [
                    'increase_id' => $increase->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("Successfully applied {$appliedCount} static salary increases.");
    }
}
