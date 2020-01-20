<?php

namespace Seventyninepr\Wordpress\Boilerplate\Helpers;

class TinyMCE
{
    /**
     * Filter block elements
     * Filters a list of block elements that can be used
     * with the Tiny MCE editor
     * https://www.tiny.cloud/docs/configure/content-formatting/#block_formats
     * @param array $elements
     * @param array $customStyles
     * @return void
     */
    public static function filterBlockElements($elements = [], $customStyles = [])
    {
        if (is_array($elements) && count($elements) > 0) {
            $filteredElements = implode(';', $elements);

            add_filter('tiny_mce_before_init', function ($init) use ($filteredElements) {
                // Add block format elements you want to show in dropdown
                $init['block_formats'] = $filteredElements;
                return $init;
            });
        }
    }

    /**
     * Init
     * Initialise TinyMCE with custom settings
     * https://codex.wordpress.org/Plugin_API/Filter_Reference/tiny_mce_before_init
     * @param array $settings
     * @return void
     */
    public static function init(array $settings = [])
    {
        if (count($settings) > 0) {
            add_filter('tiny_mce_before_init', function ($init) use ($settings) {
                $init = array_merge($init, $settings);

                if (array_key_exists('style_formats', $init)) {
                    $style_formats = json_encode($init['style_formats']);
                    $init['style_formats'] = $style_formats;
                }

                if (array_key_exists('block_formats', $init) && is_array($init['block_formats'])) {
                    $block_formats = implode(';', $init['block_formats']);
                    $init['block_formats'] = $block_formats;
                }

                return $init;
            });
        }
    }

    /**
     * Create Toolbars
     * Create custom toolbars for use with ACF PRO.
     * https://www.advancedcustomfields.com/resources/customize-the-wysiwyg-toolbars/
     * @param array $toolbars
     * @return void
     */
    public static function createToolbars(array $toolbars = [])
    {
        if (count($toolbars)) {
            add_filter('acf/fields/wysiwyg/toolbars', function ($config) use ($toolbars) {
                foreach ($toolbars as $name => $toolbar) {
                    $config[$name][1] = $toolbar;
                }

                return $config;
            });
        }
    }
}
