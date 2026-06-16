<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    if (get_option('mona_usui_reiki_seeded_v1') || ! function_exists('update_field')) {
        return;
    }

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

    // ── Import ảnh ───────────────────────────────────────────────────────────
    $imgs = [
        'thumb'      => mona_seed_import_theme_image('dv-chua-lanh-reiki-rieng-tu.jpg'),
        'exp_main'   => mona_seed_import_theme_image('dv-exp-main.jpg'),
        'exp_detail' => mona_seed_import_theme_image('dv-exp-detail.jpg'),
        'instructor' => mona_seed_import_theme_image('kh-instructor.jpg'),
    ];

    // ── Ensure term usui-reiki ────────────────────────────────────────────────
    $term_result = wp_insert_term('Usui Reiki', 'loai_dich_vu', ['slug' => 'usui-reiki']);
    $term_id     = is_wp_error($term_result)
        ? (get_term_by('slug', 'usui-reiki', 'loai_dich_vu')->term_id ?? null)
        : $term_result['term_id'];

    // ── Dữ liệu card ─────────────────────────────────────────────────────────
    $services = [
        [
            'title'     => 'Chữa Lành Usui Reiki (Nhóm)',
            'thumbnail' => 'dv-chua-lanh-reiki-nhom.jpg',
            'order'     => 10,
            'fields'    => [
                'dv_format'          => 'Onsite',
                'dv_duration'        => '60 – 90 phút mỗi phiên',
                'dv_location'        => "Aetheria Sanctuary, Level 4, Thảo Điền",
                'dv_available_days'  => 'Thứ 2 – Chủ nhật',
                'dv_branch'          => 'Thảo Điền',
                'dv_spots'           => 8,
                'dv_short_desc'      => 'Buổi chữa lành Usui Reiki theo nhóm nhỏ — nơi năng lượng vũ trụ được dẫn dắt qua đôi tay của healer, giúp cân bằng các luân xa, giải phóng tắc nghẽn và phục hồi dòng chảy năng lượng tự nhiên.',
                'dv_price'           => '700.000 VNĐ',
                'dv_status'          => 'open',
                'dv_best_seller'     => 0,
                'dv_description'     => 'Usui Reiki là phương pháp chữa lành bằng năng lượng vũ trụ, giúp cân bằng thể chất, cảm xúc và tinh thần thông qua việc truyền năng lượng qua các luân xa.',
                'dv_exp_title'       => 'Hành trình Trải nghiệm',
                'dv_exp_desc'        => "Buổi Reiki nhóm bắt đầu bằng nghi lễ nối đất và thiền ngắn giúp mọi người sẵn sàng tiếp nhận năng lượng. Healer sẽ lần lượt làm việc với từng thành viên, đặt tay nhẹ nhàng lên các điểm năng lượng chính.\n\nTrong không gian tĩnh lặng với nhạc tần số 432Hz, cơ thể bạn sẽ tự nhiên buông thả và bắt đầu quá trình tự chữa lành.",
                'dv_exp_image_1'     => $imgs['exp_main'],
                'dv_exp_image_2'     => $imgs['exp_detail'],
                'dv_benefits_heading' => 'Lợi ích của Usui Reiki',
                'dv_benefits_items'  => [
                    ['dv_benefit_title' => 'CÂN BẰNG LUÂN XA',        'dv_benefit_desc' => 'Khai thông và cân bằng 7 luân xa chính, giúp năng lượng lưu thông tự do trong cơ thể.'],
                    ['dv_benefit_title' => 'GIẢM CĂNG THẲNG SÂU',      'dv_benefit_desc' => 'Kích hoạt hệ thần kinh phó giao cảm, mang lại trạng thái thư giãn sâu và bình an.'],
                    ['dv_benefit_title' => 'TĂNG CƯỜNG MIỄN DỊCH',     'dv_benefit_desc' => 'Năng lượng Reiki hỗ trợ hệ miễn dịch tự nhiên và đẩy nhanh quá trình phục hồi của cơ thể.'],
                ],
                'dv_instructor_label'   => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image'   => $imgs['instructor'],
                'dv_instructor_name'    => 'Linh Tâm',
                'dv_instructor_bio'     => 'Reiki Master cấp độ III với hơn 8 năm thực hành và truyền năng lượng. Linh Tâm được chứng nhận theo dòng Usui Shiki Ryoho và đã đồng hành cùng hơn 600 khách hàng trong hành trình chữa lành.',
                'dv_cta_title'          => 'Bắt đầu hành trình chữa lành',
                'dv_cta_desc'           => 'Mỗi buổi Reiki là một bước tiến trên hành trình khám phá và phục hồi bản thân. Đặt lịch ngay để giữ chỗ.',
            ],
        ],

        [
            'title'     => 'Chữa Lành Usui Reiki (Riêng Tư)',
            'thumbnail' => 'dv-chua-lanh-reiki-rieng-tu.jpg',
            'order'     => 11,
            'fields'    => [
                'dv_format'          => 'Onsite',
                'dv_duration'        => '75 – 90 phút mỗi phiên',
                'dv_location'        => "Aetheria Sanctuary, Level 4, Thảo Điền",
                'dv_available_days'  => 'Thứ 2 – Chủ nhật',
                'dv_branch'          => 'Thảo Điền',
                'dv_spots'           => 1,
                'dv_short_desc'      => 'Phiên Reiki cá nhân hoá hoàn toàn theo nhu cầu và trạng thái của bạn. Healer sẽ dành toàn bộ sự tập trung để khai thông từng luân xa và giải phóng các tắc nghẽn năng lượng sâu nhất.',
                'dv_price'           => '1.200.000 VNĐ',
                'dv_status'          => 'open',
                'dv_best_seller'     => 1,
                'dv_description'     => 'Phiên Usui Reiki riêng tư — trải nghiệm chữa lành sâu và cá nhân hoá nhất với không gian hoàn toàn chỉ dành cho bạn.',
                'dv_exp_title'       => 'Trải nghiệm cá nhân hoá sâu sắc',
                'dv_exp_desc'        => "Trước buổi, healer và bạn sẽ có 15 phút trò chuyện để xác định các vùng cần chú ý — thể chất, cảm xúc hay tinh thần. Từ đó, phiên Reiki được thiết kế riêng.\n\nTrong suốt buổi, healer dành toàn bộ sự hiện diện để cảm nhận và điều hướng năng lượng, không bị phân tâm bởi bất kỳ ai khác.",
                'dv_exp_image_1'     => $imgs['exp_main'],
                'dv_exp_image_2'     => $imgs['exp_detail'],
                'dv_benefits_heading' => 'Lợi ích của phiên riêng tư',
                'dv_benefits_items'  => [
                    ['dv_benefit_title' => 'CHỮA LÀNH THEO TẦNg SÂU',   'dv_benefit_desc' => 'Healer có thể tập trung vào các luân xa cụ thể cần khai thông, mang lại hiệu quả sâu hơn.'],
                    ['dv_benefit_title' => 'AN TOÀN & RIÊNG TƯ TUYỆT ĐỐI', 'dv_benefit_desc' => 'Không gian 1-1 giúp bạn hoàn toàn buông thả mà không lo ngại sự hiện diện của người khác.'],
                    ['dv_benefit_title' => 'THEO DÕI TIẾN TRÌNH',        'dv_benefit_desc' => 'Healer ghi chép và theo dõi sự thay đổi qua từng buổi để điều chỉnh liệu trình phù hợp.'],
                ],
                'dv_instructor_label'   => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image'   => $imgs['instructor'],
                'dv_instructor_name'    => 'Linh Tâm',
                'dv_instructor_bio'     => 'Reiki Master cấp độ III với hơn 8 năm thực hành. Chuyên sâu về phiên Reiki cá nhân hoá và đã hỗ trợ hàng trăm khách hàng vượt qua các tắc nghẽn năng lượng mãn tính.',
                'dv_cta_title'          => 'Không gian chỉ dành cho bạn',
                'dv_cta_desc'           => 'Phiên Reiki riêng tư là món quà chữa lành sâu sắc nhất bạn có thể dành cho chính mình. Đặt lịch sớm — số lượng phiên trong tuần có giới hạn.',
            ],
        ],
    ];

    // ── Tạo posts ─────────────────────────────────────────────────────────────
    foreach ($services as $service) {
        $existing = get_posts([
            'post_type'      => 'dich_vu',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'title'          => $service['title'],
        ]);
        if (! empty($existing)) continue;

        $thumb_id = mona_seed_import_theme_image($service['thumbnail']);

        $post_id = wp_insert_post([
            'post_title'   => $service['title'],
            'post_type'    => 'dich_vu',
            'post_status'  => 'publish',
            'menu_order'   => $service['order'],
            'post_content' => '',
        ]);

        if (is_wp_error($post_id) || ! $post_id) continue;

        if ($thumb_id) set_post_thumbnail($post_id, $thumb_id);
        if ($term_id)  wp_set_post_terms($post_id, [$term_id], 'loai_dich_vu');

        foreach ($service['fields'] as $key => $value) {
            update_field($key, $value, $post_id);
        }
    }

    update_option('mona_usui_reiki_seeded_v1', true);
}, 20);
