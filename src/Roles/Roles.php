<?php

namespace Seventyninepr\Wordpress\Boilerplate\Roles;

class Roles
{
    /**
     * Remove Roles
     * Accepts and array of roles to remove
     * @param array $roles
     * @return void
     */
    public static function removeRoles($roles = [])
    {
        if (is_array($roles) && count($roles) > 0) {
            add_action('after_setup_theme', function () use ($roles) {
                foreach ($roles as $role) {
                    if (get_role($role)) {
                        remove_role($role);
                    }
                }
            });
        }
    }
}
