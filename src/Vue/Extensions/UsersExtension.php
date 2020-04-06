<?php

namespace FjordPermissions\Vue\Extensions;

use Spatie\Permission\Models\Role;
use Fjord\Fjord\Models\FjordUser;
use Fjord\Application\Vue\Extension;

class UsersExtension extends Extension
{
    /**
     * Has user permission for this extension.
     * 
     * @var \Fjord\Fjord\Models\FjordUser $user
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
        if (fjord_user()->can('update fjord-user-roles')) {
            $component->addRecordAction('fj-permissions-fjord-users-apply-role');
        }

        $component->addProp('roles', Role::all());

        $component->addTableColumn([
            'label' => __f('fj.role'),
            'key' => '',
            'component' => 'fj-permissions-fjord-users-role-name',
        ]);
    }
}
