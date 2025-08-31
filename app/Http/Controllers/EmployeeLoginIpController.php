<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLoginIp;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeLoginIpController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'loginIps']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $employees = $query->get();

        return view('Employees.loginIps', compact('employees'));
    }

    public function block(EmployeeLoginIp $employeeLoginIp)
    {
        $employeeLoginIp->update([
            'is_allowed' => false,
            'blocked_at' => now(),
        ]);

        return back()->with('success', 'تم حظر IP الجهاز بنجاح');
    }

    public function unblock(EmployeeLoginIp $employeeLoginIp)
    {
        $employeeLoginIp->update([
            'is_allowed' => true,
            'blocked_at' => null,
        ]);

        return back()->with('success', 'تم إلغاء حظر IP الجهاز بنجاح');
    }

    public function addTemporaryIp(Request $request, Employee $employee)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'allowed_until' => 'required|date|after:now',
        ]);

        $employee->loginIps()->create([
            'ip_address' => $request->ip_address,
            'is_allowed' => true,
            'is_temporary' => true,
            'allowed_until' => Carbon::parse($request->allowed_until),
        ]);

        return back()->with('success', 'تم إضافة IP مؤقت بنجاح');
    }
}
