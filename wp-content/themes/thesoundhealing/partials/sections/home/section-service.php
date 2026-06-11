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
            'image'          => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tam-am-ngu-ngon-nhom.jpg', 'alt' => 'Tắm Âm Ngủ Ngon (Nhóm)'],
            'category'       => 'SOUND HEALING',
            'title'          => 'Tắm Âm Ngủ Ngon (Nhóm)',
            'desc'           => 'Trải nghiệm sóng âm thư giãn cùng nhóm để cải thiện chất lượng giấc ngủ và giảm căng thẳng tích tụ.',
            'available_days' => 'Thứ 6, 7, Chủ nhật',
            'duration'       => '60 - 90 phút mỗi phiên',
            'branch'         => 'Cơ sở Quận 1',
            'location'       => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor'     => 'Linh Tâm',
            'status'         => 'open',
            'spots'          => 12,
            'price'          => '500.000 VNĐ',
            'url'            => '#',
        ],
        [
            'image'          => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tam-am-ngu-ngon-rieng-tu.jpg', 'alt' => 'Tắm Âm Ngủ Ngon (Riêng Tư)'],
            'category'       => 'PRIVATE EXPERIENCE',
            'title'          => 'Tắm Âm Ngủ Ngon (Riêng Tư)',
            'desc'           => 'Không gian trị liệu âm thanh dành riêng cho bạn, tập trung vào nhu cầu phục hồi sâu sắc của cá nhân.',
            'available_days' => 'Thứ 2 – Chủ nhật',
            'duration'       => '90 phút mỗi phiên',
            'branch'         => 'Cơ sở Quận 1',
            'location'       => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor'     => 'Linh Tâm',
            'status'         => 'open',
            'spots'          => 2,
            'price'          => '1.200.000 VNĐ',
            'url'            => '#',
        ],
        [
            'image'          => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-tri-lieu-chuong-do-rieng-tu.jpg', 'alt' => 'Trị Liệu Chuông Đồ (Riêng Tư)'],
            'category'       => 'VIBRATIONAL THERAPY',
            'title'          => 'Trị Liệu Chuông Đồ (Riêng Tư)',
            'desc'           => 'Kỹ thuật đặt chuông trực tiếp lên cơ thể để các rung động tác động sâu vào từng tế bào và huyệt đạo.',
            'available_days' => 'Thứ 2 – Thứ 6',
            'duration'       => '60 phút mỗi phiên',
            'branch'         => 'Cơ sở Quận 1',
            'location'       => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor'     => 'Linh Tâm',
            'status'         => 'open',
            'spots'          => 1,
            'price'          => '800.000 VNĐ',
            'url'            => '#',
        ],
        [
            'image'          => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-chua-lanh-reiki-nhom.jpg', 'alt' => 'Chữa Lành Usui Reiki (Group)'],
            'category'       => 'ENERGY HEALING',
            'title'          => 'Chữa Lành Usui Reiki (Group)',
            'desc'           => 'Kết nối năng lượng vũ trụ trong không gian cộng hưởng nhóm để thanh tẩy và cân bằng tâm trí.',
            'available_days' => 'Thứ 7, Chủ nhật',
            'duration'       => '60 - 90 phút mỗi phiên',
            'branch'         => 'Cơ sở Quận 1',
            'location'       => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor'     => 'Linh Tâm',
            'status'         => 'open',
            'spots'          => 10,
            'price'          => '600.000 VNĐ',
            'url'            => '#',
        ],
        [
            'image'          => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-chua-lanh-reiki-rieng-tu.jpg', 'alt' => 'Chữa Lành Usui Reiki (Riêng Tư)'],
            'category'       => 'ENERGY HEALING',
            'title'          => 'Chữa Lành Usui Reiki (Riêng Tư)',
            'desc'           => 'Phiên trị liệu năng lượng chuyên sâu 1-1 giúp giải quyết các tắc nghẽn cảm xúc và thể chất cụ thể.',
            'available_days' => 'Thứ 2 – Thứ 6',
            'duration'       => '90 phút mỗi phiên',
            'branch'         => 'Cơ sở Quận 1',
            'location'       => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor'     => 'Linh Tâm',
            'status'         => 'limited',
            'spots'          => 1,
            'price'          => '1.500.000 VNĐ',
            'url'            => '#',
        ],
        [
            'image'          => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-khai-van-huyen-hoc.jpg', 'alt' => 'Khai Vấn Dự Đoán Huyền Học'],
            'category'       => 'INTUITIVE ARTS',
            'title'          => 'Khai Vấn Dự Đoán Huyền Học',
            'desc'           => 'Sử dụng Soul Mirror Cards để soi chiếu nội tâm và tìm kiếm những chỉ dẫn trực giác cho hành trình sống.',
            'available_days' => 'Thứ 3, 5, 7',
            'duration'       => '60 phút mỗi phiên',
            'branch'         => 'Cơ sở Quận 1',
            'location'       => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor'     => 'Linh Tâm',
            'status'         => 'open',
            'spots'          => 1,
            'price'          => '700.000 VNĐ',
            'url'            => '#',
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
        'image'          => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'category'       => $term_name,
        'format'         => get_field('dv_format',         $post->ID) ?: 'Onsite',
        'title'          => $post->post_title,
        'desc'           => get_field('dv_short_desc',     $post->ID),
        'available_days' => get_field('dv_available_days', $post->ID),
        'duration'       => get_field('dv_duration',       $post->ID),
        'branch'         => get_field('dv_branch',         $post->ID),
        'location'       => get_field('dv_location',       $post->ID),
        'instructor'     => get_field('dv_instructor_name', $post->ID),
        'status'         => get_field('dv_status',         $post->ID) ?: '',
        'price'          => get_field('dv_price',          $post->ID),
        'spots'          => get_field('dv_spots',          $post->ID),
        'url'            => get_permalink($post->ID),
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

<section class="sec-service bg-white section-pd-t">
    <div class="container">

        <!-- Header -->
        <div class="flex items-end justify-between mb-8 max-md:flex-col max-md:items-center max-md:text-center gap-4">
            <div>
                <h2 class="font-title text-sec text-[32px] font-normal max-md:text-[24px] mb-3">
                    <?php echo esc_html($data['heading']); ?>
                </h2>
                <?php if (!empty($data['desc'])) : ?>
                    <p class="text-[#414847] max-w-[500px]">
                        <?php echo wp_kses_post($data['desc']); ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if (!empty($data['link']['url'])) : ?>
                <a href="<?php echo esc_url($data['link']['url']); ?>"
                    target="<?php echo esc_attr($data['link']['target'] ?? ''); ?>"
                    class="flex items-center gap-1 text-pri text-[16px] font-semibold uppercase tracking-[1.2px] shrink-0">
                    <?php echo esc_html($data['link']['title'] ?: 'XEM TẤT CẢ'); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Cards -->
        <div class="row">
            <?php foreach ($data['items'] as $item) : ?>
                <div class="col col-5i max-lg:!w-1/2">
                    <?php get_template_part('partials/components/card-dich-vu', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA link -->
        <!-- <?php if (!empty($data['link']['url'])) : ?>
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
        <?php endif; ?> -->

    </div>
</section>