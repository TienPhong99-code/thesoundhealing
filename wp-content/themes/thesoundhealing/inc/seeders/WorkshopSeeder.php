<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    if (get_option('mona_workshop_seeded') || ! function_exists('update_field')) {
        return;
    }

    // ── Tạo taxonomy terms ────────────────────────────────────────────
    $term_am_thanh = term_exists('Workshop Âm Thanh', 'loai_workshop');
    if (! $term_am_thanh) {
        $term_am_thanh = wp_insert_term('Workshop Âm Thanh', 'loai_workshop', ['slug' => 'am-thanh']);
    }
    $tid_am_thanh = is_array($term_am_thanh) ? $term_am_thanh['term_id'] : $term_am_thanh;

    $term_nang_luong = term_exists('Workshop Năng Lượng', 'loai_workshop');
    if (! $term_nang_luong) {
        $term_nang_luong = wp_insert_term('Workshop Năng Lượng', 'loai_workshop', ['slug' => 'nang-luong']);
    }
    $tid_nang_luong = is_array($term_nang_luong) ? $term_nang_luong['term_id'] : $term_nang_luong;

    // ── Import ảnh ────────────────────────────────────────────────────
    if (! function_exists('mona_seed_import_theme_image')) {
        function mona_seed_import_theme_image(string $relative_path): ?int
        {
            $file_path = MONA_THEME_PATH . '/assets/images/' . $relative_path;
            if (! file_exists($file_path)) return null;

            $existing = get_posts([
                'post_type'      => 'attachment',
                'post_status'    => 'any',
                'posts_per_page' => 1,
                'meta_query'     => [['key' => '_mona_seeded_from', 'value' => $relative_path]],
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

    // ── Danh sách workshops ───────────────────────────────────────────
    $workshops = [

        // ─── Workshop Âm Thanh ────────────────────────────────────────
        [
            'term_id'   => $tid_am_thanh,
            'title'     => 'Sound Bath Buổi Tối',
            'thumbnail' => $imgs['img1'],
            'order'     => 1,
            'acf' => [
                'ws_date'       => '18 THÁNG 1, 2025',
                'ws_time'       => '19:00 – 21:00',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Buổi tắm âm thanh thư giãn cuối tuần với bát pha lê và trống Himalaya, dành cho mọi trình độ.',
                'ws_price'      => '850.000 VNĐ',
                'ws_capacity'   => 'Còn 8 chỗ',
                'ws_status'     => 'open',
                'ws_cta_title'  => 'Đặt chỗ ngay hôm nay',
                'ws_cta_desc'   => 'Số lượng chỗ giới hạn 15 người để giữ không gian yên tĩnh và trải nghiệm đủ sâu.',
            ],
        ],
        [
            'term_id'   => $tid_am_thanh,
            'title'     => 'Hòa Âm Gong Thiêng',
            'thumbnail' => $imgs['img2'],
            'order'     => 2,
            'acf' => [
                'ws_date'       => '8 THÁNG 2, 2025',
                'ws_time'       => '18:00 – 20:30',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Đắm chìm trong tần số nguyên thủy của trống gong — hành trình đi sâu vào trạng thái thiền sâu và kết nối nội tâm.',
                'ws_price'      => '1.200.000 VNĐ',
                'ws_capacity'   => 'Còn 5 chỗ',
                'ws_status'     => 'limited',
                'ws_cta_title'  => 'Giữ chỗ trước khi hết',
                'ws_cta_desc'   => 'Chỉ còn 5 chỗ cuối. Thanh toán trước để đảm bảo chỗ ngồi.',
            ],
        ],
        [
            'term_id'   => $tid_am_thanh,
            'title'     => 'Buổi Thiền Âm Thanh Với Đàn Monochord',
            'thumbnail' => $imgs['img3'],
            'order'     => 3,
            'acf' => [
                'ws_date'       => '22 THÁNG 2, 2025',
                'ws_time'       => '09:00 – 11:30',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Trải nghiệm độc đáo với đàn monochord một dây — nhạc cụ thiêng liêng dẫn dắt bạn vào cõi im lặng nội tâm sâu nhất.',
                'ws_price'      => '950.000 VNĐ',
                'ws_capacity'   => 'Sắp mở đăng ký',
                'ws_status'     => 'upcoming',
                'ws_cta_title'  => 'Đặt lịch nhắc nhở',
                'ws_cta_desc'   => 'Đăng ký nhận thông báo khi mở đăng ký chính thức.',
            ],
        ],
        [
            'term_id'   => $tid_am_thanh,
            'title'     => 'Workshop Thanh Âm & Yoga Nidra',
            'thumbnail' => $imgs['img1'],
            'order'     => 4,
            'acf' => [
                'ws_date'       => '1 THÁNG 3, 2025',
                'ws_time'       => '17:00 – 19:30',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Kết hợp hoàn hảo giữa âm thanh chữa lành và Yoga Nidra — kỹ thuật thiền định sâu nhất trong truyền thống Yoga.',
                'ws_price'      => '1.100.000 VNĐ',
                'ws_capacity'   => 'Còn 12 chỗ',
                'ws_status'     => 'open',
                'ws_cta_title'  => 'Tham gia buổi tối đặc biệt',
                'ws_cta_desc'   => 'Số lượng giới hạn 20 người.',
            ],
        ],

        // ─── Workshop Năng Lượng ──────────────────────────────────────
        [
            'term_id'   => $tid_nang_luong,
            'title'     => 'Nhập Môn Reiki — Một Ngày Trải Nghiệm',
            'thumbnail' => $imgs['img2'],
            'order'     => 5,
            'acf' => [
                'ws_date'       => '25 THÁNG 1, 2025',
                'ws_time'       => '09:00 – 17:00',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Trải nghiệm một ngày khám phá năng lượng Reiki cơ bản — phù hợp hoàn toàn cho người chưa có kiến thức trước.',
                'ws_price'      => '1.500.000 VNĐ',
                'ws_capacity'   => 'Còn 4 chỗ',
                'ws_status'     => 'limited',
                'ws_cta_title'  => 'Chỉ còn 4 chỗ cuối',
                'ws_cta_desc'   => 'Lớp nhỏ tối đa 8 người để đảm bảo mỗi học viên được hướng dẫn cá nhân.',
            ],
        ],
        [
            'term_id'   => $tid_nang_luong,
            'title'     => 'Tarot Căn Bản — Đọc Bài Cho Bản Thân',
            'thumbnail' => $imgs['img3'],
            'order'     => 6,
            'acf' => [
                'ws_date'       => '15 THÁNG 2, 2025',
                'ws_time'       => '13:00 – 17:00',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Học cách đọc Tarot như công cụ tự khám phá — hiểu ý nghĩa 22 lá Major Arcana và cách đặt câu hỏi đúng cho bản thân.',
                'ws_price'      => '900.000 VNĐ',
                'ws_capacity'   => 'Còn 10 chỗ',
                'ws_status'     => 'open',
                'ws_cta_title'  => 'Bắt đầu hành trình Tarot',
                'ws_cta_desc'   => 'Không cần kiến thức trước. Mang theo bộ bài Tarot nếu có.',
            ],
        ],
        [
            'term_id'   => $tid_nang_luong,
            'title'     => 'Thiền Năng Lượng & Làm Sạch Luân Xa',
            'thumbnail' => $imgs['img1'],
            'order'     => 7,
            'acf' => [
                'ws_date'       => '8 THÁNG 3, 2025',
                'ws_time'       => '09:30 – 12:30',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Buổi thiền hướng dẫn chuyên sâu về 7 luân xa — học cách tự nhận biết và làm sạch trường năng lượng cá nhân.',
                'ws_price'      => '800.000 VNĐ',
                'ws_capacity'   => 'Sắp mở đăng ký',
                'ws_status'     => 'upcoming',
                'ws_cta_title'  => 'Đăng ký nhận thông báo',
                'ws_cta_desc'   => 'Đăng ký email để được thông báo khi mở đăng ký.',
            ],
        ],
        [
            'term_id'   => $tid_nang_luong,
            'title'     => 'Oracle Cards — Kết Nối Trực Giác',
            'thumbnail' => $imgs['img2'],
            'order'     => 8,
            'acf' => [
                'ws_date'       => '22 THÁNG 3, 2025',
                'ws_time'       => '14:00 – 17:00',
                'ws_location'   => 'Aetheria Studio — 12 Lê Lợi, Quận 1, TP.HCM',
                'ws_short_desc' => 'Khám phá Oracle Cards như ngôn ngữ của linh hồn — cách rút bài hàng ngày, đọc thông điệp và kết nối với trực giác sâu.',
                'ws_price'      => '750.000 VNĐ',
                'ws_capacity'   => 'Còn 15 chỗ',
                'ws_status'     => 'open',
                'ws_cta_title'  => 'Đăng ký tham dự',
                'ws_cta_desc'   => 'Mang theo Oracle deck yêu thích nếu có, hoặc dùng bộ bài của studio.',
            ],
        ],
    ];

    // ── Tạo posts ─────────────────────────────────────────────────────
    foreach ($workshops as $ws) {
        $post_id = wp_insert_post([
            'post_type'   => 'workshop',
            'post_title'  => $ws['title'],
            'post_status' => 'publish',
            'post_author' => 1,
            'menu_order'  => $ws['order'],
        ]);

        if (is_wp_error($post_id) || ! $post_id) continue;

        wp_set_post_terms($post_id, [$ws['term_id']], 'loai_workshop');

        if (! empty($ws['thumbnail'])) {
            set_post_thumbnail($post_id, $ws['thumbnail']);
        }

        foreach ($ws['acf'] as $key => $value) {
            update_field($key, $value, $post_id);
        }
    }

    update_option('mona_workshop_seeded', true);
}, 20);
