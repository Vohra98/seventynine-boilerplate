<?php

namespace Seventyninepr\Wordpress\Boilerplate\Helpers;

class DisableComments
{
    public static function run()
    {
        self::disableCommentsPostTypesSupport();
        self::disableCommentsHideExistingComments();
        self::disableCommentsAdminMenu();
        self::disableCommentsAdminMenuRedirect();
        self::disableCommentsDashboard();
        self::disableCommentsAdminBar();
    }

    /**
     * Disable support for comments and trackbacks
     * in post types
     * @return void
     */
    private static function disableCommentsPostTypesSupport()
    {
        add_action('admin_init', function () {
            $post_types = get_post_types();

            foreach ($post_types as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        });
    }


    /**
     * Close comments on the front-end
     * @return void
     */
    private static function disable_comments_status()
    {
        function set_comments_status()
        {
            return false;
        }

        add_filter('comments_open', 'set_comments_status', 20, 2);
        add_filter('pings_open', 'set_comments_status', 20, 2);
    }


    /**
     * Hide existing comments
     * @return void
     */
    private static function disableCommentsHideExistingComments()
    {
        add_filter('comments_array', function () {
            $comments = array();
            return $comments;
        }, 10, 2);
    }


    /**
     * Remove comments page in menu
     * @return void
     */
    private static function disableCommentsAdminMenu()
    {
        add_action('admin_menu', function () {
            remove_menu_page('edit-comments.php');
        });
    }


    /**
     * Redirect any user trying to access comments page
     * @return void
     */
    private static function disableCommentsAdminMenuRedirect()
    {
        add_action('admin_init', function () {
            global $pagenow;
            if ($pagenow === 'edit-comments.php') {
                wp_redirect(admin_url());
                exit;
            }
        });
    }


    /**
     * Remove comments metabox from dashboard
     * @return void
     */
    private static function disableCommentsDashboard()
    {
        add_action('admin_init', function () {
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        });
    }


    /**
     * Remove comments links from admin bar
     * @return void
     */
    private static function disableCommentsAdminBar()
    {
        add_action('init', function () {
            if (is_admin_bar_showing()) {
                remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            }
        });
    }
}
