<?php

namespace seventynine\Wordpress\Boilerplate\Helpers;

class RemovePostTags
{
    public static function run()
    {
        add_action('init', function () {
            unregister_taxonomy_for_object_type('post_tag', 'post');
        });
    }
}
