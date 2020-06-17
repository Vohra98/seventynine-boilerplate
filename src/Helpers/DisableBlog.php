<?php

namespace seventynine\Wordpress\Boilerplate\Helpers;

class DisableBlog
{
    public static function run()
    {
        self::removePostsAdminPage();
        self::redirectPostsAdminPages();
        self::redirectPostsPages();
    }

    /**
     * Remove Posts Admin Page
     * @return void
     */
    public static function removePostsAdminPage()
    {
        add_action('admin_menu', function () {
            remove_menu_page('edit.php');
        });
    }

    /**
     * Redirect Posts Admin Pages
     * @return void
     */
    public static function redirectPostsAdminPages()
    {
        add_action('admin_init', function () {
            global $pagenow;
            $hasPostType = isset($_GET['post_type']);

            $blog_pages = [
                'edit.php',
                'edit-tags.php',
                'post-new.php'
            ];

            if (in_array($pagenow, $blog_pages) && !$hasPostType) {
                wp_redirect(admin_url());
                exit;
            }
        });
    }

    /**
     * Redirect Posts Pages
     * @return void
     */
    public static function redirectPostsPages()
    {
        add_action('template_redirect', function () {
            if (is_archive() || is_single() || is_home()) {
                global $wp_query;
                $wp_query->set_404();
            }
        });
    }
}
