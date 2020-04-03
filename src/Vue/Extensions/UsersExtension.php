<?php

namespace AwStudio\FjordPermissions\Vue\Extensions;

use Spatie\Permission\Models\Role;
use AwStudio\Fjord\Fjord\Models\FjordUser;
use AwStudio\Fjord\Application\Vue\Extension;

class UsersExtension extends Extension
{
    /**
     * Has user permission for this extension.
     * 
     * @var \AwStudio\Fjord\Fjord\Models\FjordUser $user
     * @return boolean
     */
    public function authenticate(FjordUser $user)
    {
        return $user->can('read user-roles');
    }

    /**
     * Modify component in handle method.
     * 
     * @var array $component
     * @return void
     */
    public function handle($component)
    {
        //$component->addGlobalAction('fj-permissions-apply-to-users');

        if (fjord_user()->can('update user-roles')) {
            $component->addRecordAction('fj-permissions-apply-to-user');
        }

        $component->addProp('roles', Role::all());

        $component->addTableColumn([
            'label' => __f('fj.role'),
            'key' => '',
            'component' => 'fj-permissions-users-permission',
        ]);
    }
}
