<?php

namespace AwStudio\FjordPermissions\Controllers;

use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use AwStudio\FjordPermissions\Models\RolePermission;
use AwStudio\FjordPermissions\Requests\UpdateRolePermissionRequest;
use AwStudio\FjordPermissions\Requests\IndexRolePermissionRequest;
use AwStudio\Fjord\Support\IndexTable;
use AwStudio\Fjord\Support\Facades\Package;
use AwStudio\Fjord\Fjord\Models\FjordUser;

class PermissionController extends Controller
{
    public function assignRoleToUser(Request $request, $user_id)
    {
        $user = FjordUser::findOrFail($user_id);

        $newRole = Role::findOrFail($request->role_id);

        // Fjord User can only have one role.
        foreach ($user->roles as $oldRole) {
            $user->removeRole($oldRole);
        }

        $user->assignRole($newRole);
    }

    public function index(IndexRolePermissionRequest $request)
    {
        $config = [
            'cols' => [
                [
                    'key' => '{name}',
                    'label' => 'Name'
                ],
                [
                    'key' => '{email}',
                    'label' => 'E-Mail'
                ],
            ],
            'recordActions' => [],
            'globalActions' => [''],
            'sortBy' => [
                'id.desc' => 'New -> Old',
                'id.asc' => 'Old -> New'
            ],
            'sortByDefault' => 'id.desc',
        ];

        return view('fjord::app')->withComponent('fjord-permissions')
            ->withTitle('Permissions')
            ->withProps([
                'cols' => $this->getCols(),
                'roles' => Role::all(),
                'operations' => $this->getUniqueOperations(),
                'role_permissions' => RolePermission::all(),
                'config' => $config
            ]);
    }

    protected function getUniqueOperations()
    {
        $names = Permission::select('name')->pluck('name');
        return $names->map(function ($name) {
            return explode(' ', $name)[0];
        })->unique();
    }

    protected function getCols()
    {
        $cols = [
            [
                'key' => '{name}',
                'label' => 'Name',
                'component' => 'fjord-permissions-show-name'
            ]
        ];

        foreach ($this->getUniqueOperations() as $operation) {
            $cols[] = [
                'key' => $operation,
                'label' => ucfirst(__f("fj.operations.{$operation}")),
                'component' => 'fjord-permissions-toggle',
            ];
        }

        $cols[] = [
            'key' => '',
            'label' => ucfirst(__f('fj.toggle_all')),
            'component' => 'fjord-permissions-toggle-all',
        ];

        return $cols;
    }

    public function fetchIndex(Request $request)
    {
        $data = with(new IndexTable(Permission::query(), $request))->except(['paginate'])->items();

        $data['unique_items'] = $data['items']->unique(function ($item) {
            $name = str_replace($this->getUniqueOperations()->toArray(), '', $item->name);
            return Str::replaceFirst(' ', '', $name);
        })->filter(function ($item) {
            // Dont show role-permissions.
            return !Str::endsWith($item->name, 'role-permissions');
        });
        $data['count'] = $data['unique_items']->count();

        return $data;
    }

    public function update(UpdateRolePermissionRequest $request)
    {
        $role = Role::findOrFail($request->role['id']);

        if ($role->hasPermissionTo($request->permission)) {
            $role->revokePermissionTo($request->permission);
        } else {
            $role->givePermissionTo($request->permission);
        }

        \Cache::forget('spatie.permission.cache');

        return RolePermission::all();
    }
}
