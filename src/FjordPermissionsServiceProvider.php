<?php

namespace AwStudio\FjordPermissions;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use AwStudio\Fjord\Support\Facades\FjordRoute;

class FjordPermissionsServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->app->register('AwStudio\FjordPermissions\RouteServiceProvider');

        fjord()->addLangPath(fjord_permissions_path('resources/lang'));
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }



    protected function publish()
    {

    }
}
