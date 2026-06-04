<?php

/**
 * Template: Taxonomy - Danh mục dự án
 * URL pattern: /du-an/{term-slug}
 *
 * @author MONA.Retail / Website
 */

defined('ABSPATH') || exit;

get_header();
?>

<?php
$du_an_page_id = mona_get_page_id_from_template('page-template/template-du-an.php');
if (! $du_an_page_id) {
    $du_an_page    = get_page_by_path('du-an');
    $du_an_page_id = $du_an_page ? $du_an_page->ID : null;
}
$du_an_url = $du_an_page_id ? get_permalink($du_an_page_id) : home_url('/du-an/');
$term          = get_queried_object();
?>
<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => 'Trang chủ', 'url' => home_url('/'), 'is-active' => false],
        ['title' => 'Dự án', 'url' => $du_an_url, 'is-active' => false],
        ['title' => $term->name, 'url' => '', 'is-active' => true],
    ],
]); ?>

<?php get_template_part('partials/sections/du-an-category/section', 'hero'); ?>
<?php get_template_part('partials/sections/du-an-category/section', 'projects'); ?>

<?php
get_footer();
