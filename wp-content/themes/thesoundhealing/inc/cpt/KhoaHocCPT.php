<?php
defined('ABSPATH') || exit;

add_action('init', function () {

    register_taxonomy('bo_mon_khoa_hoc', 'khoa_hoc', [
        'labels' => [
            'name'          => 'Bộ Môn',
            'singular_name' => 'Bộ Môn',
            'all_items'     => 'Tất cả Bộ Môn',
            'edit_item'     => 'Sửa Bộ Môn',
            'add_new_item'  => 'Thêm Bộ Môn mới',
            'new_item_name' => 'Tên Bộ Môn',
            'menu_name'     => 'Bộ Môn',
        ],
        'public'       => true,
        'hierarchical' => true,
        'show_ui'      => true,
        'show_in_menu' => true,
        'show_in_rest' => false,
        'rewrite'      => ['slug' => 'bo-mon-khoa-hoc', 'with_front' => false],
    ]);

    register_post_type('khoa_hoc', [
        'labels' => [
            'name'               => 'Khóa học',
            'singular_name'      => 'Khóa học',
            'add_new'            => 'Thêm khóa học',
            'add_new_item'       => 'Thêm khóa học mới',
            'edit_item'          => 'Chỉnh sửa khóa học',
            'new_item'           => 'Khóa học mới',
            'view_item'          => 'Xem khóa học',
            'search_items'       => 'Tìm khóa học',
            'not_found'          => 'Không tìm thấy khóa học nào',
            'not_found_in_trash' => 'Không có khóa học trong thùng rác',
            'menu_name'          => 'Khóa học',
            'all_items'          => 'Tất cả khóa học',
        ],
        'public'             => true,
        'has_archive'        => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 22,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'supports'           => ['title', 'thumbnail'],
        'rewrite'            => ['slug' => 'khoa-hoc', 'with_front' => false],
        'show_in_rest'       => false,
    ]);
}, 0);
