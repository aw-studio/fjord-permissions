<?php

namespace FjordPermissions\Composer;

use Illuminate\View\View;

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
        fjord()
            ->app()
            ->get('vue')
            ->setProp('permissions', $this->getPermissions());
    }

    /**
     * Get unique permissions for authenticated user.
     *
     * @return array $permissions
     */
    protected function getPermissions()
    {
        $permissions = collect([]);
        foreach (auth()->user()->roles ?? [] as $role) {
            $permissions = $permissions->merge(
                $role->permissions->pluck('name')
            );
        }

        return $permissions->unique();
    }
}
