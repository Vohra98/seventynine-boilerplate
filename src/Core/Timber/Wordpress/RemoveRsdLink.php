<?php

namespace Seventyninepr\Wordpress\Boilerplate\Core\Wordpress;

class RemoveRsdLink
{
    /**
     * Remove the RSD link used by weblog client.
     */
    public static function run()
    {
        remove_action('wp_head', 'rsd_link');
    }
}
