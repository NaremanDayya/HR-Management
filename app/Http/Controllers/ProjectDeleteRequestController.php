<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectDeleteRequest;
use App\Models\User;
use App\Notifications\ProjectDeleteRequestStatusNotification;
use App\Notifications\ProjectDeleteRequestSubmittedNotification;
use App\Scopes\YearScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ProjectDeleteRequestController extends Controller
{
    public function store(Request $request, Project $project)
    {
        abort_unless(in_array(Auth::user()->role, ['admin', 'hr_manager', 'hr_assistant']), 403, 'ليس لديك صلاحية طلب حذف مشروع.');
        abort_if($project->is_stopped, 422, 'هذا المشروع متوقف بالفعل.');

        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $existingPending = ProjectDeleteRequest::where('project_id', $project->id)
            ->where('status', 'pending')
            ->exists();
        abort_if($existingPending, 422, 'يوجد طلب حذف قيد المراجعة لهذا المشروع بالفعل.');

        $deleteRequest = ProjectDeleteRequest::create([
            'project_id' => $project->id,
            'requested_by' => Auth::id(),
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        $admins = User::where('role', 'admin')->get();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new ProjectDeleteRequestSubmittedNotification($deleteRequest));
        }

        return back()->with('success', 'تم إرسال طلب حذف المشروع بانتظار موافقة الأدمن.');
    }

    public function index(Request $request)
    {
        $this->authorizeReview();

        $query = ProjectDeleteRequest::with(['project', 'requester', 'reviewer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15)->withQueryString();

        $counts = ProjectDeleteRequest::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('ProjectDeleteRequests.table', [
            'requests' => $requests,
            'pendingCount' => $counts['pending'] ?? 0,
            'approvedCount' => $counts['approved'] ?? 0,
            'rejectedCount' => $counts['rejected'] ?? 0,
            'allCount' => $counts->sum(),
        ]);
    }

    public function approve(ProjectDeleteRequest $projectDeleteRequest)
    {
        $this->authorizeReview();
        abort_unless($projectDeleteRequest->status === 'pending', 422, 'تم البت في هذا الطلب مسبقًا.');

        DB::transaction(function () use ($projectDeleteRequest) {
            Project::withoutGlobalScope(YearScope::class)->whereKey($projectDeleteRequest->project_id)->update([
                'status' => 'stopped',
                'stop_reason' => $projectDeleteRequest->reason,
                'stopped_at' => now(),
            ]);

            $projectDeleteRequest->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        });

        $this->notifyRequester($projectDeleteRequest);

        return back()->with('success', 'تمت الموافقة على الطلب وتم إيقاف المشروع وأرشفته.');
    }

    public function reject(Request $request, ProjectDeleteRequest $projectDeleteRequest)
    {
        $this->authorizeReview();
        abort_unless($projectDeleteRequest->status === 'pending', 422, 'تم البت في هذا الطلب مسبقًا.');

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $projectDeleteRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->notifyRequester($projectDeleteRequest);

        return back()->with('success', 'تم رفض الطلب بنجاح.');
    }

    private function authorizeReview(): void
    {
        abort_unless(Auth::user()->role === 'admin', 403, 'مراجعة طلبات حذف المشاريع متاحة للأدمن فقط.');
    }

    private function notifyRequester(ProjectDeleteRequest $projectDeleteRequest): void
    {
        $requester = $projectDeleteRequest->requester;

        if ($requester) {
            $requester->notify(new ProjectDeleteRequestStatusNotification($projectDeleteRequest));
        }
    }
}
