<?php
defined('ABSPATH') || exit;

/**
 * Custom Post Type: Dự án
 * Taxonomy: Danh mục dự án (Giáo dục / Dịch vụ / Xây dựng)
 */

add_action('init', function () {

    // ──────────────────────────────────────
    // Taxonomy: Danh mục dự án
    // ──────────────────────────────────────
    register_taxonomy('danh_muc_du_an', 'du_an', [
        'labels' => [
            'name'              => 'Danh mục dự án',
            'singular_name'     => 'Danh mục',
            'search_items'      => 'Tìm danh mục',
            'all_items'         => 'Tất cả danh mục',
            'edit_item'         => 'Sửa danh mục',
            'update_item'       => 'Cập nhật danh mục',
            'add_new_item'      => 'Thêm danh mục mới',
            'new_item_name'     => 'Tên danh mục mới',
            'menu_name'         => 'Danh mục',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'du-an', 'with_front' => false],
        'show_in_rest'      => false,
    ]);

    // ──────────────────────────────────────
    // CPT: Dự án
    // ──────────────────────────────────────
    register_post_type('du_an', [
        'labels' => [
            'name'               => 'Dự án',
            'singular_name'      => 'Dự án',
            'add_new'            => 'Thêm dự án',
            'add_new_item'       => 'Thêm dự án mới',
            'edit_item'          => 'Chỉnh sửa dự án',
            'new_item'           => 'Dự án mới',
            'view_item'          => 'Xem dự án',
            'search_items'       => 'Tìm dự án',
            'not_found'          => 'Không tìm thấy dự án nào',
            'not_found_in_trash' => 'Không có dự án trong thùng rác',
            'menu_name'          => 'Dự án',
            'all_items'          => 'Tất cả dự án',
        ],
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => ['title', 'thumbnail'],
        'has_archive'        => false,
        'rewrite'            => false,
        'show_in_rest'       => false,
        'taxonomies'         => ['danh_muc_du_an'],
    ]);
}, 0);
