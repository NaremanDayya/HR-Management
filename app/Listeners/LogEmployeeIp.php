<?php

namespace App\Listeners;

use App\Models\Employee;
use App\Models\EmployeeLoginIp;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\User;
use App\Notifications\BlockedIpLoginAttempt;
use Illuminate\Support\Facades\Session;

class LogEmployeeIp
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        dd(Session::all());

        if (Session::has('employee_id')) {
            return;
        }
        $employee = $event->user->employee;

        if (! $employee instanceof Employee) {
            return;
        }

        $currentIp = Request::ip();

        $mainIp = EmployeeLoginIp::where('employee_id', $employee->id)
            ->where('is_allowed', true)
            ->where('is_temporary', false)
            ->first();

        if (! $mainIp) {
            EmployeeLoginIp::create([
                'employee_id' => $employee->id,
                'ip_address' => $currentIp,
                'is_allowed' => true,
                'is_temporary' => false,
            ]);
            return;
        }

        if ($mainIp->ip_address === $currentIp) {
            return;
        }

        $temporary = EmployeeLoginIp::where('employee_id', $employee->id)
            ->where('ip_address', $currentIp)
            ->where('is_allowed', true)
            ->where('is_temporary', true)
            ->where(function ($q) {
                $q->whereNull('allowed_until')
                    ->orWhere('allowed_until', '>=', now());
            })
            ->first();

        if ($temporary) {
            return;
        }
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new BlockedIpLoginAttempt($employee, $currentIp));
        }

        Auth::logout();
        abort(403, 'هذا الجهاز غير مصرح له بتسجيل الدخول إلى النظام.');
    }
}
