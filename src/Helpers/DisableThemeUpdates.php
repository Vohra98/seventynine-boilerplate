<?php

namespace seventynine\Wordpress\Boilerplate\Helpers;

class DisableThemeUpdates
{
    /**
     * Run
     * Disables theme update notifications
     * @return void
     */
    public static function run()
    {
        add_filter('pre_site_transient_update_themes', '__return_true');
    }
}
