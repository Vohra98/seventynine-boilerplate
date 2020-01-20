<?php

namespace Seventyninepr\Wordpress\Boilerplate\Core\Wordpress;

class RemoveShortLink
{
    /**
     * Remove the short link from the head.
     */
    public static function run()
    {
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
}
