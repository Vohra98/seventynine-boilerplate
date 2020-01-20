<?php

namespace Seventyninepr\Wordpress\Boilerplate\Taxonomies\Loader;

use Illuminate\Support\Collection as IlluminateCollection;

/**
 * Class Collection
 * @package Seventyninepr\Wordpress\Boilerplate\Taxonomies\Loader
 */
class Collection extends \ArrayIterator
{

    /**
     * @var IlluminateCollection
     */
    private $collection;

    /**
     * Collection constructor
     */
    public function __construct(array $taxonomies)
    {
        $this->collection = IlluminateCollection::make();

        parent::__construct($taxonomies);
    }

    /**
     * format collection object
     *
     * @param array $results
     * @return IlluminateCollection
     */
    public function format(array $results)
    {
        for ($i = 0; $i < $this->count(); ++$i) {
            $this->createTaxonomyKey()->createTermsKey()->filterResultsByTaxonomy($results)->each(
                function ($item) {
                    $this->createNameKey($item)
                         ->createTermTaxonomyIdKey($item)
                         ->createTermsWithPostsKeys($item)
                         ->createPostsKey($item);
                }
            );

            $this->next();
        }

        return $this->collection;
    }

    /**
     * filter the results by the current taxonomy
     *
     * @param $results
     * @return mixed
     */
    private function filterResultsByTaxonomy($results)
    {
        $filtered = IlluminateCollection::make($results)->filter(
            function ($item) {
                return $item->taxonomy === $this->current();
            }
        );

        return $filtered;
    }

    /**
     * checks if the current taxonomy key is set on the collection
     *
     * @return bool
     */
    private function hasTaxonomyKey()
    {
        return $this->collection->has($this->current());
    }

    /**
     * create a collection key for the taxonomy
     *
     * @return Collection
     */
    private function createTaxonomyKey()
    {
        $this->collection->put($this->current(), IlluminateCollection::make());

        return $this;
    }

    /**
     * create a taxonomy name array key on the collection array & set the value
     *
     * @param $item
     * @return Collection
     */
    private function createNameKey($item)
    {
        if ($this->hasTaxonomyKey()) {
            $this->collection->get($this->current())->put('name', $item->taxonomy);
        }

        return $this;
    }

    /**
     * create a taxonomy term_taxonomy_id array key on the collection array & set the value
     *
     * @param $item
     * @return Collection
     */
    private function createTermTaxonomyIdKey($item)
    {
        if ($this->hasTaxonomyKey()) {
            $this->collection->get($this->current())->put('term_taxonomy_id', $item->term_taxonomy_id);
        }

        return $this;
    }

    /**
     * create a taxonomy terms key in the collection
     *
     * @return Collection
     */
    private function createTermsKey()
    {
        if ($this->hasTaxonomyKey()) {
            $this->collection->get($this->current())->put('terms', IlluminateCollection::make());
        }

        return $this;
    }

    /**
     * checks if the terms collection contains a term key
     *
     * @param $term
     * @return boolean
     */
    private function hasTerm($term)
    {
        return $this->collection->get($this->current())->get('terms')->has($term);
    }

    /**
     * push a term to the terms collection
     *
     * @param $term
     * @return $this
     */
    private function createTerm($term)
    {
        $this->collection->get($this->current())->get('terms')->put($term, IlluminateCollection::make());

        return $this;
    }

    /**
     * get a term from the terms collection
     *
     * @param $term
     * @return IlluminateCollection
     */
    private function getTerm($term)
    {
        return $this->collection->get($this->current())->get('terms')->get($term);
    }

    /**
     * check if the current item contains term data
     *
     * @param $item
     * @return bool
     */
    private function hasTermProperties($item)
    {
        return property_exists($item, 'term_id') && property_exists($item, 'term_name');
    }

    /**
     * checks if the current taxonomy posts key is set on the collection
     *
     * @return bool
     */
    private function hasPostsKey()
    {
        return $this->collection->get($this->current())->has('posts');
    }

    /**
     * add's posts to the taxonomy array key on the collection array
     *
     * @param $item
     * @return Collection
     */
    private function createPostsKey($item)
    {
        if (!$this->hasTermProperties($item) && $this->hasPostProperties($item)) {
            if (!$this->hasPostsKey()) {
                $this->collection->get($this->current())->put('posts', IlluminateCollection::make());
            }

            $this->collection->get($this->current())->get('posts')->push($item);
        }

        return $this;
    }

    /**
     * check if the current item contains post data
     *
     * @param $item
     * @return bool
     */
    private function hasPostProperties($item)
    {
        return property_exists($item, 'post_id');
    }

    /**
     * if $item has term properties, add's the term to the collection
     * add's post data if $item has post properties
     *
     * @param $item
     * @return Collection
     */
    private function createTermsWithPostsKeys($item)
    {
        if ($this->hasTermProperties($item)) {
            $this->addTermKeyValue(['key' => 'term_id', 'val' => $item->term_id, 'term' => $item->term_name])
                 ->addTermKeyValue(['key' => 'term_name', 'val' => $item->term_name, 'term' => $item->term_name])
                 ->addTermKeyValue(['key' => 'term_description', 'val' => $item->term_description, 'term' => $item->term_name]);

            if ($this->hasPostProperties($item)) {
                $this->addTermKeyValue(['key' => 'posts', 'val' => $item, 'term' => $item->term_name, 'push' => true]);
            }
        }

        return $this;
    }

    /**
     * add's key value data to a given term specified in $args
     *
     * @param $args
     * @return Collection
     */
    private function addTermKeyValue(array $args)
    {
        $required = ['key', 'val', 'term'];
        if (count(array_intersect_key(array_flip($required), $args)) === count($required)) {
            if (!$this->hasTerm($args['term'])) {
                $this->createTerm($args['term']);
            }

            if (isset($args['push']) && $args['push'] === true) {
                return $this->pushTermKeyValue($args);
            }

            $this->getTerm($args['term'])->put($args['key'], $args['val']);
        }

        return $this;
    }

    /**
     * pushes key value data to a given term specified in $args
     *
     * @param array $args
     * @return $this
     */
    private function pushTermKeyValue(array $args)
    {
        if (!$this->getTerm($args['term'])->has($args['key'])) {
            $this->getTerm($args['term'])->put($args['key'], IlluminateCollection::make());
        }

        $this->getTerm($args['term'])->get($args['key'])->push($args['val']);

        return $this;
    }
}
