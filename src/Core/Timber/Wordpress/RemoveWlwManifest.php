<?php

namespace Seventyninepr\Wordpress\Boilerplate\Core\Wordpress;

class RemoveWlwManifest
{
    public static function run()
    {
        remove_action('wp_head', 'wlwmanifest_link');
    }
}
