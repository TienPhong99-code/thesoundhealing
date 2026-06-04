<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'TRẢI NGHIỆM & TRỊ LIỆU',
    'heading' => 'Dịch Vụ',
    'desc'    => 'Khám phá các liệu pháp chữa lành cá nhân hóa, từ trị liệu âm thanh đến năng lượng, được thiết kế để mang lại sự an lạc tuyệt đối.',
    'link'    => ['url' => home_url('/dich-vu'), 'title' => 'XEM TẤT CẢ', 'target' => ''],
    'items'   => [
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tam-am-ngu-ngon-nhom.jpg', 'alt' => 'Tắm Âm Ngủ Ngon (Nhóm)'],
            'category' => 'SOUND HEALING',
            'title'    => 'Tắm Âm Ngủ Ngon (Nhóm)',
            'desc'     => 'Trải nghiệm sóng âm thư giãn cùng nhóm để cải thiện chất lượng giấc ngủ và giảm căng thẳng tích tụ.',
            'url'      => '#',
        ],
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tam-am-ngu-ngon-rieng-tu.jpg', 'alt' => 'Tắm Âm Ngủ Ngon (Riêng Tư)'],
            'category' => 'PRIVATE EXPERIENCE',
            'title'    => 'Tắm Âm Ngủ Ngon (Riêng Tư)',
            'desc'     => 'Không gian trị liệu âm thanh dành riêng cho bạn, tập trung vào nhu cầu phục hồi sâu sắc của cá nhân.',
            'url'      => '#',
        ],
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tri-lieu-chuong-do-rieng-tu.jpg', 'alt' => 'Trị Liệu Chuông Đồ (Riêng Tư)'],
            'category' => 'VIBRATIONAL THERAPY',
            'title'    => 'Trị Liệu Chuông Đồ (Riêng Tư)',
            'desc'     => 'Kỹ thuật đặt chuông trực tiếp lên cơ thể để các rung động tác động sâu vào từng tế bào và huyệt đạo.',
            'url'      => '#',
        ],
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-chua-lanh-reiki-nhom.jpg', 'alt' => 'Chữa Lành Usui Reiki (Group)'],
            'category' => 'ENERGY HEALING',
            'title'    => 'Chữa Lành Usui Reiki (Group)',
            'desc'     => 'Kết nối năng lượng vũ trụ trong không gian cộng hưởng nhóm để thanh tẩy và cân bằng tâm trí.',
            'url'      => '#',
        ],
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-chua-lanh-reiki-rieng-tu.jpg', 'alt' => 'Chữa Lành Usui Reiki (Riêng Tư)'],
            'category' => 'ENERGY HEALING',
            'title'    => 'Chữa Lành Usui Reiki (Riêng Tư)',
            'desc'     => 'Phiên trị liệu năng lượng chuyên sâu 1-1 giúp giải quyết các tắc nghẽn cảm xúc và thể chất cụ thể.',
            'url'      => '#',
        ],
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-khai-van-huyen-hoc.jpg', 'alt' => 'Khai Vấn Dự Đoán Huyền Học'],
            'category' => 'INTUITIVE ARTS',
            'title'    => 'Khai Vấn Dự Đoán Huyền Học',
            'desc'     => 'Sử dụng Soul Mirror Cards để soi chiếu nội tâm và tìm kiếm những chỉ dẫn trực giác cho hành trình sống.',
            'url'      => '#',
        ],
    ],
];

$raw_link    = get_field('service_link', $page_id);
$raw_objects = get_field('service_items', $page_id);

if (empty($raw_objects)) {
    $raw_objects = get_posts([
        'post_type'      => 'dich_vu',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ]);
}

$acf_items = [];
foreach ($raw_objects as $post) {
    $thumb     = get_the_post_thumbnail_url($post->ID, 'full');
    $terms     = get_the_terms($post->ID, 'loai_dich_vu');
    $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    $acf_items[] = [
        'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'category' => $term_name,
        'title'    => $post->post_title,
        'desc'     => get_field('dv_short_desc', $post->ID),
        'url'      => get_permalink($post->ID),
    ];
}

$data = [
    'label'   => get_field('service_label', $page_id)   ?: $sample['label'],
    'heading' => get_field('service_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('service_desc', $page_id)    ?: $sample['desc'],
    'link'    => $raw_link                              ?: $sample['link'],
    'items'   => $acf_items                            ?: $sample['items'],
];
?>

<section class="sec-service py-(--pd-sc) bg-white">
    <div class="container">

        <!-- Header -->
        <div class="flex flex-col items-center text-center gap-4 max-w-[768px] mx-auto mb-16">
            <p class="text-[#133a35] text-[12px] font-semibold uppercase tracking-[1.2px]">
                <?php echo esc_html($data['label']); ?>
            </p>
            <h2 class="font-title text-[#1b1c19] text-[32px] font-normal leading-[40px]">
                <?php echo esc_html($data['heading']); ?>
            </h2>
            <?php if (!empty($data['desc'])) : ?>
                <p class="text-[#414847] text-[16px] leading-[24px]">
                    <?php echo esc_html($data['desc']); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Cards Swiper -->
        <div class="swiper-service relative slideSw">
            <div class="swiper-container">
                <div class="swiper row">
                    <div class="swiper-wrapper">
                        <?php foreach ($data['items'] as $item) : ?>
                            <div class="swiper-slide col col-4 max-lg:!w-1/2 max-md:!w-3/4">
                                <?php get_template_part('partials/components/card-dich-vu', null, ['item' => $item]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination mt-6"></div>
        </div>

        <!-- CTA link -->
        <?php if (!empty($data['link']['url'])) : ?>
            <div class="flex justify-center mt-10">
                <a href="<?php echo esc_url($data['link']['url']); ?>"
                    target="<?php echo esc_attr($data['link']['target'] ?? ''); ?>"
                    class="flex items-center gap-2 text-[#133a35] text-[12px] font-semibold uppercase tracking-[1.2px]">
                    <?php echo esc_html($data['link']['title'] ?: 'XEM TẤT CẢ DỊCH VỤ'); ?>
                    <div class="size-[10px] shrink-0">
                        <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-arrow-right.svg"
                            class="block w-full h-full object-contain" alt="">
                    </div>
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>