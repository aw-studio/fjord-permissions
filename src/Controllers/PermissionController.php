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
use AwStudio\Fjord\Fjord\Application\IndexTable;

class PermissionController extends Controller
{
    public function index(IndexRolePermissionRequest $request)
    {
        $config = fjord()
            ->package('aw-studio/fjord-permissions')
            ->config('table');

        return view('fjord::app')->withComponent('fjord-permissions')
            ->withTitle('Permissions')
            ->withProps([
                'cols' => $this->getCols(),
                'roles' => Role::all(),
                'operations' => $this->getUniqueOperations(),
                //'permissions' => Permission::all(),
                'role_permissions' => RolePermission::all(),
                'config' => $config
            ]);
    }

    protected function getUniqueOperations()
    {
        $names = Permission::select('name')->pluck('name');
        return $names->map(function($name) {
            return explode(' ', $name)[0];
        })->unique();
    }

    protected function getCols()
    {
        $cols = [
            [
                'key' => '{name}',
                'label' => 'Name'
            ]
        ];

        foreach($this->getUniqueOperations() as $operation) {
            $cols []= [
                'key' => $operation,
                'label' => ucfirst($operation),
                'component' => 'fjord-permissions-toggle',
            ];
        }

        $cols []= [
            'key' => '',
            'label' => 'Toggle All',
            'component' => 'fjord-permissions-toggle-all',
        ];

        return $cols;
    }

    public function fetchIndex(Request $request)
    {
        $data = IndexTable::get(Permission::query(), $request);


        $data['unique_items'] = $data['items']->unique(function($item) {
            $name = str_replace($this->getUniqueOperations()->toArray(), '',$item->name);
            return Str::replaceFirst(' ', '', $name);
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
