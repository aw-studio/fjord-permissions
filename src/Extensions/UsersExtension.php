<?php

namespace FjordPermissions\Extensions;

use Fjord\Vue\Table;
use Fjord\User\Models\FjordUser;
use Spatie\Permission\Models\Role;
use Fjord\Application\Vue\Extension;

class UsersExtension extends Extension
{
    /**
     * Has user permission for this extension.
     * 
     * @var \Fjord\User\Models\FjordUser $user
     * @return boolean
     */
    public function authenticate(FjordUser $user)
    {
        return $user->can('read fjord-user-roles');
    }

    /**
     * Modify component in handle method.
     * 
     * @var array $component
     * @return void
     */
    public function handle($component)
    {
        $component->prop('roles', Role::all());
    }
}
