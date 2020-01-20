<?php

namespace Seventyninepr\Wordpress\Boilerplate\Helpers;

class AdminBar
{
    /**
     * Disable Admin Bar
     * @return void
     */
    public static function disable()
    {
        add_filter('show_admin_bar', '__return_false');
    }

    /**
     * Remove Nodes
     * Removes Admin Bar links
     * @param array $nodes
     * @return void
     */
    public static function removeNodes(array $nodes = [])
    {
        if (count($nodes)) {
            add_filter('admin_bar_menu', function ($toolbar) use ($nodes) {
                foreach ($nodes as $node) {
                    $toolbar->remove_node($node);
                }

                return $toolbar;
            }, 100);
        }
    }

    /**
     * Remove Styles
     * @return void
     */
    public static function removeStyles()
    {
        add_filter('show_admin_bar', function () {
            remove_action('wp_head', '_admin_bar_bump_cb');
        });
    }
}
