<?php

namespace Seventyninepr\Wordpress\Boilerplate\Core\Wordpress;

class RemoveEmojiScript
{
    /**
     * Remove emoji script calls from the head.
     */
    public static function run()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
    }
}
