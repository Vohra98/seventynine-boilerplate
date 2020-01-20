<?php

namespace Seventyninepr\Wordpress\Boilerplate\Taxonomies;

class Registerer
{
    /**
     * Register a new taxonomy.
     *
     * @param $taxonomy
     * @return mixed
     */
    public static function register($taxonomy)
    {
        if (is_array($taxonomy)) {
            return array_map(function ($single_taxonomy) {
                self::registerOne($single_taxonomy);
            }, $taxonomy);
        }

        self::registerOne($taxonomy);
    }

    /**
     * Run the native wordpress register_taxonomy on our taxonomy class to
     * register the new taxonomy.
     *
     * @param $taxonomy
     */
    private static function registerOne($taxonomy)
    {
        $taxonomy = (new ArgsMerger)->merge($taxonomy);

        add_action('init', function () use ($taxonomy) {
            register_taxonomy(
                $taxonomy->taxonomy,
                $taxonomy->object_type,
                $taxonomy->args
            );
        });
    }
}
