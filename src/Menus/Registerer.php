<?php

namespace Seventyninepr\Wordpress\Boilerplate\Menus;

class Registerer
{
    /**
     * Register a new menu.
     *
     * @param $menu
     * @return mixed
     */
    public static function register($menu)
    {
        if (is_array($menu)) {
            return array_map(function ($single_menu) {
                self::registerOne($single_menu);
            }, $menu);
        }

        self::registerOne($menu);
    }

    /**
     * Register the menu with wordpress and timber.
     *
     * @param $menu
     */
    private static function registerOne($menu)
    {
        self::registerWordpressMenu($menu);

        self::registerTimberMenu($menu);
    }

    /**
     * Register the main wordpress menu.
     *
     * @param $menu
     */
    private static function registerWordpressMenu($menu)
    {
        add_action('init', function () use ($menu) {
            register_nav_menu($menu->location, $menu->description);
        });
    }

    /**
     * Register the menu with timber.
     *
     * @param $menu
     */
    private static function registerTimberMenu($menu)
    {
        $menu_location = $menu->location;

        add_filter('timber_context', function ($page_data) use ($menu_location) {
            $page_data[$menu_location] = new \TimberMenu($menu_location);
            return $page_data;
        });
    }
}
