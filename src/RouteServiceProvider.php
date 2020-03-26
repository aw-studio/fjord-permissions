<?php

namespace AwStudio\FjordPermissions;

use AwStudio\Fjord\Support\Facades\Fjord;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use AwStudio\FjordPermissions\Controllers\PermissionController;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    protected $package;

    public function __construct($app)
    {
        $this->package = fjord()->package('aw-studio/fjord-permissions');
        parent::__construct($app);
    }

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
        $route = $this->package->route()
            ->get('/permissions', PermissionController::class . '@index')
            ->name('permissions');

        $this->package->extendable($route, ['buttons']);

        $this->package->route()
            ->put('/role_permissions', PermissionController::class . '@update')
            ->name('role_permissions.update');
    }
}
