<?php
defined('ABSPATH') || exit;

if (! function_exists('mona_seed_import_theme_image')) {
    function mona_seed_import_theme_image(string $relative_path): ?int
    {
        $file_path = MONA_THEME_PATH . '/assets/images/' . $relative_path;
        if (! file_exists($file_path)) {
            return null;
        }

        $filename = basename($file_path);

        $existing = get_posts([
            'post_type'      => 'attachment',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'meta_query'     => [[
                'key'   => '_mona_seeded_from',
                'value' => $relative_path,
            ]],
        ]);

        if (! empty($existing)) {
            return $existing[0]->ID;
        }

        $upload_dir = wp_upload_dir();
        $dest_path  = $upload_dir['path'] . '/' . wp_unique_filename($upload_dir['path'], $filename);

        if (! copy($file_path, $dest_path)) {
            return null;
        }

        $file_type = wp_check_filetype($dest_path);
        $attach_id = wp_insert_attachment([
            'post_mime_type' => $file_type['type'],
            'post_title'     => pathinfo($filename, PATHINFO_FILENAME),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ], $dest_path);

        if (is_wp_error($attach_id)) {
            return null;
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $dest_path));
        update_post_meta($attach_id, '_mona_seeded_from', $relative_path);

        return $attach_id;
    }
}

add_action('init', function () {
    if (get_option('mona_khoa_hoc_seeded') || ! function_exists('update_field')) {
        return;
    }

    // ── Import ảnh vào media library ──────────────────────────────────
    $imgs = [
        'card_1'    => mona_seed_import_theme_image('courses-img-1.png'),
        'card_2'    => mona_seed_import_theme_image('courses-img-2.png'),
        'card_3'    => mona_seed_import_theme_image('courses-img-3.png'),
        'hero'      => mona_seed_import_theme_image('kh-hero.jpg'),
        'exp_1'     => mona_seed_import_theme_image('kh-exp-1.jpg'),
        'exp_2'     => mona_seed_import_theme_image('kh-exp-2.jpg'),
        'instructor' => mona_seed_import_theme_image('kh-instructor.jpg'),
    ];

    // ── Dữ liệu mẫu 3 khóa học ────────────────────────────────────────
    $courses = [
        [
            'title'     => 'Nghệ Thuật Bát Hát Pha Lê',
            'thumbnail' => $imgs['hero'],
            'acf' => [
                'level'           => 'KHOÁ HỌC CHUYÊN SÂU',
                'duration'        => '4 TUẦN',
                'short_desc'      => 'Khám phá sức mạnh của tần số rung động và cách sử dụng bát hát pha lê để tái tạo năng lượng và cân bằng các luân xa.',
                'price'           => '8.500.000 VNĐ',
                'exp_title'       => 'Không gian của sự tĩnh lặng',
                'exp_desc'        => "Khoá học không chỉ là việc truyền đạt kỹ thuật gõ chuông, mà là một hành trình đi sâu vào nội tâm. Mỗi buổi học là một không gian an toàn để bạn lắng nghe sự im lặng giữa những âm thanh.\n\nBạn sẽ học cách cảm nhận âm thanh không chỉ bằng tai, mà bằng toàn bộ cơ thể vật lý và trường năng lượng bao quanh.",
                'exp_image_1'     => $imgs['exp_1'],
                'exp_image_2'     => $imgs['exp_2'],
                'roadmap_label'   => 'LỘ TRÌNH HỌC',
                'roadmap_heading' => 'Hành trình 4 tuần',
                'roadmap_items'   => [
                    [
                        'week_title' => 'Tuần 1: Căn nguyên của âm thanh',
                        'week_desc'  => 'Hiểu về cơ chế hoạt động của tần số và tác động của nó lên não bộ, tế bào. Làm quen với 7 nốt nhạc tương ứng với 7 luân xa chính.',
                        'week_tags'  => 'Lý thuyết tần số, Hệ thống luân xa',
                    ],
                    [
                        'week_title' => 'Tuần 2: Kỹ thuật gõ và xoay cơ bản',
                        'week_desc'  => 'Thực hành tư thế ngồi, cách cầm vùi (mallet), kiểm soát lực tay để tạo ra âm thanh trong trẻo. Học cách tạo và duy trì độ rung kéo dài.',
                        'week_tags'  => 'Thực hành kỹ năng, Kiểm soát lực',
                    ],
                    [
                        'week_title' => 'Tuần 3: Hoà âm đa tầng',
                        'week_desc'  => 'Nghệ thuật kết hợp 2, 3 và nhiều chuông cùng lúc để tạo ra những hợp âm chữa lành. Cách dẫn dắt người nghe đi vào trạng thái sóng não Theta sâu.',
                        'week_tags'  => 'Sáng tác hợp âm, Sóng não Theta',
                    ],
                    [
                        'week_title' => 'Tuần 4: Thiết kế buổi Sound Bath',
                        'week_desc'  => 'Hoàn thiện kỹ năng và học cách thiết kế một buổi tắm âm thanh hoàn chỉnh: từ khâu set up không gian, thanh tẩy, đến kịch bản dẫn thiền và nhịp độ âm thanh.',
                        'week_tags'  => 'Set up không gian, Dẫn thiền',
                    ],
                ],
                'instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'instructor_image' => $imgs['instructor'],
                'instructor_name' => 'Elena Vu',
                'instructor_bio'  => 'Master Sound Healer với hơn 10 năm kinh nghiệm tu tập và giảng dạy. Elena mang đến phương pháp tiếp cận cân bằng giữa khoa học năng lượng và tính linh thiêng cổ xưa.',
                'benefits_heading' => 'Bạn sẽ nhận được gì?',
                'benefits_items'  => [
                    [
                        'benefit_title' => 'Kỹ thuật chuyên sâu',
                        'benefit_desc'  => 'Làm chủ hoàn toàn 7 chuông pha lê, tự tin biểu diễn và trị liệu cá nhân.',
                    ],
                    [
                        'benefit_title' => 'Sự nhạy bén năng lượng',
                        'benefit_desc'  => 'Tăng cường trực giác và khả năng đọc, điều chỉnh trường năng lượng của không gian.',
                    ],
                    [
                        'benefit_title' => 'Chứng nhận hoàn thành',
                        'benefit_desc'  => 'Nhận chứng nhận hoàn thành khoá học từ AETHERIA, mở ra cơ hội trở thành Sound Healer chuyên nghiệp.',
                    ],
                ],
                'cta_title' => 'Bắt đầu hành trình của bạn',
                'cta_desc'  => 'Lớp học giới hạn số lượng học viên để đảm bảo chất lượng hướng dẫn tốt nhất. Vui lòng đăng ký sớm để giữ chỗ.',
            ],
        ],
        [
            'title'     => 'Chứng Nhận Reiki Shoden',
            'thumbnail' => $imgs['card_2'],
            'acf' => [
                'level'           => 'CẤP ĐỘ 1',
                'duration'        => 'CUỐI TUẦN',
                'short_desc'      => 'Khởi đầu hành trình chữa lành bằng năng lượng vũ trụ. Học cách tự trị liệu và cân bằng các luân xa cơ bản.',
                'price'           => '3.800.000 VNĐ',
                'exp_title'       => 'Chữa lành từ bên trong',
                'exp_desc'        => "Reiki Shoden là cấp độ đầu tiên trong hệ thống Reiki truyền thống Nhật Bản. Bạn sẽ được khai mở năng lượng (attunement) và học cách chuyển hóa năng lượng vũ trụ qua bàn tay.\n\nKhóa học phù hợp với người mới bắt đầu, không yêu cầu kinh nghiệm trước.",
                'exp_image_1'     => $imgs['exp_1'],
                'exp_image_2'     => $imgs['exp_2'],
                'roadmap_label'   => 'LỘ TRÌNH HỌC',
                'roadmap_heading' => 'Hành trình 2 ngày cuối tuần',
                'roadmap_items'   => [
                    [
                        'week_title' => 'Ngày 1: Nền tảng Reiki',
                        'week_desc'  => 'Lịch sử và triết học Reiki. Buổi khai mở năng lượng (attunement) cấp 1. Thực hành self-healing — tự chữa lành cho bản thân.',
                        'week_tags'  => 'Lý thuyết Reiki, Attunement',
                    ],
                    [
                        'week_title' => 'Ngày 2: Thực hành trị liệu',
                        'week_desc'  => 'Kỹ thuật đặt tay trên 12 vị trí cơ bản. Thực hành trị liệu cho người khác theo cặp. Cách bảo vệ năng lượng cá nhân.',
                        'week_tags'  => 'Kỹ thuật đặt tay, Bảo vệ năng lượng',
                    ],
                ],
                'instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'instructor_image' => $imgs['instructor'],
                'instructor_name' => 'Elena Vu',
                'instructor_bio'  => 'Master Sound Healer và Reiki Master Teacher được chứng nhận quốc tế. Hơn 10 năm kinh nghiệm đào tạo và trị liệu.',
                'benefits_heading' => 'Bạn sẽ nhận được gì?',
                'benefits_items'  => [
                    [
                        'benefit_title' => 'Khai mở năng lượng',
                        'benefit_desc'  => 'Nhận attunement chính thức, kích hoạt khả năng truyền năng lượng Reiki.',
                    ],
                    [
                        'benefit_title' => 'Kỹ năng tự chữa lành',
                        'benefit_desc'  => 'Biết cách thực hành self-healing hàng ngày để cân bằng thân — tâm — trí.',
                    ],
                    [
                        'benefit_title' => 'Chứng nhận Reiki Level 1',
                        'benefit_desc'  => 'Nhận chứng chỉ Reiki Shoden được công nhận trong hệ thống AETHERIA.',
                    ],
                ],
                'cta_title' => 'Mở ra cánh cửa chữa lành',
                'cta_desc'  => 'Lớp học nhỏ, tối đa 8 học viên để đảm bảo mỗi người được hướng dẫn trực tiếp và khai mở đúng cách.',
            ],
        ],
        [
            'title'     => 'Tắm Âm Thanh Chữa Lành',
            'thumbnail' => $imgs['card_3'],
            'acf' => [
                'level'           => 'MỌI TRÌNH ĐỘ',
                'duration'        => '2 GIỜ',
                'short_desc'      => 'Đắm chìm trong sóng âm đa tầng để giải tỏa căng thẳng sâu sắc và đưa não bộ vào trạng thái thiền định sâu.',
                'price'           => '850.000 VNĐ',
                'exp_title'       => 'Để âm thanh dẫn lối',
                'exp_desc'        => "Sound Bath là trải nghiệm âm nhạc trị liệu thụ động — bạn chỉ cần nằm xuống, nhắm mắt và để những tần số chữa lành bao quanh toàn thân.\n\nPhù hợp với mọi người, không cần kinh nghiệm thiền định hay âm nhạc.",
                'exp_image_1'     => $imgs['exp_1'],
                'exp_image_2'     => $imgs['exp_2'],
                'roadmap_label'   => 'CHƯƠNG TRÌNH',
                'roadmap_heading' => 'Một buổi tối 2 tiếng',
                'roadmap_items'   => [
                    [
                        'week_title' => '20 phút: Khai mở & hít thở',
                        'week_desc'  => 'Hướng dẫn hít thở có ý thức và thiết lập ý định. Giải phóng căng thẳng tích lũy trong ngày.',
                        'week_tags'  => 'Breathwork, Thiết lập ý định',
                    ],
                    [
                        'week_title' => '70 phút: Sound Bath',
                        'week_desc'  => 'Tắm đắm trong tầng tầng tần số từ bát hát pha lê, trống shamanic và các nhạc cụ thiêng liêng. Não bộ dần đi vào trạng thái Alpha → Theta.',
                        'week_tags'  => 'Bát pha lê, Trống Shamanic, Sóng Theta',
                    ],
                    [
                        'week_title' => '30 phút: Tích hợp & chia sẻ',
                        'week_desc'  => 'Nhẹ nhàng trở về ý thức bình thường. Thời gian tự do ghi nhật ký và chia sẻ cảm nhận trong nhóm.',
                        'week_tags'  => 'Tích hợp, Chia sẻ nhóm',
                    ],
                ],
                'instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'instructor_image' => $imgs['instructor'],
                'instructor_name' => 'Elena Vu',
                'instructor_bio'  => 'Sound Healer chuyên nghiệp với hơn 500 buổi Sound Bath được dẫn dắt. Mang đến không gian an toàn và sâu lắng cho mỗi người tham dự.',
                'benefits_heading' => 'Bạn sẽ cảm nhận được',
                'benefits_items'  => [
                    [
                        'benefit_title' => 'Giảm căng thẳng sâu sắc',
                        'benefit_desc'  => 'Cortisol (hormone stress) giảm rõ rệt sau một buổi Sound Bath 60–90 phút.',
                    ],
                    [
                        'benefit_title' => 'Ngủ sâu hơn',
                        'benefit_desc'  => 'Phần lớn học viên phản hồi giấc ngủ được cải thiện đáng kể trong 3 ngày sau buổi học.',
                    ],
                    [
                        'benefit_title' => 'Kết nối nội tâm',
                        'benefit_desc'  => 'Khoảnh khắc yên tĩnh để lắng nghe chính mình, không có màn hình hay thông báo.',
                    ],
                ],
                'cta_title' => 'Cho bản thân một khoảng lặng',
                'cta_desc'  => 'Số lượng tham dự giới hạn 15 người để đảm bảo không gian yên tĩnh và trải nghiệm đủ sâu cho mỗi người.',
            ],
        ],
    ];

    // ── Tạo posts + set fields ─────────────────────────────────────────
    foreach ($courses as $course) {
        $post_id = wp_insert_post([
            'post_type'   => 'khoa_hoc',
            'post_title'  => $course['title'],
            'post_status' => 'publish',
            'post_author' => 1,
        ]);

        if (is_wp_error($post_id) || ! $post_id) {
            continue;
        }

        // Featured image
        if (! empty($course['thumbnail'])) {
            set_post_thumbnail($post_id, $course['thumbnail']);
        }

        // ACF fields
        foreach ($course['acf'] as $key => $value) {
            update_field($key, $value, $post_id);
        }
    }

    update_option('mona_khoa_hoc_seeded', true);
}, 20);
