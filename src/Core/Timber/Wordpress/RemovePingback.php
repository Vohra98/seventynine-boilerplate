<?php

namespace seventynine\Wordpress\Boilerplate\Core\Wordpress;

class RemovePingback
{
    /**
     * Remove pingback from the headers.
     */
    public static function run()
    {
        add_filter('xmlrpc_methods', function ($methods) {
            unset($methods['pingback.ping']);
            return $methods;
        });

        add_filter('wp_headers', function ($headers) {
            unset($headers['X-Pingback']);
            return $headers;
        });
    }
}
