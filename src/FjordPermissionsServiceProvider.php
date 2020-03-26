<?php

namespace AwStudio\FjordPermissions;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use AwStudio\Fjord\Support\Facades\FjordRoute;

class FjordPermissionsServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        fjord()->addLangPath(fjord_permissions_path('resources/lang'));


        //fjord()->extend('users')->add('tableActions', 'fj-permissions-...');
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('AwStudio\FjordPermissions\RouteServiceProvider');

        fjord()->composer(FjordPermissionsComposer::class);
    }



    protected function publish()
    {

    }
}
