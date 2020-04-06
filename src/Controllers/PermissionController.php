<?php

namespace FjordPermissions\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Fjord\Support\IndexTable;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use FjordPermissions\Models\RolePermission;
use FjordPermissions\Requests\RolePermission\ReadRolePermissionRequest;

class PermissionController extends Controller
{
    public function index(ReadRolePermissionRequest $request)
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

        return view('fjord::app')->withComponent('fj-permissions-permissions')
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
                'component' => 'fj-permissions-index-group-name'
            ]
        ];

        foreach ($this->getUniqueOperations() as $operation) {
            $cols[] = [
                'key' => $operation,
                'label' => ucfirst(__f("fj.operations.{$operation}")),
                'component' => 'fj-permissions-toggle',
            ];
        }

        $cols[] = [
            'key' => '',
            'label' => ucfirst(__f('fj.toggle_all')),
            'component' => 'fj-permissions-toggle-all',
        ];

        return $cols;
    }

    public function fetchIndex(ReadRolePermissionRequest $request)
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
}
