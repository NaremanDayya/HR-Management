<?php

namespace App\Http\Controllers;

use App\Models\BankUpdateRequest;
use App\Models\Employee;
use App\Models\User;
use App\Notifications\BankUpdateRequestStatusNotification;
use App\Notifications\BankUpdateRequestSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class BankUpdateRequestController extends Controller
{
    public function showPublicForm(Employee $employee)
    {
        return view('Employees.bank-update-request-form', ['employee' => $employee]);
    }

    public function storePublicForm(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'account_status' => 'required|in:active,inactive',
            'id_card_number' => 'required|string|max:50',
            'mobile_number' => 'required|string|max:30',
            'city' => 'required|string|max:255',
            'new_iban' => 'required|string|max:34',
            'new_bank_name' => 'required|string|max:255',
            'new_owner_account_name' => 'required|string|max:255',
            'id_card_images' => 'required|array|min:1|max:5',
            'id_card_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'notes' => 'nullable|string|max:1000',
        ]);

        $idCardPaths = collect($request->file('id_card_images'))
            ->map(fn ($file) => $file->store('bank_update_requests/id_cards', 'public'))
            ->all();

        $bankUpdateRequest = BankUpdateRequest::create([
            'employee_id' => $employee->id,
            'full_name' => $validated['full_name'],
            'account_status' => $validated['account_status'],
            'id_card_number' => $validated['id_card_number'],
            'mobile_number' => $validated['mobile_number'],
            'city' => $validated['city'],
            'current_iban' => $employee->iban,
            'current_bank_name' => $employee->bank_name,
            'current_owner_account_name' => $employee->owner_account_name,
            'new_iban' => $validated['new_iban'],
            'new_bank_name' => $validated['new_bank_name'],
            'new_owner_account_name' => $validated['new_owner_account_name'],
            'id_card_images' => $idCardPaths,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        $recipients = User::whereIn('role', ['admin', 'hr_manager', 'hr_assistant'])->get();
        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new BankUpdateRequestSubmittedNotification($bankUpdateRequest));
        }

        return view('Employees.bank-update-request-success', ['employee' => $employee]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $managedProjectIds = null;

        if ($user->role === 'project_manager') {
            $managedProjectIds = $user->employee?->managedProjects?->pluck('id') ?? collect();
        }

        $scopeToManagedProjects = function ($query) use ($managedProjectIds) {
            if ($managedProjectIds !== null) {
                $query->whereHas('employee', function ($q) use ($managedProjectIds) {
                    $q->whereIn('project_id', $managedProjectIds);
                });
            }
        };

        $query = BankUpdateRequest::with(['employee.project', 'reviewer'])->latest();
        $scopeToManagedProjects($query);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        $countsQuery = BankUpdateRequest::when($request->filled('employee_id'), function ($q) use ($request) {
            $q->where('employee_id', $request->employee_id);
        });
        $scopeToManagedProjects($countsQuery);
        $counts = $countsQuery->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $employee = $request->filled('employee_id') ? Employee::find($request->employee_id) : null;

        return view('BankUpdateRequests.table', [
            'requests' => $requests,
            'pendingCount' => $counts['pending'] ?? 0,
            'approvedCount' => $counts['approved'] ?? 0,
            'rejectedCount' => $counts['rejected'] ?? 0,
            'allCount' => $counts->sum(),
            'filterEmployee' => $employee,
        ]);
    }

    public function approve(BankUpdateRequest $bankUpdateRequest)
    {
        $this->authorizeReview();
        abort_unless($bankUpdateRequest->status === 'pending', 422, 'تم البت في هذا الطلب مسبقًا.');

        DB::transaction(function () use ($bankUpdateRequest) {
            $bankUpdateRequest->employee->update([
                'iban' => $bankUpdateRequest->new_iban,
                'bank_name' => $bankUpdateRequest->new_bank_name,
                'owner_account_name' => $bankUpdateRequest->new_owner_account_name,
            ]);

            $bankUpdateRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        });

        $this->notifyEmployee($bankUpdateRequest);

        return back()->with('success', 'تمت الموافقة على الطلب وتحديث البيانات البنكية بنجاح.');
    }

    public function reject(Request $request, BankUpdateRequest $bankUpdateRequest)
    {
        $this->authorizeReview();
        abort_unless($bankUpdateRequest->status === 'pending', 422, 'تم البت في هذا الطلب مسبقًا.');

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $bankUpdateRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->notifyEmployee($bankUpdateRequest);

        return back()->with('success', 'تم رفض الطلب بنجاح.');
    }

    private function authorizeReview(): void
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'hr_manager', 'hr_assistant']), 403, 'غير مصرح لك بمراجعة هذا الطلب.');
    }

    private function notifyEmployee(BankUpdateRequest $bankUpdateRequest): void
    {
        $user = $bankUpdateRequest->employee->user;

        if ($user) {
            $user->notify(new BankUpdateRequestStatusNotification($bankUpdateRequest));
        }
    }
}
