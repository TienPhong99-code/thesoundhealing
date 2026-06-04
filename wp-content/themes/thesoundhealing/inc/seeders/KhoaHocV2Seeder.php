<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    if (get_option('mona_khoa_hoc_seeded_v2') || ! function_exists('update_field')) {
        return;
    }

    // ── Tạo taxonomy terms ────────────────────────────────────────────
    $term_am_thanh = term_exists('Bộ Môn Âm Thanh', 'bo_mon_khoa_hoc');
    if (! $term_am_thanh) {
        $term_am_thanh = wp_insert_term('Bộ Môn Âm Thanh', 'bo_mon_khoa_hoc', ['slug' => 'am-thanh']);
    }
    $term_id_am_thanh = is_array($term_am_thanh) ? $term_am_thanh['term_id'] : $term_am_thanh;

    $term_nang_luong = term_exists('Bộ Môn Năng Lượng', 'bo_mon_khoa_hoc');
    if (! $term_nang_luong) {
        $term_nang_luong = wp_insert_term('Bộ Môn Năng Lượng', 'bo_mon_khoa_hoc', ['slug' => 'nang-luong']);
    }
    $term_id_nang_luong = is_array($term_nang_luong) ? $term_nang_luong['term_id'] : $term_nang_luong;

    // ── Import ảnh vào media library ──────────────────────────────────
    // Dùng lại helper từ KhoaHocSeeder nếu đã load, không thì khai báo local
    if (! function_exists('mona_seed_import_theme_image')) {
        function mona_seed_import_theme_image(string $relative_path): ?int
        {
            $file_path = MONA_THEME_PATH . '/assets/images/' . $relative_path;
            if (! file_exists($file_path)) return null;

            $existing = get_posts([
                'post_type'      => 'attachment',
                'post_status'    => 'any',
                'posts_per_page' => 1,
                'meta_query'     => [[
                    'key'   => '_mona_seeded_from',
                    'value' => $relative_path,
                ]],
            ]);
            if (! empty($existing)) return $existing[0]->ID;

            $upload_dir = wp_upload_dir();
            $filename   = basename($file_path);
            $dest_path  = $upload_dir['path'] . '/' . wp_unique_filename($upload_dir['path'], $filename);
            if (! copy($file_path, $dest_path)) return null;

            $file_type = wp_check_filetype($dest_path);
            $attach_id = wp_insert_attachment([
                'post_mime_type' => $file_type['type'],
                'post_title'     => pathinfo($filename, PATHINFO_FILENAME),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ], $dest_path);

            if (is_wp_error($attach_id)) return null;

            require_once ABSPATH . 'wp-admin/includes/image.php';
            wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $dest_path));
            update_post_meta($attach_id, '_mona_seeded_from', $relative_path);

            return $attach_id;
        }
    }

    $imgs = [
        'img1' => mona_seed_import_theme_image('courses-img-1.png'),
        'img2' => mona_seed_import_theme_image('courses-img-2.png'),
        'img3' => mona_seed_import_theme_image('courses-img-3.png'),
    ];

    // ── Danh sách 10 khóa học ─────────────────────────────────────────
    $courses = [

        // ─── A. BỘ MÔN ÂM THANH ──────────────────────────────────────
        [
            'term_id'   => $term_id_am_thanh,
            'title'     => 'Hoà Âm 7 Chuông Pha Lê',
            'thumbnail' => $imgs['img1'],
            'order'     => 1,
            'acf' => [
                'level'      => 'FOUNDATION',
                'duration'   => '4 TUẦN',
                'short_desc' => 'Nắm vững kỹ thuật chơi và hoà âm 7 luân xa với chuông pha lê, mang lại sự cân bằng sâu sắc cho cơ thể và tâm trí.',
                'price'      => '12.000.000 VNĐ',
                'cta_title'  => 'Bắt đầu hành trình của bạn',
                'cta_desc'   => 'Lớp học giới hạn số lượng học viên để đảm bảo chất lượng hướng dẫn tốt nhất.',
            ],
        ],
        [
            'term_id'   => $term_id_am_thanh,
            'title'     => 'Liệu Pháp Chuông Đồng',
            'thumbnail' => $imgs['img2'],
            'order'     => 2,
            'acf' => [
                'level'      => 'MASTERY',
                'duration'   => '3 TUẦN',
                'short_desc' => 'Khám phá nghệ thuật chữa lành cổ xưa qua rung động vật lý của chuông đồng nguyên bản Himalaya.',
                'price'      => '15.000.000 VNĐ',
                'cta_title'  => 'Bắt đầu hành trình của bạn',
                'cta_desc'   => 'Lớp học giới hạn số lượng học viên để đảm bảo chất lượng hướng dẫn tốt nhất.',
            ],
        ],
        [
            'term_id'   => $term_id_am_thanh,
            'title'     => 'Liệu Pháp Sound Bath Tắm Âm Chữa Lành',
            'thumbnail' => $imgs['img3'],
            'order'     => 3,
            'acf' => [
                'level'      => 'ADVANCED',
                'duration'   => '2 GIỜ',
                'short_desc' => 'Đào tạo chuyên sâu kỹ năng tổ chức và dẫn dắt các buổi tắm âm thanh trị liệu chuyên nghiệp.',
                'price'      => '20.000.000 VNĐ',
                'cta_title'  => 'Cho bản thân một khoảng lặng',
                'cta_desc'   => 'Số lượng tham dự giới hạn để đảm bảo không gian yên tĩnh và trải nghiệm đủ sâu.',
            ],
        ],
        [
            'term_id'   => $term_id_am_thanh,
            'title'     => 'Khoá Học Giọng Nói',
            'thumbnail' => $imgs['img1'],
            'order'     => 4,
            'acf' => [
                'level'      => 'MỌI TRÌNH ĐỘ',
                'duration'   => '2 TUẦN',
                'short_desc' => 'Khám phá sức mạnh của giọng nói như một công cụ chữa lành — cân bằng năng lượng và kết nối nội tâm qua âm thanh của chính bạn.',
                'price'      => 'Liên hệ',
                'cta_title'  => 'Tìm lại giọng nói của bạn',
                'cta_desc'   => 'Lớp học nhỏ để đảm bảo mỗi học viên được hướng dẫn cá nhân hoá.',
            ],
        ],
        [
            'term_id'   => $term_id_am_thanh,
            'title'     => 'Goonges — Thanh Âm Từ Đất Mẹ',
            'thumbnail' => $imgs['img2'],
            'order'     => 5,
            'acf' => [
                'level'      => 'CHUYÊN SÂU',
                'duration'   => '2 NGÀY',
                'short_desc' => 'Đắm chìm vào tần số cổ nguyên của trống gong — nhạc cụ linh thiêng được xem là cổng kết nối giữa con người và vũ trụ.',
                'price'      => 'Liên hệ',
                'cta_title'  => 'Nghe tiếng gọi từ đất mẹ',
                'cta_desc'   => 'Số lượng học viên giới hạn để giữ năng lượng không gian thuần khiết.',
            ],
        ],

        // ─── B. BỘ MÔN NĂNG LƯỢNG ────────────────────────────────────
        [
            'term_id'   => $term_id_nang_luong,
            'title'     => 'Năng Lượng Usui Reiki Level 1',
            'thumbnail' => $imgs['img3'],
            'order'     => 6,
            'acf' => [
                'level'      => 'CẤP ĐỘ 1',
                'duration'   => 'CUỐI TUẦN',
                'short_desc' => 'Khởi đầu hành trình chữa lành bằng năng lượng vũ trụ. Học cách tự trị liệu và cân bằng các luân xa cơ bản qua bàn tay.',
                'price'      => '3.800.000 VNĐ',
                'cta_title'  => 'Mở ra cánh cửa chữa lành',
                'cta_desc'   => 'Lớp học nhỏ, tối đa 8 học viên để đảm bảo mỗi người được khai mở đúng cách.',
            ],
        ],
        [
            'term_id'   => $term_id_nang_luong,
            'title'     => 'Năng Lượng Usui Reiki Level 2',
            'thumbnail' => $imgs['img1'],
            'order'     => 7,
            'acf' => [
                'level'      => 'CẤP ĐỘ 2',
                'duration'   => '2 NGÀY',
                'short_desc' => 'Nâng cao khả năng chữa lành — học cách sử dụng ký hiệu Reiki và trị liệu từ xa cho người thân không cùng không gian.',
                'price'      => '5.500.000 VNĐ',
                'cta_title'  => 'Tiếp tục hành trình Reiki',
                'cta_desc'   => 'Yêu cầu đã hoàn thành Reiki Level 1.',
            ],
        ],
        [
            'term_id'   => $term_id_nang_luong,
            'title'     => 'Năng Lượng Usui Reiki Level 3 — Master Teacher',
            'thumbnail' => $imgs['img2'],
            'order'     => 8,
            'acf' => [
                'level'      => 'MASTER TEACHER',
                'duration'   => '3 NGÀY',
                'short_desc' => 'Nhận attunement Master — đạt đến cấp độ cao nhất của Reiki truyền thống và học cách khai mở năng lượng cho học viên mới.',
                'price'      => '12.000.000 VNĐ',
                'cta_title'  => 'Trở thành Master Teacher',
                'cta_desc'   => 'Yêu cầu đã hoàn thành Reiki Level 2. Phỏng vấn trước khi nhận lớp.',
            ],
        ],
        [
            'term_id'   => $term_id_nang_luong,
            'title'     => 'Tarot & Oracles Soul Mirror',
            'thumbnail' => $imgs['img3'],
            'order'     => 9,
            'acf' => [
                'level'      => 'MỌI TRÌNH ĐỘ',
                'duration'   => '3 TUẦN',
                'short_desc' => 'Học cách dùng Tarot và Oracle như một tấm gương tâm hồn — công cụ tự khám phá nội tâm, không phải bói toán.',
                'price'      => 'Liên hệ',
                'cta_title'  => 'Đối diện với tâm hồn bạn',
                'cta_desc'   => 'Lớp học dành cho người muốn khai phá nội tâm qua ngôn ngữ biểu tượng.',
            ],
        ],
        [
            'term_id'   => $term_id_nang_luong,
            'title'     => 'Tàng Thư Vũ Trụ — Past Lives Journey Records',
            'thumbnail' => $imgs['img1'],
            'order'     => 10,
            'acf' => [
                'level'      => 'NÂNG CAO',
                'duration'   => '2 NGÀY',
                'short_desc' => 'Hành trình khám phá ký ức tiền kiếp — giải phóng các mẫu năng lượng cũ và tìm lại sứ mệnh linh hồn của bạn.',
                'price'      => 'Liên hệ',
                'cta_title'  => 'Khám phá hành trình linh hồn',
                'cta_desc'   => 'Khoá học chuyên sâu, giới hạn 6 học viên để đảm bảo không gian an toàn và cá nhân hoá.',
            ],
        ],
    ];

    // ── Tạo posts + gán taxonomy + set ACF fields ─────────────────────
    $menu_order = 0;
    foreach ($courses as $course) {
        $post_id = wp_insert_post([
            'post_type'   => 'khoa_hoc',
            'post_title'  => $course['title'],
            'post_status' => 'publish',
            'post_author' => 1,
            'menu_order'  => $menu_order++,
        ]);

        if (is_wp_error($post_id) || ! $post_id) continue;

        // Taxonomy
        wp_set_post_terms($post_id, [$course['term_id']], 'bo_mon_khoa_hoc');

        // Featured image
        if (! empty($course['thumbnail'])) {
            set_post_thumbnail($post_id, $course['thumbnail']);
        }

        // ACF fields
        foreach ($course['acf'] as $key => $value) {
            update_field($key, $value, $post_id);
        }
    }

    update_option('mona_khoa_hoc_seeded_v2', true);
}, 20);
