<?php
defined('ABSPATH') || exit;

add_action('init', function () {

    // Taxonomy: Danh mục tuyển dụng
    register_taxonomy('danh_muc_tuyen_dung', 'tuyen_dung', [
        'labels' => [
            'name'                       => 'Danh mục tuyển dụng',
            'singular_name'              => 'Danh mục',
            'menu_name'                  => 'Danh mục',
            'all_items'                  => 'Tất cả danh mục',
            'add_new_item'               => 'Thêm danh mục mới',
            'new_item_name'              => 'Tên danh mục mới',
            'edit_item'                  => 'Chỉnh sửa danh mục',
            'update_item'                => 'Cập nhật danh mục',
            'search_items'               => 'Tìm danh mục',
            'parent_item'                => 'Danh mục cha',
            'parent_item_colon'          => 'Danh mục cha:',
            'separate_items_with_commas' => 'Phân cách bằng dấu phẩy',
            'add_or_remove_items'        => 'Thêm hoặc xoá danh mục',
            'choose_from_most_used'      => 'Chọn từ danh mục phổ biến',
            'not_found'                  => 'Không tìm thấy danh mục',
            'back_to_items'              => '← Về danh sách danh mục',
        ],
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => ['slug' => 'tuyen-dung-danh-muc', 'with_front' => false],
        'show_in_rest'      => false,
    ]);

    // CPT: Tuyển dụng
    register_post_type('tuyen_dung', [
        'labels' => [
            'name'               => 'Tuyển dụng',
            'singular_name'      => 'Vị trí tuyển dụng',
            'add_new'            => 'Thêm vị trí',
            'add_new_item'       => 'Thêm vị trí mới',
            'edit_item'          => 'Chỉnh sửa vị trí',
            'not_found'          => 'Không tìm thấy vị trí nào',
            'menu_name'          => 'Tuyển dụng',
        ],
        'public'             => true,
        'has_archive'        => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-businessman',
        'supports'           => ['title', 'thumbnail'],
        'taxonomies'         => ['danh_muc_tuyen_dung'],
        'rewrite'            => ['slug' => 'tuyen-dung', 'with_front' => false],
        'show_in_rest'       => false,
    ]);
});
