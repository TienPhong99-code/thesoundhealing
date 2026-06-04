<?php
defined('ABSPATH') || exit;

add_action('init', function () {

    register_taxonomy('loai_workshop', 'workshop', [
        'labels' => [
            'name'          => 'Loại Workshop',
            'singular_name' => 'Loại Workshop',
            'all_items'     => 'Tất cả Loại Workshop',
            'edit_item'     => 'Sửa Loại Workshop',
            'add_new_item'  => 'Thêm Loại Workshop mới',
            'new_item_name' => 'Tên Loại Workshop',
            'menu_name'     => 'Loại Workshop',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => false,
        'rewrite'           => ['slug' => 'loai-workshop', 'with_front' => false],
    ]);

    register_post_type('workshop', [
        'labels' => [
            'name'               => 'Workshop',
            'singular_name'      => 'Workshop',
            'add_new'            => 'Thêm Workshop',
            'add_new_item'       => 'Thêm Workshop mới',
            'edit_item'          => 'Chỉnh sửa Workshop',
            'new_item'           => 'Workshop mới',
            'view_item'          => 'Xem Workshop',
            'search_items'       => 'Tìm Workshop',
            'not_found'          => 'Không tìm thấy Workshop nào',
            'not_found_in_trash' => 'Không có Workshop trong thùng rác',
            'menu_name'          => 'Workshop',
            'all_items'          => 'Tất cả Workshop',
        ],
        'public'      => true,
        'has_archive' => false,
        'show_ui'     => true,
        'show_in_menu' => true,
        'menu_position' => 23,
        'menu_icon'   => 'dashicons-calendar-alt',
        'supports'    => ['title', 'thumbnail'],
        'rewrite'     => ['slug' => 'workshop', 'with_front' => false],
        'show_in_rest' => false,
        'taxonomies'  => ['loai_workshop'],
    ]);
}, 0);
