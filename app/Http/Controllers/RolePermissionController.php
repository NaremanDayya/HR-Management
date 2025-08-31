<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function edit(Role $role)
    {
        $permissions = Permission::all();

        return view('admin.roles.edit-permissions', compact('role', 'permissions'));
    }


public function update(Request $request,Role $role)
{
    dd($request);
  
    
    $validated = $request->validate([
        'permissions' => 'sometimes|array',
        'permissions.*' => 'string|exists:permissions,name'
    ]);

    try {
        $role->syncPermissions($validated['permissions'] ?? []);
        
        return back()->with('success', 'تم تحديث الصلاحيات بنجاح');
    } catch (\Exception $e) {
        return back()->with('error', 'فشل تحديث الصلاحيات: ' . $e->getMessage());
    }
}
}
