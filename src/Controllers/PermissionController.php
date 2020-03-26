<?php

namespace AwStudio\FjordPermissions\Controllers;

use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use AwStudio\FjordPermissions\Models\RolePermission;
use AwStudio\FjordPermissions\Requests\UpdateRolePermissionRequest;
use AwStudio\FjordPermissions\Requests\IndexRolePermissionRequest;

class PermissionController extends Controller
{
    public function index(IndexRolePermissionRequest $request)
    {
        return view('fjord::app')->withComponent('fjord-permissions')
            ->withTitle('Permissions')
            ->withProps([
                'roles' => Role::all(),
                'permissions' => Permission::all(),
                'role_permissions' => RolePermission::all(),
            ]);
    }

    public function update(UpdateRolePermissionRequest $request)
    {
        $role = Role::findOrFail($request->role['id']);
        $permission = $request->permission['name'];

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }

        \Cache::forget('spatie.permission.cache');
    }
}
