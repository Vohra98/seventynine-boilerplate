<?php

namespace Seventyninepr\Wordpress\Boilerplate\Acf;

class CustomFields
{
    /**
     * Disable Access
     * Disables custom fields for all user roles except administrators
     */
    public static function disableAccess()
    {
        add_filter('acf/settings/show_admin', function () {
            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();

                if (in_array('administrator', $current_user->roles)) {
                    return true;
                }
            }

            return false;
        });
    }
}
