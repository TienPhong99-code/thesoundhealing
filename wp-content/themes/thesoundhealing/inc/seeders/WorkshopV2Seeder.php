<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    if (get_option('mona_workshop_seeded_v2') || ! function_exists('update_field')) {
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

    $imgs = [
        'exp_1'      => mona_seed_import_theme_image('kh-exp-1.jpg'),
        'exp_2'      => mona_seed_import_theme_image('kh-exp-2.jpg'),
        'instructor' => mona_seed_import_theme_image('kh-instructor.jpg'),
    ];

    // ── Dữ liệu bổ sung cho từng workshop (khớp theo title) ──────────────
    $updates = [

        'Sound Bath Buổi Tối' => [
            'ws_exp_title'        => 'Chìm đắm trong sóng âm chữa lành',
            'ws_exp_desc'         => "Sound Bath buổi tối là không gian lý tưởng để buông bỏ gánh nặng của một ngày dài. Bạn chỉ cần nằm xuống, nhắm mắt và để những tần số pha lê bao bọc toàn thân.\n\nKhông cần kinh nghiệm thiền định hay âm nhạc — âm thanh sẽ tự dẫn lối cho bạn vào trạng thái thư giãn sâu nhất.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Sound Healer chuyên nghiệp với hơn 500 buổi Sound Bath được dẫn dắt. Elena mang đến không gian an toàn và sâu lắng để mỗi người có thể buông thả hoàn toàn.',
            'ws_benefits_heading' => 'Bạn sẽ cảm nhận được',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Thư giãn sâu tức thì',
                    'ws_benefit_desc'  => 'Hệ thần kinh giao cảm được kích hoạt chế độ "nghỉ ngơi và phục hồi" ngay trong buổi đầu tiên.',
                ],
                [
                    'ws_benefit_title' => 'Giảm căng thẳng & lo âu',
                    'ws_benefit_desc'  => 'Cortisol (hormone stress) giảm rõ rệt — cảm giác nhẹ nhàng kéo dài nhiều giờ sau buổi tắm âm thanh.',
                ],
                [
                    'ws_benefit_title' => 'Giấc ngủ cải thiện',
                    'ws_benefit_desc'  => 'Phần lớn người tham dự phản hồi giấc ngủ sâu và ngon hơn trong 2–3 ngày sau buổi.',
                ],
                [
                    'ws_benefit_title' => 'Kết nối lại với bản thân',
                    'ws_benefit_desc'  => 'Khoảng yên lặng quý giá để lắng nghe nội tâm, không có màn hình hay thông báo.',
                ],
            ],
        ],

        'Hòa Âm Gong Thiêng' => [
            'ws_exp_title'        => 'Tần số nguyên thủy từ trống gong',
            'ws_exp_desc'         => "Trống Gong là một trong những nhạc cụ lâu đời nhất thế giới — được sử dụng trong các nghi lễ thiêng liêng của nhiều nền văn hoá Á Đông hàng ngàn năm qua.\n\nRung động của gong xuyên thấu đến từng tế bào, phá vỡ các mẫu cứng nhắc trong cơ thể và mở ra cánh cửa dẫn vào trạng thái thiền sâu không cần nỗ lực.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Gong Master được đào tạo tại Bali và Ấn Độ. Elena là một trong số ít người ở Việt Nam được chứng nhận bởi Don Conreaux — cha đẻ của Gong Yoga hiện đại.',
            'ws_benefits_heading' => 'Bạn sẽ nhận được gì?',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Thiền sâu không cần nỗ lực',
                    'ws_benefit_desc'  => 'Tần số gong tự động đưa não bộ vào trạng thái Theta — trạng thái thiền sâu mà các thiền sinh lâu năm phải mất nhiều năm luyện tập mới đạt được.',
                ],
                [
                    'ws_benefit_title' => 'Giải phóng cảm xúc tích tụ',
                    'ws_benefit_desc'  => 'Nhiều người trải qua cảm xúc dâng trào và buông bỏ trong buổi Gong Bath — hoàn toàn bình thường và là dấu hiệu của sự chữa lành.',
                ],
                [
                    'ws_benefit_title' => 'Kết nối vũ trụ',
                    'ws_benefit_desc'  => 'Trải nghiệm cảm giác hoà tan ranh giới giữa bản thân và không gian xung quanh — trạng thái kết nối thuần khiết.',
                ],
            ],
        ],

        'Buổi Thiền Âm Thanh Với Đàn Monochord' => [
            'ws_exp_title'        => 'Im lặng thiêng liêng qua dây đơn',
            'ws_exp_desc'         => "Monochord là nhạc cụ một dây huyền thoại — được Pythagoras sử dụng để nghiên cứu mối liên hệ giữa âm nhạc, toán học và vũ trụ.\n\nMỗi lần gảy dây, một tầng tần số cộng hưởng (overtone) được tạo ra — dẫn dắt người nghe vào trạng thái im lặng nội tâm sâu nhất mà bất kỳ nhạc cụ nào có thể đạt được.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Được đào tạo về Monochord Therapy tại Trung tâm Nghiên cứu Âm thanh Châu Âu. Elena mang đến một trong những trải nghiệm độc đáo và hiếm có nhất tại Việt Nam.',
            'ws_benefits_heading' => 'Bạn sẽ cảm nhận được',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Im lặng nội tâm sâu sắc',
                    'ws_benefit_desc'  => 'Overtone của Monochord tạo ra trạng thái "âm thanh của sự im lặng" — hiếm có và không thể tìm thấy ở bất kỳ nhạc cụ nào khác.',
                ],
                [
                    'ws_benefit_title' => 'Cân bằng hai bán cầu não',
                    'ws_benefit_desc'  => 'Tần số đơn thuần kích thích sự đồng bộ giữa não trái (logic) và não phải (cảm xúc), tạo ra trạng thái rõ ràng và sáng suốt.',
                ],
                [
                    'ws_benefit_title' => 'Trải nghiệm độc nhất',
                    'ws_benefit_desc'  => 'Monochord Meditation là một trong những trải nghiệm hiếm nhất tại Việt Nam — cơ hội khám phá âm thanh ở chiều sâu chưa từng có.',
                ],
            ],
        ],

        'Workshop Thanh Âm & Yoga Nidra' => [
            'ws_exp_title'        => 'Giao thoa giữa âm thanh và giấc ngủ thiền',
            'ws_exp_desc'         => "Yoga Nidra — còn gọi là \"giấc ngủ thiền\" — là kỹ thuật thư giãn sâu nhất trong truyền thống Yoga. Khi kết hợp với âm thanh chữa lành, hiệu quả được nhân lên nhiều lần.\n\nBạn sẽ được dẫn dắt qua từng lớp ý thức — từ thức đến ngủ — trong khi bát pha lê và nhạc cụ thiêng liêng tạo ra môi trường âm thanh hỗ trợ.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Giáo viên Yoga Nidra được chứng nhận bởi Bihar School of Yoga (Ấn Độ) và Sound Healer có kinh nghiệm 10 năm. Elena là người tiên phong kết hợp hai phương pháp này tại Việt Nam.',
            'ws_benefits_heading' => 'Bạn sẽ nhận được gì?',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Phục hồi sâu trong 2,5 tiếng',
                    'ws_benefit_desc'  => '45 phút Yoga Nidra tương đương 3 tiếng ngủ thông thường về mức độ phục hồi cơ thể và não bộ.',
                ],
                [
                    'ws_benefit_title' => 'Trồng ý định vào tiềm thức',
                    'ws_benefit_desc'  => 'Học cách đặt Sankalpa (ý định thiêng liêng) vào trạng thái giữa ngủ và thức — thời điểm tiềm thức dễ tiếp nhận nhất.',
                ],
                [
                    'ws_benefit_title' => 'Âm thanh tăng cường hiệu quả',
                    'ws_benefit_desc'  => 'Tần số bát pha lê hỗ trợ não bộ duy trì trạng thái Hypnagogic — ranh giới giữa thức và ngủ — trong suốt buổi thực hành.',
                ],
            ],
        ],

        'Nhập Môn Reiki — Một Ngày Trải Nghiệm' => [
            'ws_exp_title'        => 'Cánh cửa đầu tiên vào năng lượng',
            'ws_exp_desc'         => "Reiki — năng lượng sống phổ quát — là nền tảng của mọi hình thức chữa lành bằng năng lượng. Buổi nhập môn một ngày giúp bạn có cái nhìn toàn diện trước khi quyết định theo đuổi sâu hơn.\n\nBạn sẽ được trải nghiệm cảm giác năng lượng chảy qua bàn tay, tự thực hành self-healing và cảm nhận sự thay đổi rõ ràng trong cơ thể.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Reiki Master Teacher được chứng nhận quốc tế, thuộc dòng truyền thừa trực tiếp từ Hệ thống Reiki Usui Shiki Ryoho. Hơn 200 học viên đã được khai mở năng lượng dưới sự hướng dẫn của Elena.',
            'ws_benefits_heading' => 'Bạn sẽ nhận được gì?',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Trải nghiệm năng lượng thực sự',
                    'ws_benefit_desc'  => 'Cảm nhận dòng năng lượng chảy qua bàn tay — không phải khái niệm lý thuyết mà là trải nghiệm thân thể thực tế.',
                ],
                [
                    'ws_benefit_title' => 'Tự chữa lành ngay hôm đó',
                    'ws_benefit_desc'  => 'Học và thực hành kỹ thuật tự trị liệu đơn giản có thể áp dụng hàng ngày tại nhà.',
                ],
                [
                    'ws_benefit_title' => 'Nền tảng cho hành trình tiếp theo',
                    'ws_benefit_desc'  => 'Hiểu đầy đủ về hệ thống Reiki để quyết định có muốn tiến sâu vào khoá Reiki Level 1 chính thức hay không.',
                ],
            ],
        ],

        'Tarot Căn Bản — Đọc Bài Cho Bản Thân' => [
            'ws_exp_title'        => 'Tarot như tấm gương nội tâm',
            'ws_exp_desc'         => "Tarot không phải công cụ bói toán hay tiên tri — đó là ngôn ngữ biểu tượng phản chiếu trạng thái nội tâm của bạn tại thời điểm hiện tại.\n\nBuổi workshop sẽ thay đổi hoàn toàn cách bạn nhìn nhận về Tarot — từ một thứ bí ẩn, xa lạ sang một người bạn đồng hành thấu hiểu trong hành trình tự khám phá.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Đọc và nghiên cứu Tarot hơn 8 năm theo hướng tiếp cận tâm lý học Jung. Elena đào tạo hàng trăm người đọc Tarot cho bản thân theo phương pháp trực giác và biểu tượng.',
            'ws_benefits_heading' => 'Bạn sẽ học được gì?',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Hiểu 22 lá Major Arcana',
                    'ws_benefit_desc'  => 'Nắm vững ý nghĩa nguyên mẫu và biểu tượng của 22 lá bài chính — nền tảng của mọi hệ thống Tarot.',
                ],
                [
                    'ws_benefit_title' => 'Cách đặt câu hỏi đúng',
                    'ws_benefit_desc'  => 'Nghệ thuật đặt câu hỏi mở — yếu tố quyết định chất lượng của mỗi lần trải bài.',
                ],
                [
                    'ws_benefit_title' => 'Đọc bài không cần "thuộc lòng"',
                    'ws_benefit_desc'  => 'Phương pháp đọc bài theo trực giác và cảm xúc — không cần ghi nhớ từng ý nghĩa, phù hợp cho người mới bắt đầu.',
                ],
            ],
        ],

        'Thiền Năng Lượng & Làm Sạch Luân Xa' => [
            'ws_exp_title'        => 'Tinh lọc trường năng lượng từ bên trong',
            'ws_exp_desc'         => "7 luân xa là 7 trung tâm năng lượng của cơ thể — mỗi luân xa liên quan đến một khía cạnh khác nhau của sức khoẻ thể chất và tinh thần.\n\nBuổi thiền hướng dẫn chuyên sâu này sẽ dạy bạn cách tự nhận biết trạng thái từng luân xa và sử dụng hơi thở, âm thanh và trực giác để phục hồi sự cân bằng.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Chuyên gia năng lượng luân xa với hơn 10 năm thực hành và đào tạo. Elena kết hợp kiến thức Ayurveda, Yoga và Sound Healing để tạo ra phương pháp tiếp cận toàn diện.',
            'ws_benefits_heading' => 'Bạn sẽ học được gì?',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Nhận biết 7 luân xa',
                    'ws_benefit_desc'  => 'Hiểu vị trí, màu sắc, âm thanh và chức năng của từng luân xa — kết nối giữa năng lượng và sức khoẻ.',
                ],
                [
                    'ws_benefit_title' => 'Kỹ thuật tự làm sạch',
                    'ws_benefit_desc'  => 'Học 3 kỹ thuật đơn giản có thể thực hành hàng ngày: thiền màu sắc, âm thanh Bija mantra, và hơi thở luân xa.',
                ],
                [
                    'ws_benefit_title' => 'Đọc tín hiệu cơ thể',
                    'ws_benefit_desc'  => 'Nhận ra các dấu hiệu cơ thể gửi đến khi một luân xa bị tắc nghẽn hay mất cân bằng.',
                ],
            ],
        ],

        'Oracle Cards — Kết Nối Trực Giác' => [
            'ws_exp_title'        => 'Ngôn ngữ của trực giác và linh hồn',
            'ws_exp_desc'         => "Oracle Cards là công cụ tiếp cận nhẹ nhàng và linh hoạt hơn Tarot — không có quy tắc cứng nhắc, chỉ có sự kết nối thuần khiết giữa bạn và hình ảnh trực giác.\n\nBuổi workshop sẽ giúp bạn xây dựng một thực hành rút bài hàng ngày — như một nghi lễ sáng sớm để kết nối với trực giác trước khi bắt đầu ngày mới.",
            'ws_exp_image_1'      => $imgs['exp_1'],
            'ws_exp_image_2'      => $imgs['exp_2'],
            'ws_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
            'ws_instructor_image' => $imgs['instructor'],
            'ws_instructor_name'  => 'Elena Vu',
            'ws_instructor_bio'   => 'Nhà trị liệu Oracle Cards và Thiền định với 8 năm kinh nghiệm. Elena đã đưa thực hành Oracle vào cuộc sống hàng ngày của hơn 300 học viên trên khắp Việt Nam.',
            'ws_benefits_heading' => 'Bạn sẽ học được gì?',
            'ws_benefits_items'   => [
                [
                    'ws_benefit_title' => 'Xây dựng thực hành rút bài hàng ngày',
                    'ws_benefit_desc'  => 'Tạo ra nghi lễ sáng sớm đơn giản với Oracle Cards để bắt đầu mỗi ngày với sự rõ ràng và ý định.',
                ],
                [
                    'ws_benefit_title' => 'Đọc thông điệp theo trực giác',
                    'ws_benefit_desc'  => 'Học cách tin vào luồng cảm nhận đầu tiên — không phán xét, không phân tích, chỉ lắng nghe.',
                ],
                [
                    'ws_benefit_title' => 'Kết nối với "Hướng dẫn nội tâm"',
                    'ws_benefit_desc'  => 'Oracle là chiếc cầu nối để lắng nghe tiếng nói bên trong — sự khôn ngoan sẵn có mà chúng ta thường bỏ qua.',
                ],
            ],
        ],
    ];

    // ── Lấy tất cả workshop posts và cập nhật fields ──────────────────────
    $existing_posts = get_posts([
        'post_type'      => 'workshop',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);

    foreach ($existing_posts as $post) {
        $title = $post->post_title;
        if (! isset($updates[$title])) continue;

        $fields = $updates[$title];

        // Image fields cần format array cho ACF
        $image_keys = ['ws_exp_image_1', 'ws_exp_image_2', 'ws_instructor_image'];

        foreach ($fields as $key => $value) {
            if (in_array($key, $image_keys, true)) {
                // ACF image field với format 'array' cần truyền attachment ID
                update_field($key, $value, $post->ID);
            } elseif ($key === 'ws_benefits_items') {
                update_field($key, $value, $post->ID);
            } else {
                update_field($key, $value, $post->ID);
            }
        }
    }

    update_option('mona_workshop_seeded_v2', true);
}, 20);
