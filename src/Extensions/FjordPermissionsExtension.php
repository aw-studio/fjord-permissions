<?php

namespace AwStudio\FjordPermissions\Extensions;

use AwStudio\Fjord\Fjord\Application\Application;

class FjordPermissionsExtension
{
    /**
     * Define the packages and routes that should be extended
     *
     * @var array
     */
    const FOR = [
        '*' => '*'
    ];

    /**
     * Extend the fjord application.
     *
     * @param AwStudio\Fjord\Fjord\Application\Application $app
     */
    public function extend(Application $app)
    {
        $app->prop('permissions', $this->getPermissions());
    }

    /**
     *
     *
     * @return
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
