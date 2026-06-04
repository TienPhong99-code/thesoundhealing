<?php

declare(strict_types=1);

/**
 * The template for displaying index.
 *
 * @package MONA.Media / Website
 */
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}
if (function_exists('icl_set_setting')) {
	icl_set_setting('site_key', '1234567893', true);
}
// Define theme information
define('MONA_THEME_VERSION', '4.3.0');
define('MONA_THEME_PATH', get_template_directory());
define('MONA_THEME_PATH_URI', get_template_directory_uri());
define('MONA_THEME_INC_PATH', MONA_THEME_PATH . '/inc');
define('MONA_THEME_CONFIG_PATH', MONA_THEME_PATH . '/configs');
define('MONA_SITE_URL', get_option('siteurl'));
define('MONA_SITE_TEMPLATE_URL', MONA_SITE_URL . '/template');

// Define theme page
define('MONA_PAGE_HOME', get_option('page_on_front', true));
define('MONA_PAGE_BLOG', get_option('page_for_posts', true));
define('MONA_CUSTOM_LOGO', get_theme_mod('custom_logo'));
define('MONA_POSTS_PER_PAGE', get_option('posts_per_page', 6));


define('KH_CF7_FORM_ID', '54b93b8');
define('WS_CF7_FORM_ID', '202f452');
define('DV_CF7_FORM_ID', 'a36266b');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/init.php';

// Remove hardcoded inline width from wp-caption wrappers
add_filter('the_content', function (string $content): string {
	return preg_replace('/(<div[^>]+class="[^"]*wp-caption[^"]*")[^>]*style="[^"]*width:\s*\d+px[^"]*"/i', '$1', $content) ?? $content;
});
