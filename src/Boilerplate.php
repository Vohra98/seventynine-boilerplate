<?php

namespace seventynine\Wordpress\Boilerplate;

use \Timber;

class Boilerplate
{
    /**
     * Start the boilerplate bootstrap process.
     */
    public static function bootstrap()
    {
        add_filter('xmlrpc_enabled', '__return_false');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        add_filter('the_generator', function () {
            return '';
        });
        add_filter('xmlrpc_methods', function ($methods) {
            unset($methods['pingback.ping']);
            return $methods;
        });

        add_filter('wp_headers', function ($headers) {
            unset($headers['X-Pingback']);
            return $headers;
        });
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'wlwmanifest_link');
        if (class_exists('Timber')) {
            Timber::$cache = false;
            Timber::$twig_cache = false;
            Timber::$dirname = 'templates';
        }
    }
}
