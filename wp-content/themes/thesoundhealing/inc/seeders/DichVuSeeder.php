<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    if (get_option('mona_dich_vu_seeded_v1') || ! function_exists('update_field')) {
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

    // ── Import ảnh shared ─────────────────────────────────────────────────
    $imgs = [
        'exp_main'   => mona_seed_import_theme_image('dv-exp-main.jpg'),
        'exp_detail' => mona_seed_import_theme_image('dv-exp-detail.jpg'),
        'instructor' => mona_seed_import_theme_image('kh-instructor.jpg'),
    ];

    // ── Ensure taxonomy terms ─────────────────────────────────────────────
    $term_sound   = wp_insert_term('Sound Healing',        'loai_dich_vu');
    $term_private = wp_insert_term('Private Experience',   'loai_dich_vu');
    $term_vibra   = wp_insert_term('Vibrational Therapy',  'loai_dich_vu');
    $term_energy  = wp_insert_term('Energy Healing',       'loai_dich_vu');
    $term_arts    = wp_insert_term('Intuitive Arts',       'loai_dich_vu');

    $term_ids = [
        'sound'   => is_wp_error($term_sound)   ? $term_sound['term_id']   : $term_sound['term_id'],
        'private' => is_wp_error($term_private)  ? $term_private['term_id']  : $term_private['term_id'],
        'vibra'   => is_wp_error($term_vibra)   ? $term_vibra['term_id']   : $term_vibra['term_id'],
        'energy'  => is_wp_error($term_energy)  ? $term_energy['term_id']  : $term_energy['term_id'],
        'arts'    => is_wp_error($term_arts)    ? $term_arts['term_id']    : $term_arts['term_id'],
    ];

    // Lấy term ID thực nếu đã tồn tại
    foreach (
        [
            'sound'   => 'Sound Healing',
            'private' => 'Private Experience',
            'vibra'   => 'Vibrational Therapy',
            'energy'  => 'Energy Healing',
            'arts'    => 'Intuitive Arts',
        ] as $key => $name
    ) {
        $t = get_term_by('name', $name, 'loai_dich_vu');
        if ($t) $term_ids[$key] = $t->term_id;
    }

    // ── Dữ liệu 6 dịch vụ ────────────────────────────────────────────────
    $services = [

        [
            'title'     => 'Tắm Âm Ngủ Ngon (Nhóm)',
            'thumbnail' => 'dv-tam-am-ngu-ngon-nhom.jpg',
            'term_key'  => 'sound',
            'order'     => 1,
            'fields'    => [
                'dv_duration'    => '60 - 90 phút mỗi phiên',
                'dv_clothing'    => 'Đồ tập hoặc quần áo thoải mái, nhẹ nhàng',
                'dv_location'    => 'Aetheria Sanctuary, Level 4, Thảo Điền',
                'dv_preparation' => 'Hạn chế ăn no 2 tiếng trước giờ trị liệu',
                'dv_short_desc'  => 'Midday/Deep Sleep Sound Bath. Một hành trình quay về bên trong, nơi các tần số âm thanh từ chuông xoay pha lê dẫn dắt hệ thần kinh vào trạng thái thư giãn tuyệt đối, phục hồi giấc ngủ và cân bằng tâm trí.',
                'dv_price'       => '800.000 VNĐ',
                'dv_exp_title'   => 'Hành trình Trải nghiệm',
                'dv_exp_desc'    => "Mỗi buổi Tắm Âm được thiết kế như một nghi lễ thanh tẩy. Bạn sẽ được nằm thoải mái trên thảm, hỗ trợ bởi gối lót và chăn ấm. Trong không gian tĩnh lặng, những rung động tinh khiết từ bộ chuông pha lê Alchemy sẽ bao bọc cơ thể, giúp giải phóng các tắc nghẽn năng lượng và phục hồi trạng thái cân bằng tự nhiên.",
                'dv_exp_image_1' => $imgs['exp_main'],
                'dv_exp_image_2' => $imgs['exp_detail'],
                'dv_feature_1_title' => 'Pha lê Alchemy',
                'dv_feature_1_desc'  => 'Sử dụng các loại chuông pha lê quý hiếm cho tần số chữa lành cao nhất.',
                'dv_feature_2_title' => 'Tĩnh lặng tuyệt đối',
                'dv_feature_2_desc'  => 'Không gian được cách âm hoàn toàn, tách biệt với thế giới bên ngoài.',
                'dv_benefits_heading' => 'Lợi ích của liệu pháp',
                'dv_benefits_items'   => [
                    ['dv_benefit_title' => 'CẢI THIỆN GIẤC NGỦ',     'dv_benefit_desc' => 'Giúp đưa sóng não về trạng thái Delta và Theta, hỗ trợ ngủ sâu và ngon hơn.'],
                    ['dv_benefit_title' => 'GIẢM STRESS & CĂNG THẲNG', 'dv_benefit_desc' => 'Hạ mức cortisol trong máu, làm dịu hệ thần kinh thực vật sau những giờ làm việc mệt mỏi.'],
                    ['dv_benefit_title' => 'MINH MẪN TÂM TRÍ',        'dv_benefit_desc' => 'Giải phóng những suy nghĩ thừa thãi, giúp bạn tập trung và sáng tạo hơn.'],
                ],
                'dv_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image' => $imgs['instructor'],
                'dv_instructor_name'  => 'Linh Tâm',
                'dv_instructor_bio'   => 'Sound Healer chuyên nghiệp với hơn 500 buổi Sound Bath được dẫn dắt. Linh Tâm mang đến không gian an toàn và sâu lắng để mỗi người có thể buông thả hoàn toàn.',
                'dv_cta_title'        => 'Bắt đầu hành trình chữa lành',
                'dv_cta_desc'         => 'Mỗi buổi Tắm Âm là một bước tiến trên hành trình phục hồi sâu sắc. Đặt lịch ngay hôm nay để giữ chỗ.',
            ],
        ],

        [
            'title'     => 'Tắm Âm Ngủ Ngon (Riêng Tư)',
            'thumbnail' => 'dv-tam-am-ngu-ngon-rieng-tu.jpg',
            'term_key'  => 'private',
            'order'     => 2,
            'fields'    => [
                'dv_duration'    => '90 phút mỗi phiên',
                'dv_clothing'    => 'Đồ thoải mái, nhẹ nhàng — không mặc đồ bó sát',
                'dv_location'    => 'Aetheria Sanctuary, Level 4, Thảo Điền',
                'dv_preparation' => 'Không dùng nước hoa hoặc tinh dầu mạnh trước buổi',
                'dv_short_desc'  => 'Không gian trị liệu âm thanh dành riêng cho bạn, được tuỳ chỉnh theo nhu cầu phục hồi cá nhân. Mỗi tần số được lựa chọn dựa trên trạng thái cơ thể và mục tiêu chữa lành của bạn.',
                'dv_price'       => '1.500.000 VNĐ',
                'dv_exp_title'   => 'Trải nghiệm cá nhân hoá sâu sắc',
                'dv_exp_desc'    => "Khác với buổi nhóm, phiên Sound Bath riêng tư được thiết kế hoàn toàn xung quanh bạn. Trước khi bắt đầu, healer sẽ có 15 phút trò chuyện để hiểu trạng thái hiện tại và mục tiêu của bạn.\n\nTừ đó, bộ nhạc cụ, tần số và phương pháp được lựa chọn riêng — tạo ra một hành trình chữa lành không thể lặp lại.",
                'dv_exp_image_1' => $imgs['exp_main'],
                'dv_exp_image_2' => $imgs['exp_detail'],
                'dv_feature_1_title' => 'Tuỳ chỉnh hoàn toàn',
                'dv_feature_1_desc'  => 'Tần số, nhạc cụ và thời lượng được điều chỉnh theo nhu cầu riêng của từng người.',
                'dv_feature_2_title' => 'Không gian 1-1',
                'dv_feature_2_desc'  => 'Không gian riêng tư hoàn toàn, không chia sẻ với bất kỳ ai khác.',
                'dv_benefits_heading' => 'Lợi ích của phiên riêng tư',
                'dv_benefits_items'   => [
                    ['dv_benefit_title' => 'PHỤC HỒI SÂU HƠN',     'dv_benefit_desc' => 'Không gian 1-1 cho phép healer điều chỉnh liên tục, mang lại hiệu quả sâu hơn so với buổi nhóm.'],
                    ['dv_benefit_title' => 'AN TOÀN & TIN TƯỞNG',   'dv_benefit_desc' => 'Cảm giác an toàn tuyệt đối để buông bỏ hoàn toàn mà không có sự hiện diện của người lạ.'],
                    ['dv_benefit_title' => 'TIẾP CẬN CÁ NHÂN HOÁ', 'dv_benefit_desc' => 'Healer có thể tập trung vào các vùng cơ thể hoặc trung tâm năng lượng cần chú ý đặc biệt.'],
                ],
                'dv_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image' => $imgs['instructor'],
                'dv_instructor_name'  => 'Linh Tâm',
                'dv_instructor_bio'   => 'Chuyên gia Sound Healing với hơn 10 năm kinh nghiệm trị liệu cá nhân. Linh Tâm đã đồng hành cùng hơn 300 khách hàng trong những hành trình chữa lành riêng tư và sâu sắc nhất.',
                'dv_cta_title'        => 'Không gian chỉ dành cho bạn',
                'dv_cta_desc'         => 'Phiên Sound Bath riêng tư là món quà tuyệt vời nhất bạn có thể dành cho chính mình. Đặt lịch sớm — số lượng phiên trong tuần có giới hạn.',
            ],
        ],

        [
            'title'     => 'Trị Liệu Chuông Đồng (Riêng Tư)',
            'thumbnail' => 'dv-tri-lieu-chuong-do-rieng-tu.jpg',
            'term_key'  => 'vibra',
            'order'     => 3,
            'fields'    => [
                'dv_duration'    => '60 phút mỗi phiên',
                'dv_clothing'    => 'Quần áo rộng, không đeo trang sức kim loại',
                'dv_location'    => 'Aetheria Sanctuary, Level 4, Thảo Điền',
                'dv_preparation' => 'Thông báo trước nếu có implant kim loại trong cơ thể',
                'dv_short_desc'  => 'Kỹ thuật đặt chuông Himalaya trực tiếp lên cơ thể để các rung động âm thanh tác động sâu vào từng tế bào và huyệt đạo, khai thông kinh mạch và phục hồi cân bằng năng lượng.',
                'dv_price'       => '1.200.000 VNĐ',
                'dv_exp_title'   => 'Rung động chữa lành từ bên trong',
                'dv_exp_desc'    => "Chuông đồng Himalaya được đặt trực tiếp lên các điểm huyệt đạo và luân xa trên cơ thể. Khi được gõ, rung động truyền qua xương và mô mềm — tạo ra hiệu ứng massage năng lượng từ bên trong ra bên ngoài.\n\nĐây là một trong những hình thức trị liệu âm thanh cổ xưa nhất, có nguồn gốc từ truyền thống Tây Tạng và Nepal hàng nghìn năm.",
                'dv_exp_image_1' => $imgs['exp_main'],
                'dv_exp_image_2' => $imgs['exp_detail'],
                'dv_feature_1_title' => 'Chuông Himalaya chính gốc',
                'dv_feature_1_desc'  => 'Chuông đồng thủ công từ Nepal, chứa 7 kim loại linh thiêng theo truyền thống Tây Tạng.',
                'dv_feature_2_title' => 'Rung động xuyên thấu',
                'dv_feature_2_desc'  => 'Sóng âm truyền trực tiếp qua mô cơ và xương — massage từ bên trong ra ngoài.',
                'dv_benefits_heading' => 'Lợi ích của liệu pháp',
                'dv_benefits_items'   => [
                    ['dv_benefit_title' => 'KHAI THÔNG KINH MẠCH',  'dv_benefit_desc' => 'Rung động chuông tác động vào các huyệt đạo, giúp năng lượng lưu thông trơn tru hơn.'],
                    ['dv_benefit_title' => 'GIẢM ĐAU CƠ & KHỚP',   'dv_benefit_desc' => 'Sóng âm có tác dụng thư giãn cơ sâu và giảm viêm, được nhiều người báo cáo giảm đau hiệu quả.'],
                    ['dv_benefit_title' => 'CÂN BẰNG LUÂN XA',      'dv_benefit_desc' => 'Mỗi tần số chuông tương ứng với một luân xa — giúp phục hồi sự cân bằng năng lượng toàn thân.'],
                ],
                'dv_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image' => $imgs['instructor'],
                'dv_instructor_name'  => 'Linh Tâm',
                'dv_instructor_bio'   => 'Được đào tạo về Himalayan Singing Bowl Therapy tại Nepal. Linh Tâm có hơn 8 năm thực hành và đã mang liệu pháp này đến hàng trăm người tại Việt Nam.',
                'dv_cta_title'        => 'Cảm nhận rung động chữa lành',
                'dv_cta_desc'         => 'Trải nghiệm liệu pháp chuông đồng — một hình thức chữa lành cổ xưa mang lại kết quả hiện đại. Đặt lịch ngay hôm nay.',
            ],
        ],

        [
            'title'     => 'Chữa Lành Usui Reiki (Nhóm)',
            'thumbnail' => 'dv-chua-lanh-reiki-nhom.jpg',
            'term_key'  => 'energy',
            'order'     => 4,
            'fields'    => [
                'dv_duration'    => '90 phút mỗi buổi',
                'dv_clothing'    => 'Đồ thoải mái, màu nhạt nếu có thể',
                'dv_location'    => 'Aetheria Sanctuary, Level 4, Thảo Điền',
                'dv_preparation' => 'Tắm sạch trước buổi, giữ tinh thần cởi mở',
                'dv_short_desc'  => 'Kết nối năng lượng vũ trụ trong không gian cộng hưởng nhóm để thanh tẩy trường năng lượng cá nhân và cân bằng lại các luân xa, mang lại sự nhẹ nhàng và rõ ràng trong tâm trí.',
                'dv_price'       => '600.000 VNĐ',
                'dv_exp_title'   => 'Năng lượng chữa lành trong không gian nhóm',
                'dv_exp_desc'    => "Reiki nhóm tạo ra một trường năng lượng đặc biệt khi nhiều người cùng mở lòng nhận và trao. Năng lượng Reiki được khuếch đại qua sự cộng hưởng tập thể — nhiều người báo cáo trải nghiệm sâu hơn so với phiên cá nhân.\n\nBuổi bắt đầu bằng 15 phút thiền dẫn dắt, tiếp theo là 45 phút trị liệu Reiki đồng thời và kết thúc bằng vòng chia sẻ.",
                'dv_exp_image_1' => $imgs['exp_main'],
                'dv_exp_image_2' => $imgs['exp_detail'],
                'dv_feature_1_title' => 'Cộng hưởng tập thể',
                'dv_feature_1_desc'  => 'Năng lượng được khuếch đại khi nhiều người cùng kết nối trong một không gian thiêng liêng.',
                'dv_feature_2_title' => 'Kết nối cộng đồng',
                'dv_feature_2_desc'  => 'Gặp gỡ những người cùng hành trình chữa lành và tạo nên không gian an toàn chung.',
                'dv_benefits_heading' => 'Lợi ích của liệu pháp',
                'dv_benefits_items'   => [
                    ['dv_benefit_title' => 'THANH TẨY TRƯỜNG NĂNG LƯỢNG', 'dv_benefit_desc' => 'Reiki loại bỏ những năng lượng tiêu cực và tắc nghẽn tích tụ từ áp lực cuộc sống hàng ngày.'],
                    ['dv_benefit_title' => 'CÂN BẰNG CẢM XÚC',           'dv_benefit_desc' => 'Nhiều người cảm thấy nhẹ nhõm về mặt cảm xúc sau buổi — như được gỡ bỏ những gánh nặng vô hình.'],
                    ['dv_benefit_title' => 'TĂNG CƯỜNG SỨC SỐNG',        'dv_benefit_desc' => 'Năng lượng Ki được phục hồi và lưu thông tốt hơn, mang lại cảm giác tươi mới và tràn đầy sức sống.'],
                ],
                'dv_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image' => $imgs['instructor'],
                'dv_instructor_name'  => 'Linh Tâm',
                'dv_instructor_bio'   => 'Reiki Master Teacher được chứng nhận quốc tế, thuộc dòng truyền thừa Usui Shiki Ryoho. Linh Tâm đã dẫn dắt hàng trăm buổi Reiki nhóm, tạo nên những trải nghiệm chuyển hoá sâu sắc.',
                'dv_cta_title'        => 'Gia nhập không gian chữa lành chung',
                'dv_cta_desc'         => 'Buổi Reiki nhóm tiếp theo sẽ diễn ra sớm. Đặt chỗ của bạn ngay — số lượng tham dự giới hạn để đảm bảo chất lượng năng lượng.',
            ],
        ],

        [
            'title'     => 'Chữa Lành Usui Reiki (Riêng Tư)',
            'thumbnail' => 'dv-chua-lanh-reiki-rieng-tu.jpg',
            'term_key'  => 'energy',
            'order'     => 5,
            'fields'    => [
                'dv_duration'    => '60 - 90 phút mỗi phiên',
                'dv_clothing'    => 'Đồ thoải mái, tránh màu tối',
                'dv_location'    => 'Aetheria Sanctuary, Level 4, Thảo Điền',
                'dv_preparation' => 'Uống nhiều nước sau buổi để hỗ trợ quá trình thải độc',
                'dv_short_desc'  => 'Phiên trị liệu năng lượng chuyên sâu 1-1 giúp giải quyết các tắc nghẽn cảm xúc và thể chất cụ thể. Healer sẽ làm việc trực tiếp với hệ thống luân xa và trường năng lượng của bạn.',
                'dv_price'       => '1.200.000 VNĐ',
                'dv_exp_title'   => 'Chữa lành sâu trong không gian riêng tư',
                'dv_exp_desc'    => "Phiên Reiki 1-1 bắt đầu bằng cuộc trò chuyện ngắn để healer hiểu tình trạng hiện tại và những gì bạn muốn giải quyết. Sau đó bạn nằm thư giãn hoàn toàn trong khi healer làm việc trên từng vùng năng lượng.\n\nMột số người cảm nhận nhiệt ấm, ngứa ran hoặc cảm xúc dâng lên — tất cả đều là dấu hiệu của năng lượng đang chuyển hoá.",
                'dv_exp_image_1' => $imgs['exp_main'],
                'dv_exp_image_2' => $imgs['exp_detail'],
                'dv_feature_1_title' => 'Tiếp cận chính xác',
                'dv_feature_1_desc'  => 'Healer tập trung hoàn toàn vào bạn, xác định và làm việc trực tiếp với các tắc nghẽn cụ thể.',
                'dv_feature_2_title' => 'Không gian an toàn tuyệt đối',
                'dv_feature_2_desc'  => 'Môi trường riêng tư cho phép cảm xúc được giải phóng tự nhiên mà không có áp lực.',
                'dv_benefits_heading' => 'Lợi ích của liệu pháp',
                'dv_benefits_items'   => [
                    ['dv_benefit_title' => 'GIẢI PHÓNG TẮC NGHẼN CẢM XÚC', 'dv_benefit_desc' => 'Reiki tác động vào gốc rễ của các vấn đề cảm xúc — không chỉ giải quyết triệu chứng bề mặt.'],
                    ['dv_benefit_title' => 'HỖ TRỢ CHỮA LÀNH THỂ CHẤT',    'dv_benefit_desc' => 'Tăng cường hệ miễn dịch và kích thích khả năng tự phục hồi tự nhiên của cơ thể.'],
                    ['dv_benefit_title' => 'SỰ RÕ RÀNG VÀ ĐỊNH HƯỚNG',      'dv_benefit_desc' => 'Nhiều người rời buổi với cảm giác rõ ràng hơn về tình huống trong cuộc sống đang đối mặt.'],
                ],
                'dv_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image' => $imgs['instructor'],
                'dv_instructor_name'  => 'Linh Tâm',
                'dv_instructor_bio'   => 'Reiki Master với hơn 10 năm thực hành trị liệu cá nhân. Linh Tâm được biết đến với khả năng "đọc" trường năng lượng chính xác và phương pháp tiếp cận nhẹ nhàng, đồng hành.',
                'dv_cta_title'        => 'Bắt đầu quá trình chuyển hoá',
                'dv_cta_desc'         => 'Một phiên Reiki có thể thay đổi trạng thái năng lượng của bạn đáng kể. Đặt lịch ngay để bắt đầu hành trình chữa lành sâu sắc.',
            ],
        ],

        [
            'title'     => 'Khai Vấn Dự Đoán Huyền Học',
            'thumbnail' => 'dv-khai-van-huyen-hoc.jpg',
            'term_key'  => 'arts',
            'order'     => 6,
            'fields'    => [
                'dv_duration'    => '60 phút mỗi phiên',
                'dv_clothing'    => 'Không yêu cầu đặc biệt',
                'dv_location'    => 'Aetheria Sanctuary, Level 4, Thảo Điền hoặc online',
                'dv_preparation' => 'Chuẩn bị 1-3 câu hỏi hoặc chủ đề muốn khai vấn',
                'dv_short_desc'  => 'Sử dụng Soul Mirror Cards và các công cụ huyền học để soi chiếu nội tâm, tìm kiếm những chỉ dẫn trực giác cho các câu hỏi quan trọng trong hành trình sống của bạn.',
                'dv_price'       => '900.000 VNĐ',
                'dv_exp_title'   => 'Hành trình nhìn vào gương nội tâm',
                'dv_exp_desc'    => "Soul Mirror Cards không phải công cụ bói toán — chúng là những tấm gương phản chiếu trạng thái nội tâm sâu nhất của bạn. Mỗi lần rút bài, hình ảnh và biểu tượng kích hoạt trực giác, mở ra những góc nhìn mới.\n\nBuổi khai vấn bắt đầu bằng thiền ngắn để kết nối, sau đó là trải bài và đọc thông điệp theo câu hỏi cụ thể của bạn.",
                'dv_exp_image_1' => $imgs['exp_main'],
                'dv_exp_image_2' => $imgs['exp_detail'],
                'dv_feature_1_title' => 'Soul Mirror Cards',
                'dv_feature_1_desc'  => 'Bộ bài độc đáo được phát triển riêng cho thực hành soi chiếu nội tâm và trực giác.',
                'dv_feature_2_title' => 'Góc nhìn trung lập',
                'dv_feature_2_desc'  => 'Healer đóng vai trò là người phiên dịch khách quan, không áp đặt hay phán xét.',
                'dv_benefits_heading' => 'Lợi ích của liệu pháp',
                'dv_benefits_items'   => [
                    ['dv_benefit_title' => 'RÕ RÀNG TRONG QUYẾT ĐỊNH',     'dv_benefit_desc' => 'Những tình huống phức tạp trở nên rõ ràng hơn khi được nhìn qua lăng kính biểu tượng và trực giác.'],
                    ['dv_benefit_title' => 'KẾT NỐI VỚI TRÍ TUỆ NỘI TÂM', 'dv_benefit_desc' => 'Học cách lắng nghe tiếng nói bên trong — nguồn khôn ngoan sẵn có mà chúng ta thường bỏ qua.'],
                    ['dv_benefit_title' => 'HƯỚNG DẪN THỰC TẾ',             'dv_benefit_desc' => 'Mỗi buổi kết thúc với những bước hành động cụ thể dựa trên thông điệp từ buổi khai vấn.'],
                ],
                'dv_instructor_label' => 'NGƯỜI HƯỚNG DẪN',
                'dv_instructor_image' => $imgs['instructor'],
                'dv_instructor_name'  => 'Linh Tâm',
                'dv_instructor_bio'   => 'Chuyên gia khai vấn huyền học với 8 năm nghiên cứu và thực hành. Linh Tâm đã đồng hành cùng hơn 400 người trong những buổi khai vấn mang lại sự rõ ràng và định hướng.',
                'dv_cta_title'        => 'Tìm kiếm sự rõ ràng cho hành trình của bạn',
                'dv_cta_desc'         => 'Đặt câu hỏi quan trọng nhất của bạn và nhận những chỉ dẫn từ trí tuệ nội tâm. Đặt lịch khai vấn ngay hôm nay.',
            ],
        ],

    ];

    // ── Tạo posts và set ACF fields ───────────────────────────────────────
    foreach ($services as $service) {

        // Bỏ qua nếu đã tồn tại (theo title)
        $existing = get_posts([
            'post_type'      => 'dich_vu',
            'post_status'    => 'any',
            'posts_per_page' => 1,
            'title'          => $service['title'],
        ]);
        if (! empty($existing)) continue;

        // Import thumbnail
        $thumb_id = mona_seed_import_theme_image($service['thumbnail']);

        // Tạo post
        $post_id = wp_insert_post([
            'post_title'   => $service['title'],
            'post_type'    => 'dich_vu',
            'post_status'  => 'publish',
            'menu_order'   => $service['order'],
            'post_content' => '',
        ]);

        if (is_wp_error($post_id) || ! $post_id) continue;

        // Gán thumbnail
        if ($thumb_id) {
            set_post_thumbnail($post_id, $thumb_id);
        }

        // Gán taxonomy
        $term_id = $term_ids[$service['term_key']] ?? null;
        if ($term_id) {
            wp_set_post_terms($post_id, [$term_id], 'loai_dich_vu');
        }

        // Set ACF fields
        foreach ($service['fields'] as $key => $value) {
            update_field($key, $value, $post_id);
        }
    }

    update_option('mona_dich_vu_seeded_v1', true);
}, 20);
