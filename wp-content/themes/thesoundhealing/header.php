<?php

/**
 * The template for displaying header.
 *
 * @package MONA.Media / Website
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Body class
if (wp_is_mobile()) {
    $body = 'mobile-detect';
} else {
    $body = 'desktop-detect';
}

$nav_locations = get_nav_menu_locations();
$menu_pc_items = ! empty($nav_locations['header-menu-pc'])
    ? wp_get_nav_menu_object($nav_locations['header-menu-pc'])
    : null;
$menu_tree = [];
if (! empty($menu_pc_items)) {
    $menu_pc_items = wp_get_nav_menu_items($menu_pc_items->term_id, ['no_found_rows' => true,]);

    if ($menu_pc_items) {
        foreach ($menu_pc_items as $item) {
            if (!$item->menu_item_parent) {
                $menu_tree[$item->ID] = ['item' => $item, 'children' => []];
            } else {
                $parent_id = $item->menu_item_parent;
                if (isset($menu_tree[$parent_id])) {
                    $menu_tree[$parent_id]['children'][$item->ID] = ['item' => $item, 'children' => []];
                } else {
                    foreach ($menu_tree as &$top_item) {
                        if (isset($top_item['children'][$parent_id])) {
                            $top_item['children'][$parent_id]['children'][$item->ID] = ['item' => $item];
                        }
                    }
                }
            }
        }
    }
}

$header_top_settings = function_exists('get_field') ? get_field('header_top', 'option') : null;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
    <!-- Meta ================================================== -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
    <?php wp_site_icon(); ?>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php wp_head(); ?>
</head>

<body <?php body_class($body); ?>>


    <?php get_template_part('partials/components/header-main'); ?>

    <main class="<?php echo join(' ', apply_filters('mona_main_classes', ['main'])); ?>">