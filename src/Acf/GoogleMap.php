<?php

namespace Seventyninepr\Wordpress\Boilerplate\Acf;

class GoogleMap
{
    /**
     * Register API Key
     * @param string $key
     */
    public static function registerApiKey($key)
    {
        add_action('acf/init', function () use ($key) {
            acf_update_setting('google_api_key', $key);
        });
    }
}
