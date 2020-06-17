<?php

namespace seventynine\Wordpress\Boilerplate\Core\Timber;

use \Timber;

class Defaults
{
    /**
     * Set some defaults for the timber plugin.
     */
    public static function run()
    {
        if (class_exists('Timber')) {
            Timber::$cache = false;
            Timber::$twig_cache = false;
            Timber::$dirname = 'templates';
        }
    }
}
