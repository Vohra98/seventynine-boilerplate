<?php

namespace Seventyninepr\Wordpress\Boilerplate\Taxonomies;

use Seventyninepr\Wordpress\Boilerplate\Helpers\Pluraliser;

class ArgsMerger
{
    protected $base_args;

    public $taxonomy;
    public $merged_args;

    /**
     * Perform the merge of the defaults and overridden values.
     *
     * @param $taxonomy
     *
     * @return mixed
     */
    public function merge($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        $this->setLabels();
        $this->mergeArgs();
        $this->taxonomy->args = $this->merged_args;

        return $this->taxonomy;
    }

    /**
     * Set labels for the taxonomy.
     */
    private function setLabels()
    {
        $singular_label = ucwords(str_replace('-', ' ', $this->taxonomy->taxonomy));
        $pluralised_label = Pluraliser::pluralise($singular_label);

        $this->base_args['label'] = $pluralised_label;
    }

    /**
     * Set the $merged_args property to the merged args. Anything overridden in
     * the defaults class or the taxonomy itself will override this. We'll
     * also just use empty arrays if they have not been specified.
     */
    private function mergeArgs()
    {
        $default_args = (isset($this->taxonomy->default_args) && is_array($this->taxonomy->default_args))
            ? $this->taxonomy->default_args
            : [];

        $args = (isset($this->taxonomy->args) && is_array($this->taxonomy->args))
            ? $this->taxonomy->args
            : [];

        $this->merged_args = array_replace_recursive(
            $this->base_args,
            $default_args,
            $args
        );
    }
}
