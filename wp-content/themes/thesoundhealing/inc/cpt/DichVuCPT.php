<?php
defined('ABSPATH') || exit;

add_action('init', function () {

    register_taxonomy('loai_dich_vu', 'dich_vu', [
        'labels' => [
            'name'          => 'Loại Dịch Vụ',
            'singular_name' => 'Loại Dịch Vụ',
            'all_items'     => 'Tất cả Loại Dịch Vụ',
            'edit_item'     => 'Sửa Loại Dịch Vụ',
            'add_new_item'  => 'Thêm Loại Dịch Vụ mới',
            'new_item_name' => 'Tên Loại Dịch Vụ',
            'menu_name'     => 'Loại Dịch Vụ',
        ],
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'show_in_rest'      => false,
        'rewrite'           => ['slug' => 'loai-dich-vu', 'with_front' => false],
    ]);

    register_post_type('dich_vu', [
        'labels' => [
            'name'               => 'Dịch Vụ',
            'singular_name'      => 'Dịch Vụ',
            'add_new'            => 'Thêm Dịch Vụ',
            'add_new_item'       => 'Thêm Dịch Vụ mới',
            'edit_item'          => 'Chỉnh sửa Dịch Vụ',
            'new_item'           => 'Dịch Vụ mới',
            'view_item'          => 'Xem Dịch Vụ',
            'search_items'       => 'Tìm Dịch Vụ',
            'not_found'          => 'Không tìm thấy Dịch Vụ nào',
            'not_found_in_trash' => 'Không có Dịch Vụ trong thùng rác',
            'menu_name'          => 'Dịch Vụ',
            'all_items'          => 'Tất cả Dịch Vụ',
        ],
        'public'       => true,
        'has_archive'  => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_position' => 24,
        'menu_icon'    => 'dashicons-heart',
        'supports'     => ['title', 'thumbnail'],
        'rewrite'      => ['slug' => 'dich-vu', 'with_front' => false],
        'show_in_rest' => false,
        'taxonomies'   => ['loai_dich_vu'],
    ]);
}, 0);
