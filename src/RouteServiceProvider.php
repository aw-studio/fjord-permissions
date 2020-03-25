<?php

namespace AwStudio\FjordPermissions;

use AwStudio\Fjord\Support\Facades\FjordRoute;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use AwStudio\FjordPermissions\Controllers\PermissionController;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapRolePermissionRoutes();
    }

    protected function mapRolePermissionRoutes()
    {
        FjordRoute::get('/fjord/permissions', PermissionController::class . '@index')->name('permissions');
        FjordRoute::put('/role_permissions', PermissionController::class . '@update')->name('role_permissions.update');
    }
}
