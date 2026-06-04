<?php

/**
 * Template for single: Dự án chi tiết
 *
 * @author MONA.Retail / Website
 */

defined('ABSPATH') || exit;

$du_an_page = get_page_by_path('du-an');

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => __('Trang chủ', 'monamedia'), 'url' => home_url('/'), 'is-active' => false],
        ['title' => __('Dự án', 'monamedia'), 'url' => $du_an_page ? get_permalink($du_an_page) : home_url('/du-an'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '', 'is-active' => true],
    ],
]); ?>

<?php get_footer(); ?>
