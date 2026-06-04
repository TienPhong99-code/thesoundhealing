<?php

/**
 * Custom nav menu
 * 
 * @author MONA.Media / Website
 */
if (! defined('ABSPATH')) {
    die;
}

if (! class_exists('Mona_Walker_Nav_Menu_Desktop')) {
    class Mona_Walker_Nav_Menu_Desktop extends Walker_Nav_Menu
    {
        function start_lvl(&$output, $depth = 0, $args = array())
        {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class='child js-child{$depth}'>\n";
        }

        function end_lvl(&$output, $depth = 0, $args = array())
        {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }

        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
        {
            $title = $item->title;
            $permalink = $item->url;

            // Check has children
            $has_children = in_array('menu-item-has-children', $item->classes);

            $images = get_field('images', $item);
            if ($has_children) {
                $item->classes[] = 'dropdown';
            }

            $output .= "<li class='" .  implode(" ", $item->classes) . "'>";

            $link_class = $depth === 0 ? 'menu-link hd-nav-link' : 'menu-link';

            //Add SPAN if no Permalink
            if ($permalink && $permalink != '#') {
                $output .= '<a class="' . $link_class . '" href="' . $permalink . '" title="' . $title . '">';
            } else {
                $output .= '<a class="' . $link_class . '" href="javascript:;" title="' . $title . '">';
            }

            $output .= '<span>' . $title . '</span>';

            $output .= '</a>';

            if ($has_children && $depth === 0) {
                $output .= '<span class="dd-arrow"><svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
            }

            // If image
            $output .= '<div class="images" style="display:none;">';
            if ($images) {
                $output .= '<div class="desk-imgs">';
                foreach ($images as $image) {
                    $output .= '<div class="desk-img">';
                    $output .= mona_get_image_by_id($image);
                    $output .= '</div>';
                }
                $output .= '</div>';
            }
            $output .= '</div>';
        }
    }
}
