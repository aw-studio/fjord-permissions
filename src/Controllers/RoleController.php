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
    public function assignRoleToUser(UpdateRoleRequest $request, $user_id, $role_id)
    {
        $user = FjordUser::findOrFail($user_id);

        $role = Role::findOrFail($role_id);

        $user->assignRole($role);
    }

    /**
     * Remove role to from fjord-user.
     *
     * @param Request $request
     * @param FjordUser $user_id
     * @return void
     */
    public function removeRoleFromUser(UpdateRoleRequest $request, $user_id, $role_id)
    {
        $user = FjordUser::findOrFail($user_id);

        $role = $user->roles()->findOrFail($role_id);

        // Can't take away own admin role.
        if ($role->name == 'admin' && $user->id == fjord_user()->id) {
            return response()->json(['message' => __f('fjpermissions.cant_remove_admin_role'), 'variant' => 'danger', 405]);
        }

        // Remove role.
        $user->removeRole($role);

        // Apply user role if user has no roles.
        if ($user->roles()->count() == 0) {
            $user->assignRole(Role::where('name', 'user')->first());
        }
    }

    /**
     * Create new role.
     *
     * @param CreateRoleRequest $request
     * @return Role
     */
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
            if ($user->roles()->count() > 1) {
                continue;
            }

            $user->assignRole('user');
        }

        $role->delete();
    }
}
