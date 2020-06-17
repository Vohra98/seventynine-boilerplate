<?php

namespace seventynine\Wordpress\Boilerplate\Core\Wordpress;

class DisableXmlRpc
{
    /**
     * Disable Xml Rpc
     */
    public static function run()
    {
        add_filter('xmlrpc_enabled', '__return_false');
    }
}
