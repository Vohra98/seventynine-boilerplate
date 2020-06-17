<?php

namespace seventynine\Wordpress\Boilerplate\PostTypes;

class Registerer
{
    /**
     * Register a new post type.
     *
     * @param $post_type
     * @return mixed
     */
    public static function register($post_type)
    {
        if (is_array($post_type)) {
            return array_map(function ($single_post_type) {
                self::registerOne($single_post_type);
            }, $post_type);
        }

        self::registerOne($post_type);
    }

    /**
     * Run the native wordpress register_post_type on our post_type class to
     * register the new post type.
     *
     * @param $post_type
     */
    private static function registerOne($post_type)
    {
        $post_type = (new ArgsMerger)->merge($post_type);

        add_action('init', function () use ($post_type) {
            register_post_type(
                $post_type->post_type,
                $post_type->args
            );
        });
    }
}
