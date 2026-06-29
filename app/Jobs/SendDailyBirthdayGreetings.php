<?php
namespace App\Jobs;

use App\Models\User;
use App\Notifications\BirthdayGreeting;
use App\Notifications\EmployeeBirthdayNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class SendDailyBirthdayGreetings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = Carbon::today();

        $users = User::whereMonth('birthday', $today->month)
            ->whereDay('birthday', $today->day)
            ->with('employee.project.manager')
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        // Recipients who should be informed about every employee's birthday,
        // regardless of which project the employee belongs to.
        $broadAudience = User::whereIn('role', ['admin', 'hr_manager', 'hr_assistant', 'operations_manager'])->get();

        foreach ($users as $user) {
            Notification::send($user, new BirthdayGreeting());

            $recipients = $broadAudience->keyBy('id');

            $projectManager = $user->employee?->project?->manager;
            if ($projectManager) {
                $recipients->put($projectManager->id, $projectManager);
            }

            // Don't notify the birthday person about their own birthday twice
            // (they already received the BirthdayGreeting above).
            $recipients->forget($user->id);

            if ($recipients->isNotEmpty()) {
                Notification::send($recipients->values(), new EmployeeBirthdayNotification($user));
            }
        }
    }
}
