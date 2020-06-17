<?php

namespace seventynine\Wordpress\Boilerplate\Taxonomies\Loader;

/**
 * Class Query
 * @package seventynine\Wordpress\Boilerplate\Taxonomies\Loader
 */
class Query
{

    /**
     * @var \wpdb
     */
    private $wpdb;

    /**
     * @var string
     */
    private $query;

    /**
     * @var array
     */
    private $taxonomies = [];

    /**
     * @var bool
     */
    private $loadTerms = false;

    /**
     * @var bool
     */
    private $loadPosts = false;

    /**
     * @var string
     */
    private $postType;

    /**
     * @var bool
     */
    private $loadPostFeaturedImage = false;

    /**
     * @var array
     */
    private $joins = [];

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var array
     */
    private $conditions = [];

    /**
     * Query constructor.
     */
    public function __construct(array $taxonomies)
    {
        global $wpdb;

        $this->wpdb       = $wpdb;
        $this->taxonomies = $taxonomies;
        $this->query      = "
            SELECT DISTINCT
            {$this->wpdb->term_taxonomy}.taxonomy AS `taxonomy`,
            {$this->wpdb->term_taxonomy}.term_taxonomy_id AS `term_taxonomy_id`
        ";
    }

    /**
     * get the SQL query
     *
     * @return string
     */
    public function getResults()
    {
        return $this->buildQuery()->wpdb->get_results($this->query);
    }

    /**
     * set the load terms flag to either true or false
     *
     * @param $loadTerms
     * @return Query
     */
    public function setLoadTerms($loadTerms)
    {
        if (is_bool($loadTerms)) {
            $this->loadTerms = $loadTerms;
        }

        return $this;
    }

    /**
     * set the load posts flag to either true or false
     *
     * @param $loadPosts
     * @return Query
     */
    public function setLoadPosts($loadPosts)
    {
        if (is_bool($loadPosts)) {
            $this->loadPosts = $loadPosts;
        }

        return $this;
    }

    /**
     * set the post type to query
     *
     * @param $postType
     * @return Query
     */
    public function setPostType($postType)
    {
        if (is_string($postType)) {
            $this->postType = $postType;
        }

        return $this;
    }

    /**
     * set the load post featured image flag to either true or false
     *
     * @param $loadPostFeaturedImage
     * @return Query
     */
    public function setLoadPostFeaturedImage($loadPostFeaturedImage)
    {
        if (is_bool($loadPostFeaturedImage)) {
            $this->loadPostFeaturedImage = $loadPostFeaturedImage;
        }

        return $this;
    }

    /**
     * build the SQL query
     *
     * @return Query
     */
    private function buildQuery()
    {
        return $this
            ->setTermColumnJoin()
            ->setPostColumnJoin()
            ->setPostFeaturedImageColumn()
            ->setTaxonomyConditions()
            ->setPostConditions()
            ->addColumnSQL()
            ->addFromSQL()
            ->addConditionSQL()
            ->addOrderBySQL();
    }

    /**
     * add column to select i.e wp_posts.id AS `post_id`
     *
     * @param $column
     * @param $alias
     * @return Query
     */
    private function addColumn($column, $alias)
    {
        $this->columns[] = "{$column} AS `{$alias}`";

        return $this;
    }

    /**
     * add join i.e.
     * INNER JOIN wp_posts ON wp_term_relationships.object_id = wp_posts.id
     *
     * @param $joinTable
     * @param $table1ColumnName
     * @param $table2ColumnName
     * @param string $type
     * @return Query
     */
    private function addJoin($joinTable, $table1ColumnName, $table2ColumnName, $type = 'INNER JOIN')
    {
        $this->joins[] = "{$type} {$joinTable} ON {$table1ColumnName} = {$table2ColumnName}";

        return $this;
    }

    /**
     * add condition i.e. wp_posts.post_type = 'post'
     *
     * @param $condition
     * @return Query
     */
    private function addCondition($condition)
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * if $loadTerms is true, add term tables and columns to the class properties
     *
     * @return Query
     */
    private function setTermColumnJoin()
    {
        if ($this->loadTerms === true) {
            $this
                ->addColumn("{$this->wpdb->terms}.name", 'term_name')
                ->addColumn("{$this->wpdb->term_taxonomy}.description", 'term_description')
                ->addColumn("{$this->wpdb->terms}.term_id", 'term_id');
        }

        $this
            ->addJoin($this->wpdb->terms, "{$this->wpdb->term_taxonomy}.term_id", "{$this->wpdb->terms}.term_id")
            ->addJoin($this->wpdb->term_relationships, "{$this->wpdb->term_taxonomy}.term_taxonomy_id", "{$this->wpdb->term_relationships}.term_taxonomy_id");

        return $this;
    }

    /**
     * if $loadPosts is true, add post table and columns to the class properties
     *
     * @return Query
     */
    private function setPostColumnJoin()
    {
        if ($this->loadPosts === true) {
            $this
                ->addColumn("{$this->wpdb->posts}.id", 'post_id')
                ->addColumn("{$this->wpdb->posts}.post_type", 'post_type')
                ->addColumn("{$this->wpdb->posts}.post_title", 'post_title')
                ->addColumn("{$this->wpdb->posts}.post_name", 'post_name')
                ->addColumn("{$this->wpdb->posts}.post_content", 'post_content')
                ->addColumn("{$this->wpdb->posts}.post_date", 'post_date')
                ->addColumn("{$this->wpdb->posts}.guid", 'guid')
                ->addJoin($this->wpdb->posts, "{$this->wpdb->term_relationships}.object_id", "{$this->wpdb->posts}.id");
        }

        return $this;
    }

    /**
     * set the featured image sub query to the $columns array
     *
     * @return Query
     */
    private function setPostFeaturedImageColumn()
    {
        if ($this->loadPostFeaturedImage === true) {
            $this->addColumn($this->postFeaturedImageSubQuery(), 'post_featured_image');
        }

        return $this;
    }

    /**
     * returns the post featured image sub query / SQL
     *
     * @return string
     */
    private function postFeaturedImageSubQuery()
    {
        return "(
            SELECT (SELECT guid FROM {$this->wpdb->posts} WHERE id = {$this->wpdb->postmeta}.meta_value)
            FROM {$this->wpdb->postmeta}
            WHERE {$this->wpdb->posts}.id = {$this->wpdb->postmeta}.post_id
            AND {$this->wpdb->postmeta}.meta_key = '_thumbnail_id'
            GROUP BY {$this->wpdb->postmeta}.post_id
        )";
    }

    /**
     * add each taxonomy condition to the $conditions array
     *
     * @return Query
     */
    private function setTaxonomyConditions()
    {
        $conditions = '';
        foreach ($this->taxonomies as $key => $taxonomy) {
            if ($key > 0) {
                $conditions .= ' OR ';
            }
            $conditions .= "({$this->wpdb->term_taxonomy}.taxonomy = '{$taxonomy}')";
        }

        $this->addCondition("({$conditions})");

        return $this;
    }

    /**
     * add post type condition to the $conditions array
     *
     * @return Query
     */
    private function setPostConditions()
    {
        if ($this->loadPosts === true) {
            $this->addCondition("{$this->wpdb->posts}.post_status = 'publish'");
        }
        if (!empty($this->postType)) {
            $this->addCondition("{$this->wpdb->posts}.post_type = '{$this->postType}'");
        }

        return $this;
    }

    /**
     * build column SQL and append to $query
     *
     * @return Query
     */
    private function addColumnSQL()
    {
        if (count($this->columns)) {
            $this->query .= ', ' . implode(', ', $this->columns);
        }

        return $this;
    }

    /**
     * build the from & join SQL and append to $query
     *
     * @return Query
     */
    private function addFromSQL()
    {
        $this->query .= " FROM {$this->wpdb->term_taxonomy}";

        if (count($this->joins)) {
            $this->query .= ' ' . implode(' ', $this->joins);
        }

        return $this;
    }

    /**
     * build column conditions SQL and append to $query
     *
     * @return Query
     */
    private function addConditionSQL()
    {
        if (count($this->conditions) > 0) {
            $this->query .= ' WHERE ' . implode(' AND ', $this->conditions);
        }

        return $this;
    }

    /**
     * add order by SQL and append to $query
     *
     * @return Query
     */
    private function addOrderBySQL()
    {
        $this->query .= " ORDER BY {$this->wpdb->terms}.term_order ASC";

        return $this;
    }
}
