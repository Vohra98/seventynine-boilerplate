<?php

namespace Seventyninepr\Wordpress\Boilerplate\Metabox;

class AddCustomExcerpt
{
    public static function run($postTypes = [])
    {
        self::removeStandardExcerpt($postTypes);
        self::createExcerpt($postTypes);
        self::setExcerptLocation();
    }


    /**
     * Create Excerpt
     * https://wpartisan.me/tutorials/wordpress-how-to-move-the-excerpt-meta-box-above-the-editor
     *
     * Adds a custom excerpt with a custom screen location.
     * @param string $post_type
     * @return null
     */
    public static function createExcerpt($postTypes = [])
    {
        add_action('add_meta_boxes', function () use ($postTypes) {
            global $post_type;

            if (in_array($post_type, $postTypes)) {
                add_meta_box(
                    'Seventyninepr_postexcerpt',
                    __('Excerpt', 'thetab-theme'),
                    'post_excerpt_meta_box',
                    $post_type,
                    'after_title',
                    'high'
                );
            }
        });
    }


    /**
     * Set Excerpt Location
     * https://wpartisan.me/tutorials/wordpress-how-to-move-the-excerpt-meta-box-above-the-editor
     *
     * You can't actually add meta boxes after the title by default in WP so
     * we're being cheeky. We've registered our own meta box position
     * `after_title` onto which we've regiestered our new meta boxes and
     * are now calling them in the `edit_form_after_title` hook which is run
     * after the post tile box is displayed.
     *
     * @return null
     */
    public static function setExcerptLocation()
    {
        add_action('edit_form_after_title', function () {
            global $post, $wp_meta_boxes;
            // Output the `below_title` meta boxes:
            do_meta_boxes(get_current_screen(), 'after_title', $post);
        });
    }


    /**
     * Remove Standard Excerpt
     * @param array $postTypes
     * @return void
     */
    public static function removeStandardExcerpt($postTypes = [])
    {
        add_action('admin_init', function () use ($postTypes) {
            if (is_array($postTypes) && count($postTypes) > 0) {
                foreach ($postTypes as $postType) {
                    remove_post_type_support($postType, 'excerpt');
                }
            }
        });
    }
}
