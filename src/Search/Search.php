<?php

namespace Seventyninepr\Wordpress\Boilerplate\Search;

class Search
{
    /**
     * Customise Url
     * Customises the search URL
     * @param string $path
     * @return void
     */
    public static function customiseUrl($path = '/search/')
    {
        add_action('template_redirect', function () {
            if (is_search() && isset($_GET['s'])) {
                wp_redirect(home_url($path) . urlencode(get_search_query(true)));
                exit();
            }
        });
    }


    /**
     * Disable
     * Disables search functionality.
     * @return void
     */
    public static function disable()
    {
        self::disableSearchQuery();
        self::disableSearchForm();
        self::removeSearchFromAdminBar();
    }


    /**
     * Disable Search Query
     * Disables WordPress search and redirects queries to 404 page.
     * @return void
     */
    public static function disableSearchQuery()
    {
        add_action('parse_query', function ($query, $error = true) {
            if (is_search()) {
                $query->is_search = false;
                $query->query_vars['s'] = false;
                $query->query['s'] = false;

                if ($error == true) {
                    $query->is_404 = true;
                }
            }
        });
    }


    /**
     * Disable Search Form
     * Disable search form used in widgets.
     * @param string $form
     * @return void
     */
    public static function disableSearchForm()
    {
        add_filter('get_search_form', function ($form) {
            return null;
        });
    }


    /**
     * Remove Search from Admin Bar
     * @return void
     */
    public static function removeSearchFromAdminBar()
    {
        add_filter('admin_bar_menu', function ($toolbar) {
            $toolbar->remove_node('search');
            return $toolbar;
        }, 100);
    }
}
