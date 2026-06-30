<?php

namespace App\Listeners;

use App\Models\Employee;
use App\Models\EmployeeLoginIp;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\BlockedIpLoginAttempt;
use Illuminate\Support\Facades\Session;

class LogEmployeeIp
{
    public const DEVICE_COOKIE = 'device_token';

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

        if (Session::has('impersonator_id')) {
            return;
        }
        $employee = $event->user->employee;

        if (! $employee instanceof Employee) {
            return;
        }

        $currentIp = Request::ip();
        $currentDeviceToken = $this->resolveDeviceToken();

        $mainRecord = EmployeeLoginIp::where('employee_id', $employee->id)
            ->where('is_allowed', true)
            ->where('is_temporary', false)
            ->first();

        if (! $mainRecord) {
            EmployeeLoginIp::create([
                'employee_id' => $employee->id,
                'ip_address' => $currentIp,
                'device_token' => $currentDeviceToken,
                'is_allowed' => true,
                'is_temporary' => false,
            ]);
            return;
        }

        // Legacy rows (created before the device-token fingerprint existed) only
        // have an IP on file. Fall back to the old IP comparison once, then
        // upgrade the record to the stable device token so future network
        // changes no longer break this employee's login.
        if (! $mainRecord->device_token) {
            if ($mainRecord->ip_address === $currentIp) {
                $mainRecord->update(['device_token' => $currentDeviceToken, 'ip_address' => $currentIp]);
                return;
            }
        } elseif ($mainRecord->device_token === $currentDeviceToken) {
            if ($mainRecord->ip_address !== $currentIp) {
                $mainRecord->update(['ip_address' => $currentIp]);
            }
            return;
        }

        $temporary = EmployeeLoginIp::where('employee_id', $employee->id)
            ->where('is_allowed', true)
            ->where('is_temporary', true)
            ->where(function ($q) use ($currentIp, $currentDeviceToken) {
                $q->where('ip_address', $currentIp)
                    ->orWhere('device_token', $currentDeviceToken);
            })
            ->where(function ($q) {
                $q->whereNull('allowed_until')
                    ->orWhere('allowed_until', '>=', now());
            })
            ->first();

        if ($temporary) {
            if (! $temporary->device_token) {
                $temporary->update(['device_token' => $currentDeviceToken]);
            }
            return;
        }
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new BlockedIpLoginAttempt($employee, $currentIp));
        }

        Auth::logout();
        abort(403, 'هذا الجهاز غير مصرح له بتسجيل الدخول إلى النظام.');
    }

    /**
     * Resolve a stable per-browser identifier via a long-lived cookie.
     * Unlike Request::ip(), this does not change when the employee
     * switches WiFi/mobile networks or gets a new dynamic IP from their ISP.
     */
    private function resolveDeviceToken(): string
    {
        $token = Request::cookie(self::DEVICE_COOKIE);

        if (! $token) {
            $token = (string) Str::uuid();
            Cookie::queue(Cookie::forever(self::DEVICE_COOKIE, $token));
        }

        return $token;
    }
}
