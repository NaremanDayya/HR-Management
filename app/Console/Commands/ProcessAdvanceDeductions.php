<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Advance;
use App\Models\AdvanceDeduction;
use Carbon\Carbon;

class ProcessAdvanceDeductions extends Command
{
    protected $signature = 'advances:process-deductions';
    protected $description = 'خصم السلف من الرواتب الشهرية للموظفين';

    public function handle()
    {
        $today = Carbon::today();
        $deductionsCount = 0;

        $advances = Advance::where('status', 'approved')
            ->where('is_fully_paid', false)
            ->whereDate('start_deduction_at', '<=', $today)
            ->get();

        foreach ($advances as $advance) {
            $alreadyDeducted = AdvanceDeduction::where('advance_id', $advance->id)
                ->whereMonth('deducted_at', $today->month)
                ->whereYear('deducted_at', $today->year)
                ->exists();

            if ($alreadyDeducted || $advance->months_remaining <= 0) {
                continue;
            }

            AdvanceDeduction::create([
                'advance_id' => $advance->id,
                'employee_id' => $advance->employee_id,
                'amount' => $advance->monthly_deduction,
                'deducted_at' => $today,
            ]);

            $advance->months_remaining -= 1;

            if ($advance->months_remaining <= 0) {
                $advance->is_fully_paid = true;
            }

            $advance->save();

            $deductionsCount++;
        }

        $this->info("تم تنفيذ $deductionsCount عملية خصم بنجاح.");
    }
}
