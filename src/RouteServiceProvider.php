<?php

namespace FjordPermissions;

use Fjord\Support\Facades\Package;
use FjordPermissions\Controllers\RoleController;
use FjordPermissions\Controllers\PermissionController;
use FjordPermissions\Controllers\RolePermissionController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as LaravelRouteServiceProvider;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    protected $package;

    public function boot()
    {
        $this->package = Package::get('aw-studio/fjord-permissions');

        parent::boot();

        $provider = $this;
        $this->app->booted(function () use ($provider) {
            $provider->addNavPresets();
        });
    }

    public function map()
    {
        $this->mapRolePermissionRoutes();
    }

    public function addNavPresets()
    {
        $this->package->addNavPreset('permissions', [
            'link' => route('fjord.aw-studio.fjord-permissions.permissions'),
            'title' => __f('fj.permissions'),
            'authorize' => function ($user) {
                return $user->can('read fjord-role-permissions');
            },
        ]);
    }

    protected function mapRolePermissionRoutes()
    {
        $route = $this->package->route()
            ->get('/permissions', PermissionController::class . '@index')
            ->name('permissions');

        $this->package->route()
            ->post('/fjord-user/{user_id}/role/{role_id}', RoleController::class . '@assignRoleToUser')
            ->name('role.assign');

        $this->package->route()
            ->delete('/fjord-user/{user_id}/role/{role_id}', RoleController::class . '@removeRoleFromUser')
            ->name('role.remove');

        $this->package->route()
            ->post('/role', RoleController::class . '@store')
            ->name('role.store');

        $this->package->route()
            ->delete('/role/{id}', RoleController::class . '@destroy')
            ->name('role.destroy');

        $this->package->route()
            ->post('/index', PermissionController::class . '@fetchIndex')
            ->name('permissions.index');

        $this->package->route()
            ->put('/role_permissions', RolePermissionController::class . '@update')
            ->name('role_permissions.update');
    }
}
