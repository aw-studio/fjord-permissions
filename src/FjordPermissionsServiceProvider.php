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
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('AwStudio\FjordPermissions\RouteServiceProvider');
        /*
        fjord()->package('aw-studio/fjord-permissions')->setProps([

        ]);
        */
    }



    protected function publish()
    {

    }
}
