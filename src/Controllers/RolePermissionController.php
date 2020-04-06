<?php

namespace FjordPermissions\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;
use FjordPermissions\Models\RolePermission;
use FjordPermissions\Requests\RolePermission\UpdateRolePermissionRequest;

class RolePermissionController
{
    public function update(UpdateRolePermissionRequest $request)
    {
        $role = Role::findOrFail($request->role['id']);

        if ($role->hasPermissionTo($request->permission)) {
            $role->revokePermissionTo($request->permission);
        } else {
            $role->givePermissionTo($request->permission);
        }

        Cache::forget('spatie.permission.cache');

        return RolePermission::all();
    }
}
