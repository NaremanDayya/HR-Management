<?php
namespace App\Jobs;

use App\Models\User;
use App\Notifications\BirthdayGreeting;
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
            ->get();

        foreach ($users as $user) {
                        Notification::send($user, new BirthdayGreeting());

        }
    }
}
