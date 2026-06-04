<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    if (get_option('mona_about_seeded') || ! function_exists('update_field')) {
        return;
    }

    // ── Import ảnh vào media library ──────────────────────────────────
    $imgs = [
        'hero_bg'    => mona_seed_import_theme_image('about-hero-bg.jpg'),
        'journey'    => mona_seed_import_theme_image('about-journey-img.jpg'),
        'pillar_1'   => mona_seed_import_theme_image('about-pillar-1-img.jpg'),
        'pillar_2'   => mona_seed_import_theme_image('about-pillar-2-img.jpg'),
        'gallery_1'  => mona_seed_import_theme_image('about-gallery-1.jpg'),
        'gallery_2'  => mona_seed_import_theme_image('about-gallery-2.jpg'),
        'gallery_3'  => mona_seed_import_theme_image('about-gallery-3.jpg'),
        'gallery_4'  => mona_seed_import_theme_image('about-gallery-4.jpg'),
        'founder'    => mona_seed_import_theme_image('about-founder-img.jpg'),
        'visionary_bg' => mona_seed_import_theme_image('about-visionary-bg.jpg'),
    ];

    // ── Tìm hoặc tạo page Về Chúng Tôi ────────────────────────────────
    $existing = get_posts([
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => [[
            'key'   => '_wp_page_template',
            'value' => 'page-template/page-about.php',
        ]],
    ]);

    if (! empty($existing)) {
        $page_id = $existing[0]->ID;
    } else {
        $page_id = wp_insert_post([
            'post_type'      => 'page',
            'post_title'     => 'Về Chúng Tôi',
            'post_status'    => 'publish',
            'post_author'    => 1,
            'page_template'  => 'page-template/page-about.php',
        ]);

        if (is_wp_error($page_id) || ! $page_id) {
            return;
        }

        update_post_meta($page_id, '_wp_page_template', 'page-template/page-about.php');
    }

    // ── Seed ACF fields ────────────────────────────────────────────────
    $fields = [

        // Hero
        'ab_hero_heading' => 'Bản Chất Của Sự Tĩnh Lặng',
        'ab_hero_desc'    => 'Nơi âm thanh hòa quyện cùng không gian, tạo nên một hành trình chữa lành sâu sắc từ bên trong. Chúng tôi tin vào sức mạnh của sự im lặng có chủ đích.',
        'ab_hero_image'   => $imgs['hero_bg'],

        // Hành trình
        'ab_journey_heading'   => 'Hành Trình Của Chúng Tôi',
        'ab_journey_desc_1'    => 'Aetheria ra đời từ một nhận thức đơn giản: trong một thế giới không ngừng chuyển động, sự tĩnh lặng đã trở thành một thứ xa xỉ. Chúng tôi không chỉ tạo ra một không gian vật lý, mà là một nơi tôn trú cho tâm hồn. Hành trình của chúng tôi bắt đầu bằng việc khám phá các phương pháp chữa lành cổ xưa qua âm thanh và năng lượng, sau đó tinh chỉnh chúng qua lăng kính của sự tối giản hiện đại.',
        'ab_journey_desc_2'    => 'Mỗi chi tiết tại Aetheria, từ ánh sáng phản chiếu trên mặt sàn gỗ đến âm vang của những chiếc bát hát pha lê, đều được thiết kế tỉ mỉ để hướng bạn về với trạng thái cân bằng tự nhiên. Chúng tôi loại bỏ những yếu tố thừa thãi, để lại một không gian trong trẻo, nơi bạn có thể thực sự lắng nghe chính mình.',
        'ab_journey_link_text' => 'Khám Phá Triết Lý',
        'ab_journey_link_url'  => '#',
        'ab_journey_image'     => $imgs['journey'],

        // Nền tảng cốt lõi
        'ab_pillars_heading' => 'Nền Tảng Cốt Lõi',
        'ab_pillars_desc'    => 'Những nguyên tắc hướng dẫn mọi trải nghiệm và không gian tại Aetheria.',
        'ab_pillars_items'   => [
            [
                'number' => '01',
                'title'  => 'Sự Tối Giản Có Chủ Đích',
                'desc'   => 'Chúng tôi tin rằng sự trống rỗng là nơi tiềm năng bắt đầu. Bằng cách loại bỏ những yếu tố phân tâm, chúng tôi tạo ra không gian cho sự tĩnh lặng nội tâm trỗi dậy.',
                'image'  => $imgs['pillar_1'],
            ],
            [
                'number' => '02',
                'title'  => 'Cộng Hưởng Chữa Lành',
                'desc'   => 'Mọi rung động đều có ngôn ngữ riêng. Chúng tôi sử dụng âm thanh học tinh tế—từ tần số Solfeggio đến bát hát Tây Tạng—để tái lập sự cân bằng hài hòa của cơ thể.',
                'image'  => $imgs['pillar_2'],
            ],
        ],

        // Người sáng lập
        'ab_visionary_label' => 'Người Sáng Lập',
        'ab_visionary_name'  => 'Elias Thorne',
        'ab_visionary_quote' => '"Âm thanh không chỉ đi qua tai; nó cộng hưởng qua từng tế bào. Nhiệm vụ của chúng ta không phải là thêm vào tiếng ồn của thế giới, mà là tạo ra những khoảng lặng nơi sự thật có thể lên tiếng."',
        'ab_visionary_bio'   => 'Với hơn hai thập kỷ nghiên cứu về âm thanh học và y học phương Đông, Elias đã phát triển một phương pháp tiếp cận độc đáo kết hợp nghệ thuật chữa lành cổ xưa với thiết kế không gian hiện đại. Tầm nhìn của ông về Aetheria là tạo ra một nơi giao thoa giữa nghệ thuật, khoa học và tinh thần.',
        'ab_visionary_image' => $imgs['founder'],
        'ab_visionary_bg'    => $imgs['visionary_bg'],

        // Gallery
        'ab_gallery_heading' => 'Sống Trọn Vẹn',
        'ab_gallery_desc'    => 'Những khoảnh khắc chân thực tại không gian của chúng tôi.',
        'ab_gallery_images'  => [
            ['image' => $imgs['gallery_1']],
            ['image' => $imgs['gallery_2']],
            ['image' => $imgs['gallery_3']],
            ['image' => $imgs['gallery_4']],
        ],

        // CTA
        'ab_cta_heading'           => 'Bắt Đầu Hành Trình Của Bạn',
        'ab_cta_desc'              => 'Dù bạn đang tìm kiếm sự tĩnh lặng sâu sắc hay muốn khám phá sức mạnh của âm thanh, không gian của chúng tôi luôn rộng mở chào đón bạn.',
        'ab_cta_btn_primary_text'  => 'Đặt Lịch Trải Nghiệm',
        'ab_cta_btn_primary_url'   => '#',
        'ab_cta_btn_secondary_text' => 'Xem Các Khóa Học',
        'ab_cta_btn_secondary_url'  => '#',
    ];

    foreach ($fields as $key => $value) {
        update_field($key, $value, $page_id);
    }

    update_option('mona_about_seeded', true);
}, 20);
