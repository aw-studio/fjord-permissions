<?php

namespace AwStudio\FjordPermissions\Composer;

use Illuminate\View\View;
use AwStudio\Fjord\Fjord\Application\Application;

class PermissionsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('permissions', $this->getPermissions());
    }

    /**
     * Get unique permissions for authenticated user.
     *
     * @return array $permissions
     */
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
