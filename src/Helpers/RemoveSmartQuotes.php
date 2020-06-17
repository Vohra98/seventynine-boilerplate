<?php

namespace seventynine\Wordpress\Boilerplate\Helpers;

class RemoveSmartQuotes
{
    /**
     * Removes WordPress Smart Quotes
     * Improves font rendering when using custom fonts
     */
    public static function run()
    {
        add_filter('run_wptexturize', '__return_false');
    }
}
