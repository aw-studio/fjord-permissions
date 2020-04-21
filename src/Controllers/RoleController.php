<?php

namespace FjordPermissions\Controllers;

use Illuminate\Http\Request;
use Fjord\User\Models\FjordUser;
use Spatie\Permission\Models\Role;
use FjordPermissions\Requests\Role\CreateRoleRequest;
use FjordPermissions\Requests\Role\DeleteRoleRequest;
use FjordPermissions\Requests\Role\UpdateRoleRequest;

class RoleController
{
    /**
     * Assign role to fjord-user.
     *
     * @param Request $request
     * @param FjordUser $user_id
     * @return void
     */
    public function assignRoleToUser(UpdateRoleRequest $request, $user_id)
    {
        $user = FjordUser::findOrFail($user_id);

        $newRole = Role::findOrFail($request->role_id);

        // Fjord User can only have one role.
        foreach ($user->roles as $oldRole) {
            $user->removeRole($oldRole);
        }

        $user->assignRole($newRole);
    }

    public function store(CreateRoleRequest $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->save();

        return $role;
    }

    /**
     * Delete role.
     *
     * @param DeleteRoleRequest $request
     * @param int $id
     * @return void
     */
    public function destroy(DeleteRoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        // Roles admin & user cannot be deletet.
        if (in_array($role->name, ['admin', 'user'])) {
            $roleName = __f("roles.{$role->name}") !== "roles.{$role->name}"
                ? __f("roles.{$role->name}")
                : ucfirst($role->name);
            abort(422, __f('fjpermissions.cant_delete_role', ['role' => $roleName]));
        }

        // FjordUsers with the role to be deleted are assigned the role user.
        foreach ($role->users as $user) {
            $user->assignRole('user');
        }

        $role->delete();
    }
}
