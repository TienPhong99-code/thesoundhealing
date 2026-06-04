<?php

/**
 * Mona setup theme
 * 
 * @package MonaTheme
 * @author MONA
 */

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('Mona_SetupTheme')) {
    class Mona_SetupTheme
    {
        public static $instance = null;

        private function __construct()
        {
            // Setup theme features and hooks
            add_action('after_setup_theme', [$this, 'after_setup_theme']);
            add_action('login_enqueue_scripts', [$this, 'custom_login_page']);

            // Add filters
            $this->add_filters();

            // Remove actions from wp_head
            $this->remove_wp_head_actions();

            $this->remove_block_assets();
        }

        public static function get_instance()
        {
            if (! self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Setup theme support features
         */
        public function after_setup_theme()
        {
            if (! current_user_can('administrator') && ! is_admin()) {
                show_admin_bar(false);
            }

            load_theme_textdomain('monamedia', get_template_directory() . '/languages');
            add_theme_support('post-thumbnails');
            add_theme_support('woocommerce');
            add_theme_support('title-tag');
            add_theme_support('menus');
            add_theme_support('html5', ['comment-list', 'search-form', 'comment-form']);
            add_theme_support('custom-logo', [
                'height'      => 100,
                'width'       => 400,
                'flex-height' => true,
                'flex-width'  => true,
                'header-text' => ['site-title', 'site-description'],
            ]);
        }

        /**
         * Add custom filters
         */
        private function add_filters()
        {
            add_filter('wp_title', [$this, 'rewrite_title_tag'], 10, 3);
            add_filter('the_generator', [$this, 'remove_rss_version']);
            add_filter('the_content', [$this, 'remove_ptags_on_images']);
            add_filter('get_the_archive_title', [$this, 'rewrite_term_title']);
            add_filter('login_errors', [$this, 'custom_wordpress_error_message']);
            add_filter('style_loader_src', [$this, 'remove_version_from_scripts']);
            add_filter('script_loader_src', [$this, 'remove_version_from_scripts']);
            add_filter('mod_rewrite_rules', [$this, 'rewrite_htaccess'], 999999);
            add_filter('upload_mimes', [$this, 'custom_upload_mimes']);
            add_filter('wpcf7_autop_or_not', '__return_false');
            add_filter('xmlrpc_enabled', '__return_false');
            add_filter('embed_oembed_discover', '__return_false');
        }

        /**
         * Remove unnecessary actions from wp_head
         */
        private function remove_wp_head_actions()
        {
            remove_action('wp_head', 'wlwmanifest_link');
            remove_action('wp_head', 'parent_post_rel_link', 10, 0);
            remove_action('wp_head', 'start_post_rel_link', 10, 0);
            remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
            remove_action('wp_head', 'wp_generator');
            remove_action('wp_head', 'rest_output_link_wp_head', 10);
            remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
            remove_action('wp_head', 'wp_oembed_add_host_js');
            remove_action('wp_head', 'rsd_link');
            remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
            remove_action('rest_api_init', 'wp_oembed_register_route');
        }

        /**
         * Custom WordPress error message on login page
         */
        public function custom_wordpress_error_message()
        {
            return __('That was not quite correct...', 'monamedia');
        }

        /**
         * Remove version query from scripts and styles
         */
        public function remove_version_from_scripts($src)
        {
            if (! WP_DEBUG) return $src;

            // Giữ lại version của mona-custom.css để cache busting hoạt động
            if (strpos($src, 'mona-custom.css') !== false) return $src;

            return strpos($src, 'ver=') ? remove_query_arg('ver', $src) : $src;
        }

        /**
         * Customize WordPress title tag
         */
        public function rewrite_title_tag($title, $sep, $seplocation)
        {
            global $page, $paged;

            if (is_feed()) return $title;

            $title .= ('right' == $seplocation) ? get_bloginfo('name') : get_bloginfo('name') . $title;

            $site_description = get_bloginfo('description', 'display');
            if ($site_description && (is_home() || is_front_page())) {
                $title .= " {$sep} {$site_description}";
            }

            if ($paged >= 2 || $page >= 2) {
                $title .= " {$sep} " . sprintf(__('Page %s', 'monamedia'), max($paged, $page));
            }

            return $title;
        }

        /**
         * Remove RSS generator version
         */
        public function remove_rss_version()
        {
            return '';
        }

        /**
         * Remove <p> tags from images
         */
        public function remove_ptags_on_images($content)
        {
            return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
        }

        /**
         * Rewrite archive titles
         */
        public function rewrite_term_title($title)
        {
            $strip_texts = ['Category:', 'Tag:', 'Tags:'];
            return (is_category() || is_tag()) ? str_replace($strip_texts, '', $title) : $title;
        }

        /**
         * Customize rewrite rules
         */
        public function rewrite_htaccess($rules)
        {
            $custom_rules = "
                RewriteRule wp-content/plugins/(.*\.php)$ - [R=404,L]
                RewriteRule wp-content/themes/(.*\.php)$ - [R=404,L]
                RewriteCond %{QUERY_STRING} (<|%3C).*script.*(>|%3E) [NC,OR]
                RewriteCond %{QUERY_STRING} GLOBALS(=|[|%[0-9A-Z]{0,2}) [OR]
                RewriteCond %{QUERY_STRING} _REQUEST(=|[|%[0-9A-Z]{0,2})
                RewriteRule ^(.*)$ index.php [F,L]
                RewriteRule ^wp-admin/includes/ - [F,L]
                RewriteRule !^wp-includes/ - [S=3]
                RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
                RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
                RewriteRule ^wp-includes/theme-compat/ - [F,L]
                RewriteRule ^wp-content/uploads/.*\.(php|rb|py)$ - [F,L,NC]
                RewriteRule ^wp-config.php$ - [F,L,NC]
            ";
            return str_replace("</IfModule>", $custom_rules . "</IfModule>", $rules);
        }

        /**
         * Allow SVG uploads
         */
        public function custom_upload_mimes($mimes)
        {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }

        /**
         * Custom login page styles
         */
        public function custom_login_page()
        {
            if ($GLOBALS['pagenow'] === 'wp-login.php') {
                wp_enqueue_style('mona-style-login-template', MONA_THEME_PATH_URI . '/assets/css/page-login.css');
            }
        }

        public function remove_block_assets()
        {
            // Disable Gutenberg for all post types
            add_filter('use_block_editor_for_post', '__return_false', 10);
            add_filter('use_block_editor_for_post_type', '__return_false', 10);

            // Disable block widgets
            add_action('init', function () {
                add_filter('use_widgets_block_editor', '__return_false');
                add_filter('gutenberg_use_widgets_block_editor', '__return_false');
            });

            // Remove Gutenberg CSS/JS
            add_action('wp_enqueue_scripts', function () {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
                wp_dequeue_style('wc-block-style'); // WooCommerce block styles
            }, 100);

            // Disable WooCommerce Blocks scripts
            add_filter('woocommerce_blocks_enqueue_scripts', '__return_false');
        }
    }
}
