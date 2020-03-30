<?php

namespace AwStudio\FjordPermissions\Vue\Extensions;

use AwStudio\Fjord\Application\Vue\Extension;

class UsersExtension extends Extension
{
    /**
     * Modify props in handle method.
     * 
     * @var array $props
     * @return array $props
     */
    public function handle($props)
    {
        $props['config']['globalActions'] []= 'fj-permissions-apply-to-users';

        return $props;
    }
}