<?php

namespace AwStudio\FjordPermissions;

use AwStudio\Fjord\Application\Package\FjordPackage;

class Package extends FjordPackage
{
    /**
     * List of service providers to be registered for this package.
     * 
     * @var array
     */
    protected $providers = [
        \AwStudio\FjordPermissions\ServiceProvider::class
    ];

    /**
     * List of components this package contains.
     * 
     * @var array
     */
    protected $components = [
        // 'fj-permissions' => PermissionsComponent::class
    ];

    /**
     * List of handlers for config files.
     * 
     * @var array
     */
    protected $configHandler = [
        'table' => \AwStudio\FjordPermissions\Config\TableConfig::class
    ];
}
