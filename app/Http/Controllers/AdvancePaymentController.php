<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\AdvancePayment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdvancePaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = AdvancePayment::with(['advance', 'employee'])->latest();

        if (Auth::user()->role === 'project_manager') {
            $managedProjectIds = Auth::user()->employee->managedProjects->pluck('id');
            $query->whereHas('employee', function ($q) use ($managedProjectIds) {
                $q->whereIn('project_id', $managedProjectIds);
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('advance_id')) {
            $query->where('advance_id', $request->advance_id);
        }

        $payments = $query->paginate(20);

        return view('advances.payments.index', compact('payments'));
    }

    public function show(Advance $advance)
    {
        $advance->load(['payments' => function ($query) {
            $query->orderBy('payment_number');
        }, 'employee']);

        return view('advances.payments.show', compact('advance'));
    }

    public function postpone(Request $request, AdvancePayment $payment)
    {
        $validated = $request->validate([
            'postpone_type' => 'required|in:extra_month,new_payment,new_advance',
            'new_date' => 'required_if:postpone_type,extra_month,new_payment|date|after:today',
            'reason' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            switch ($validated['postpone_type']) {
                case 'extra_month':
                    $this->postponeToExtraMonth($payment, $validated);
                    break;
                case 'new_payment':
                    $this->addNewPaymentAfterLast($payment, $validated);
                    break;
                case 'new_advance':
                    $this->createNewAdvance($payment, $validated);
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تأجيل الدفعة بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تأجيل الدفعة: ' . $e->getMessage()
            ], 500);
        }
    }

    private function postponeToExtraMonth(AdvancePayment $payment, array $validated)
    {
        $payment->update([
            'original_scheduled_date' => $payment->original_scheduled_date ?? $payment->scheduled_date,
            'scheduled_date' => $validated['new_date'],
            'status' => 'postponed',
            'postpone_reason' => $validated['reason'] ?? null,
        ]);

        $advance = $payment->advance;
        $advance->increment('months_to_repay');
        $advance->increment('months_remaining');
    }

    private function addNewPaymentAfterLast(AdvancePayment $payment, array $validated)
    {
        $advance = $payment->advance;
        $lastPayment = $advance->payments()->orderBy('payment_number', 'desc')->first();

        AdvancePayment::create([
            'advance_id' => $advance->id,
            'employee_id' => $advance->employee_id,
            'amount' => $payment->amount,
            'scheduled_date' => $validated['new_date'],
            'status' => 'pending',
            'payment_number' => $lastPayment->payment_number + 1,
            'created_from_payment_id' => $payment->id,
        ]);

        $payment->update([
            'status' => 'postponed',
            'postpone_reason' => $validated['reason'] ?? 'تم إنشاء دفعة جديدة',
        ]);

        $advance->increment('months_to_repay');
        $advance->increment('months_remaining');
    }

    private function createNewAdvance(AdvancePayment $payment, array $validated)
    {
        $advance = $payment->advance;
        $employee = $advance->employee;

        $remainingPayments = $advance->payments()
            ->where('status', 'pending')
            ->where('id', '>=', $payment->id)
            ->get();

        $totalRemainingAmount = $remainingPayments->sum('amount');
        $remainingMonths = $remainingPayments->count();

        $newAdvance = Advance::create([
            'employee_id' => $employee->id,
            'manager_id' => Auth::id(),
            'amount' => $totalRemainingAmount,
            'percentage' => $employee->salary > 0 ? round(($totalRemainingAmount / $employee->salary) * 100, 2) : 0,
            'salary' => $employee->salary,
            'status' => 'approved',
            'requested_at' => now(),
            'approved_at' => now(),
            'notes' => 'تم إنشاؤها من تأجيل سلفة رقم ' . $advance->id,
            'monthly_deduction' => round($totalRemainingAmount / $remainingMonths, 2),
            'months_to_repay' => $remainingMonths,
            'months_remaining' => $remainingMonths,
            'start_deduction_at' => $validated['new_date'],
            'is_fully_paid' => false,
        ]);

        $paymentNumber = 1;
        foreach ($remainingPayments as $oldPayment) {
            $newDate = Carbon::parse($validated['new_date'])->addMonths($paymentNumber - 1);
            
            AdvancePayment::create([
                'advance_id' => $newAdvance->id,
                'employee_id' => $employee->id,
                'amount' => $oldPayment->amount,
                'scheduled_date' => $newDate,
                'status' => 'pending',
                'payment_number' => $paymentNumber,
                'created_from_payment_id' => $oldPayment->id,
            ]);

            $oldPayment->update([
                'status' => 'postponed',
                'postpone_reason' => 'تم نقلها إلى سلفة جديدة رقم ' . $newAdvance->id,
            ]);

            $paymentNumber++;
        }

        $advance->update([
            'is_fully_paid' => true,
            'months_remaining' => 0,
        ]);
    }

    public function markAsPaid(AdvancePayment $payment)
    {
        DB::beginTransaction();
        try {
            $payment->update(['status' => 'paid']);

            $advance = $payment->advance;
            $remainingPayments = $advance->payments()
                ->where('status', 'pending')
                ->count();

            if ($remainingPayments === 0) {
                $advance->update([
                    'is_fully_paid' => true,
                    'months_remaining' => 0,
                ]);
            } else {
                $advance->decrement('months_remaining');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة الدفعة بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }
}
