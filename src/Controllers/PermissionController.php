<?php

namespace FjordPermissions\Controllers;

use Fjord\Support\IndexTable;
use Fjord\Vue\Table;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use FjordPermissions\Models\RolePermission;
use FjordPermissions\Requests\RolePermission\ReadRolePermissionRequest;

class PermissionController extends Controller
{
    public function index(ReadRolePermissionRequest $request)
    {
        $config = [
            'recordActions' => [],
            'globalActions' => [''],
            'sortBy' => [
                'id.desc' => __f('fj.sort_new_to_old'),
                'id.asc' => __f('fj.sort_old_to_new'),
            ],
            'sortByDefault' => 'id.desc',
            'search' => ['name'],
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
        $index = new Table;

        $index->component('fj-permissions-index-group-name')
            ->label('Group')
            ->sortBy('permission_group');

        foreach ($this->getUniqueOperations() as $operation) {
            $index->component('fj-permissions-toggle')
                ->prop('operation', $operation)
                ->label(__f("fj.operations.{$operation}"));
        }

        $index->component('fj-permissions-toggle-all')
            ->label(ucfirst(__f('fj.toggle_all')));

        return $index;
    }

    public function fetchIndex(ReadRolePermissionRequest $request)
    {
        $query = Permission::select([
            '*',
            DB::raw("SUBSTRING_INDEX(name, ' ', 1) AS operation"),
            DB::raw("SUBSTRING_INDEX(name, ' ', -1) AS permission_group"),
        ])->whereRaw("SUBSTRING_INDEX(name, ' ', -1) != 'fjord-role-permissions'");

        $data = with(new IndexTable($query, $request))->except(['paginate'])->items();

        $data['unique_items'] = $data['items']->unique('permission_group');

        $data['count'] = $data['unique_items']->count();

        return $data;
    }
}
