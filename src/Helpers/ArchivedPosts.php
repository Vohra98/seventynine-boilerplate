<?php

namespace seventynine\Wordpress\Boilerplate\Helpers;

class ArchivedPosts
{
    /**
     * Access By Url
     * Plugin: https://wordpress.org/plugins/archived-post-status/
     * https://wordpress.org/support/topic/404-error-236/
     * Ensures all archived post types can be acessed via a URL.
     * Prevents WordPress returning a 404 error.
     */
    public static function accessByUrl()
    {
        add_filter('aps_status_arg_public', '__return_true');
        add_filter('aps_status_arg_private', '__return_false');
        add_filter('aps_status_arg_exclude_from_search', '__return_false');
    }
}
