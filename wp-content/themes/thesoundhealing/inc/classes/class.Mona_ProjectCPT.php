<?php
defined('ABSPATH') || exit;

if (!class_exists('Mona_ProjectCPT')) {
    class Mona_ProjectCPT
    {
        public static function init()
        {
            add_action('init', [__CLASS__, 'register_post_type']);
            add_action('init', [__CLASS__, 'register_taxonomy']);
        }

        public static function register_post_type()
        {
            register_post_type('project', [
                'labels' => [
                    'name'               => 'Dự án',
                    'singular_name'      => 'Dự án',
                    'add_new'            => 'Thêm dự án',
                    'add_new_item'       => 'Thêm dự án mới',
                    'edit_item'          => 'Sửa dự án',
                    'new_item'           => 'Dự án mới',
                    'view_item'          => 'Xem dự án',
                    'search_items'       => 'Tìm kiếm dự án',
                    'not_found'          => 'Không tìm thấy dự án',
                    'not_found_in_trash' => 'Không tìm thấy trong thùng rác',
                ],
                'public'        => true,
                'has_archive'   => false,
                'rewrite'       => ['slug' => 'du-an'],
                'supports'      => ['title', 'thumbnail'],
                'show_in_rest'  => false,
                'menu_icon'     => 'dashicons-building',
                'menu_position' => 5,
            ]);
        }

        public static function register_taxonomy()
        {
            register_taxonomy('project_category', 'project', [
                'labels' => [
                    'name'          => 'Danh mục dự án',
                    'singular_name' => 'Danh mục dự án',
                    'add_new_item'  => 'Thêm danh mục',
                    'edit_item'     => 'Sửa danh mục',
                    'search_items'  => 'Tìm kiếm danh mục',
                    'all_items'     => 'Tất cả danh mục',
                ],
                'hierarchical' => true,
                'public'       => true,
                'show_ui'      => true,
                'show_in_rest' => false,
                'rewrite'      => ['slug' => 'linh-vuc'],
            ]);
        }
    }

    Mona_ProjectCPT::init();
}
