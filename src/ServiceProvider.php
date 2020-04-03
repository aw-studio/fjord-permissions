<?php

namespace AwStudio\FjordPermissions;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use AwStudio\FjordPermissions\Composer\PermissionsComposer;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        fjord()->addLangPath(fjord_permissions_path('resources/lang'));

        fjord()->composer(PermissionsComposer::class);
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('AwStudio\FjordPermissions\RouteServiceProvider');
    }
}
