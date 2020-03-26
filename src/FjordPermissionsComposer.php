<?php

namespace AwStudio\FjordPermissions;

use Illuminate\View\View;

class FjordPermissionsComposer
{
    public function compose(View $view)
    {
        fjord()->app()->addProp('permissions', $this->getPermissions());
    }

    protected function getPermissions()
    {
        $permissions = collect([]);
        foreach(auth()->user()->roles ?? [] as $role) {
            $permissions = $permissions->merge(
                $role->permissions->pluck('name')
            );
        }
        return $permissions->unique();
    }
}
