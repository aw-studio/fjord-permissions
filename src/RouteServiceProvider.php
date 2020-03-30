<?php

namespace AwStudio\FjordPermissions;

use Illuminate\Support\Facades\Route;
use AwStudio\Fjord\Support\Facades\Fjord;
use AwStudio\Fjord\Support\Facades\Package;
use App\Providers\RouteServiceProvider as LaravelRouteServiceProvider;
use AwStudio\FjordPermissions\Controllers\PermissionController;

class RouteServiceProvider extends LaravelRouteServiceProvider
{
    protected $package;

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->package = Package::get('aw-studio/fjord-permissions');

        $this->mapRolePermissionRoutes();
    }

    protected function mapRolePermissionRoutes()
    {
        $this->package->route()
            ->get('/permissions', PermissionController::class . '@index')
            ->name('permissions');

        $this->package->route()
            ->post('/index', PermissionController::class . '@fetchIndex')
            ->name('permissions.index');

        $this->package->route()
            ->put('/role_permissions', PermissionController::class . '@update')
            ->name('role_permissions.update');
    }
}
