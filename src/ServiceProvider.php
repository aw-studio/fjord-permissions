<?php

namespace FjordPermissions;

use Illuminate\Routing\Router;
use Fjord\Support\Facades\FjordLang;
use FjordPermissions\Composer\PermissionsComposer;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        FjordLang::addPath(fjord_permissions_path('resources/lang'));

        fjord()->composer(PermissionsComposer::class);
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('FjordPermissions\RouteServiceProvider');
    }
}
