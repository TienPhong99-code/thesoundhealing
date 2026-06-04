<?php

/**
 * Custom nav menu
 * 
 * @author MONA.Media / Website
 */
if (! defined('ABSPATH')) {
    die;
}

if (! class_exists('Mona_Walker_Nav_Menu_Mobile')) {
    class Mona_Walker_Nav_Menu_Mobile extends Walker_Nav_Menu
    {
        function start_lvl(&$output, $depth = 0, $args = array())
        {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class='menu-list'>\n";
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

            if ($has_children) {
                $item->classes[] = 'dropdown';
            }

            $output .= "<li class='" .  implode(" ", $item->classes) . "'>";

            if ($has_children && $depth === 0) {
                $output .= '<div class="dd-header">';
                $link_class = 'menu-link hd-nav-item font-bold text-[14px] flex-1 uppercase py-3 text-pri';
            } else {
                $link_class = $depth === 0
                    ? 'menu-link hd-nav-item font-bold text-[14px] block uppercase py-3 border-b border-[#f0f0f0] text-pri'
                    : 'menu-link';
            }

            //Add SPAN if no Permalink
            if ($permalink && $permalink != '#') {
                $output .= '<a class="' . $link_class . '" href="' . $permalink . '" title="' . $title . '">';
            } else {
                $output .= '<a class="' . $link_class . '" href="javascript:;" title="' . $title . '">';
            }

            $output .= '<span>' . $title . '</span>';
            $output .= '</a>';

            if ($has_children && $depth === 0) {
                $output .= '<button class="dd-toggle" type="button" aria-label="Mở submenu"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>';
                $output .= '</div>';
            }
        }
    }
}
