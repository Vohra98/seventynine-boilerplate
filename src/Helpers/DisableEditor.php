<?php

namespace Seventyninepr\Wordpress\Boilerplate\Helpers;

class DisableEditor
{
    /**
     * Run
     * Disables the editor interface for a given
     * array of post types
     * @param array $postTypes
     */
    public static function run($postTypes = [])
    {
        if (is_array($postTypes) && count($postTypes) > 0) {
            add_action('admin_init', function () use ($postTypes) {
                foreach ($postTypes as $postType) {
                    remove_post_type_support($postType, 'editor');
                }
            });
        }
    }
}
