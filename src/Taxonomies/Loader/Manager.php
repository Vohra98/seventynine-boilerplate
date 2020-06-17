<?php

namespace seventynine\Wordpress\Boilerplate\Taxonomies\Loader;

/**
 * Class Manager
 * @package seventynine\Wordpress\Boilerplate\Taxonomies\Loader
 */
class Manager
{

    /**
     * Manager constructor.
     *
     * @param $taxonomies
     */
    public function __construct(array $taxonomies)
    {
        $this->query      = new Query($taxonomies);
        $this->collection = new Collection($taxonomies);
    }

    /**
     * named constructor.
     *
     * @param array $taxonomies
     * @return static
     */
    public static function load(array $taxonomies)
    {
        return new static($taxonomies);
    }

    /**
     * set load terms on query object
     *
     * @return $this
     */
    public function withTerms()
    {
        $this->query->setLoadTerms(true);

        return $this;
    }

    /**
     * set load posts on query object
     *
     * @param $postType
     * @return $this
     */
    public function withPosts($postType = null)
    {
        $this->query->setLoadPosts(true);

        if (!empty($postType)) {
            $this->query->setPostType($postType);
        }

        return $this;
    }

    /**
     * set load post featured image on query object
     *
     * @return $this
     */
    public function withFeaturedPostImage()
    {
        $this->query->setLoadPostFeaturedImage(true);

        return $this;
    }

    /**
     * get results and return the collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        $results = $this->query->getResults();

        return $this->collection->format($results);
    }
}
