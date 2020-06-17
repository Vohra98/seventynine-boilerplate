<?php

namespace seventynine\Wordpress\Boilerplate\Core\Wordpress;

class RemoveGenerator
{
    /**
     * Remove generator from wp_head.
     */
    public static function run()
    {
        add_filter('the_generator', function () {
            return '';
        });
    }
}
