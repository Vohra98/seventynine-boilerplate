<?php

namespace seventynine\Wordpress\Boilerplate\PostTypes;

use seventynine\Wordpress\Boilerplate\Helpers\Pluraliser;


class ArgsMerger
{
    protected $base_args;

    public $post_type;
    public $merged_args;

    /**
     * Perform the merge of the defaults and overridden values.
     *
     * @param $post_type
     *
     * @return mixed
     */
    public function merge($post_type)
    {
        $this->post_type = $post_type;
        $this->setLabels();
        $this->mergeArgs();
        $this->post_type->args = $this->merged_args;

        return $this->post_type;
    }

    /**
     * Set labels for the post type.
     */
    private function setLabels()
    {
        $singular_label = ucwords(str_replace('-', ' ', $this->post_type->post_type));
        $pluralised_label = isset($this->post_type->pluralised_label)
            ? $this->post_type->pluralised_label
            : Pluraliser::pluralise($singular_label);

        $this->base_args['label'] = $pluralised_label;
        $this->base_args['labels']['name'] = $pluralised_label;
        $this->base_args['labels']['singular_name'] = $singular_label;
        $this->base_args['labels']['menu_name'] = $pluralised_label;
        $this->base_args['labels']['name_admin_bar'] = $singular_label;
        $this->base_args['labels']['all_items'] = $pluralised_label;
        $this->base_args['labels']['add_new'] = "Add New " . $singular_label;
        $this->base_args['labels']['add_new_item'] = "Add New " . $singular_label;
        $this->base_args['labels']['edit_item'] = "Edit " . $singular_label;
        $this->base_args['labels']['new_item'] = "New " . $singular_label;
        $this->base_args['labels']['view_item'] = "View " . $singular_label;
        $this->base_args['labels']['search_items'] = "Search " . $pluralised_label;
        $this->base_args['labels']['not_found'] = "No " . $pluralised_label . " found";
        $this->base_args['labels']['not_found_in_trash'] = "No " . $pluralised_label . " found in Trash";
        $this->base_args['labels']['parent_item_colon'] = "Parent " . $singular_label;
    }

    /**
     * Set the $args property to the merged args. Anything overridden in
     * the defaults class or the post type itself will override this. We'll
     * also just use empty arrays if they have not been specified.
     */
    private function mergeArgs()
    {
        $default_args = (isset($this->post_type->default_args) && is_array($this->post_type->default_args))
            ? $this->post_type->default_args
            : [];

        $args = (isset($this->post_type->args) && is_array($this->post_type->args))
            ? $this->post_type->args
            : [];

        $this->merged_args = array_replace_recursive(
            $this->base_args,
            $default_args,
            $args
        );
    }
}


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
