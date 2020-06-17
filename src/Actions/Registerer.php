<?php

namespace seventynine\Wordpress\Boilerplate\Actions;

/**
 * Class Registerer
 * @package seventynine\Wordpress\Boilerplate\Actions
 */
class Registerer
{
    /**
     * register wp action
     *
     * @param Action|array $action
     * @return $this|array
     */
    public static function register($action)
    {
        if (self::is_multiple_actions($action)) {
            self::register_multiple($action);

            return;
        }

        self::register_one($action);
    }

    /**
     * check if multiple actions need registering
     *
     * @param $action
     * @return bool
     */
    private static function is_multiple_actions($action)
    {
        return is_array($action);
    }

    /**
     * register multiple actions
     *
     * @param array $actions
     * @return void
     */
    private static function register_multiple(array $actions)
    {
        foreach ($actions as $action) {
            self::register_one($action);
        }
    }

    /**
     * register one action
     *
     * @param Action $action
     * @return void
     */
    private static function register_one(Action $action)
    {
        if (self::has_multiple_tags($action)) {
            self::register_multiple_tags($action);

            return;
        }

        self::register_single_tag($action);
    }

    /**
     * check if the action has multiple tags
     *
     * @param Action $action
     * @return bool
     */
    private static function has_multiple_tags(Action $action)
    {
        return is_array($action->get_tags());
    }

    /**
     * register action with multiple tags
     *
     * @param Action $action
     */
    private static function register_multiple_tags(Action $action)
    {
        foreach ($action->get_tags() as $tag) {
            add_action($tag, function ($args = null) use ($action) {
                self::invoke_function_to_add($action, $args);
            });
        }
    }

    /**
     * register action with single tag
     *
     * @param Action $action
     */
    private static function register_single_tag(Action $action)
    {
        add_action($action->get_tags(), function ($args = null) use ($action) {
            self::invoke_function_to_add($action, $args);
        });
    }

    /**
     * call $action::function_to_add()
     *
     * @param Action $action
     * @param null $args
     * @return void
     */
    private static function invoke_function_to_add(Action $action, $args = null)
    {
        call_user_func([
            self::initialize_hooked_class($action),
            self::get_hooked_method($action)
        ], $args);
    }

    /**
     * initialize the hooked class
     *
     * @param Action $action
     * @return object|bool
     */
    private static function initialize_hooked_class(Action $action)
    {
        $function_to_add = $action->get_function_to_add();
        $function_to_add_class_name = is_array($function_to_add) ? reset($function_to_add) : [];

        return class_exists($function_to_add_class_name) ? new $function_to_add_class_name : false;
    }

    /**
     * get the hooked method name
     *
     * @param Action $action
     * @return bool|string
     */
    private static function get_hooked_method(Action $action)
    {
        $function_to_add = $action->get_function_to_add();

        return is_array($function_to_add) ? end($function_to_add) : false;
    }
}
