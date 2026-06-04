<?php

/**
 * Template: Trang chi tiết danh mục dự án (taxonomy-project_category.php)
 *
 * @author MONA.Retail / Website
 */
defined('ABSPATH') || exit;

$term     = get_queried_object();
$term_id  = $term->term_id;

$image_id    = get_field('image', 'project_category_' . $term_id);
$cat_desc    = get_field('category_description', 'project_category_' . $term_id) ?: '';

// Trang cha /du-an/ (page dùng template-projects.php)
$projects_page_url = '';
$projects_page = get_pages(['meta_key' => '_wp_page_template', 'meta_value' => 'page-template/template-projects.php']);
if (!empty($projects_page)) {
    $projects_page_url = get_permalink($projects_page[0]->ID);
}

// Query dự án thuộc danh mục này
$paged = max(get_query_var('paged'), 1);
$projects_query = new WP_Query([
    'post_type'      => 'project',
    'posts_per_page' => 6,
    'paged'          => $paged,
    'tax_query'      => [[
        'taxonomy' => 'project_category',
        'field'    => 'term_id',
        'terms'    => $term_id,
    ]],
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

get_header();

// Hero
get_template_part('partials/sections/projects/section-hero', null, [
    'title'      => strtoupper($term->name),
    'desc_red'   => '',
    'desc'       => $cat_desc,
    'image_id'   => $image_id,
    'breadcrumb' => array_filter([
        ['label' => 'Trang chủ', 'url' => home_url('/')],
        $projects_page_url ? ['label' => 'Dự án', 'url' => $projects_page_url] : null,
        ['label' => $term->name, 'url' => get_term_link($term)],
    ]),
]);

// Danh sách dự án
get_template_part('partials/sections/projects/section-project-list', null, [
    'term'  => $term,
    'query' => $projects_query,
]);

get_footer();
