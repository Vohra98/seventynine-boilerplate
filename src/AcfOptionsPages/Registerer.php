<?php

namespace Seventyninepr\Wordpress\Boilerplate\AcfOptionsPages;

class Registerer
{
    /**
     * Register a new Acf Options Page.
     *
     * @param $page
     * @return mixed
     */
    public static function register($page)
    {
        if (function_exists('acf_add_options_page')) {
            if (is_array($page)) {
                return array_map(function ($single_page) {
                    self::registerOne($single_page);
                }, $page);
            }

            self::registerOne($page);
        }
    }

    /**
     * Run the acf_add_options_page on the given options page.
     *
     * @param $page
     */
    private static function registerOne($page)
    {
        add_action('init', function () use ($page) {
            acf_add_options_page($page->page);
        });
    }
}
