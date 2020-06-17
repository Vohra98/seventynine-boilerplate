<?php

namespace seventynine\Wordpress\Boilerplate\Actions;

interface Action
{
    /**
     * the name of the action to which the class method is hooked.
     *
     * @return array
     */
    public function get_tags();

    /**
     * the name of the method to be called.
     * ['my_class , 'my_method']
     *
     * @return array
     */
    public function get_function_to_add();

    /**
     * used to specify the order in which the functions associated with a particular action are executed.
     * lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
     *
     * @return int
     */
    public function get_priority();

    /**
     * the number of arguments the function accepts.
     *
     * @return int
     */
    public function get_accepted_args();
}
