<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'SỰ KIỆN SẮP TỚI',
    'heading' => 'Workshop & Trải Nghiệm',
    'link'    => ['url' => home_url('/workshop'), 'title' => 'XEM TẤT CẢ', 'target' => ''],
    'items'   => [
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Sound Bath Buổi Tối'],
            'type'     => 'Workshop Âm Thanh',
            'status'   => 'open',
            'date'     => '18 THÁNG 1, 2025',
            'time'     => '19:00 – 21:00',
            'title'    => 'Sound Bath Buổi Tối',
            'location' => 'Aetheria Studio — Quận 1, TP.HCM',
            'desc'     => 'Buổi tắm âm thanh thư giãn cuối tuần với bát pha lê và trống Himalaya.',
            'price'    => '850.000 VNĐ',
            'url'      => '#',
        ],
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Nhập Môn Reiki'],
            'type'     => 'Workshop Năng Lượng',
            'status'   => 'limited',
            'date'     => '25 THÁNG 1, 2025',
            'time'     => '09:00 – 17:00',
            'title'    => 'Nhập Môn Reiki',
            'location' => 'Aetheria Studio — Quận 1, TP.HCM',
            'desc'     => 'Trải nghiệm một ngày khám phá năng lượng Reiki cơ bản cho người mới bắt đầu.',
            'price'    => '1.500.000 VNĐ',
            'url'      => '#',
        ],
        [
            'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Hòa Âm Gong Thiêng'],
            'type'     => 'Workshop Âm Thanh',
            'status'   => 'upcoming',
            'date'     => '8 THÁNG 2, 2025',
            'time'     => '18:00 – 20:30',
            'title'    => 'Hòa Âm Gong Thiêng',
            'location' => 'Aetheria Studio — Quận 1, TP.HCM',
            'desc'     => 'Đắm chìm trong tần số nguyên thủy của trống gong — thiền sâu qua âm thanh.',
            'price'    => '1.200.000 VNĐ',
            'url'      => '#',
        ],
    ],
];

$raw_link    = get_field('ws_home_link', $page_id);
$raw_objects = get_field('ws_home_items', $page_id);

if (empty($raw_objects)) {
    $raw_objects = get_posts([
        'post_type'      => 'workshop',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ]);
}

$acf_items = [];
foreach ($raw_objects as $post) {
    $thumb = get_the_post_thumbnail_url($post->ID, 'full');

    $terms     = get_the_terms($post->ID, 'loai_workshop');
    $type_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    $acf_items[] = [
        'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'type'     => $type_name,
        'status'   => get_field('ws_status', $post->ID) ?: 'open',
        'date'     => get_field('ws_date', $post->ID),
        'time'     => get_field('ws_time', $post->ID),
        'title'    => $post->post_title,
        'location' => get_field('ws_location', $post->ID),
        'desc'     => get_field('ws_short_desc', $post->ID),
        'price'    => get_field('ws_price', $post->ID),
        'url'      => get_permalink($post->ID),
    ];
}

$data = [
    'label'   => get_field('ws_home_label', $page_id)   ?: $sample['label'],
    'heading' => get_field('ws_home_heading', $page_id) ?: $sample['heading'],
    'link'    => $raw_link                              ?: $sample['link'],
    'items'   => $acf_items                            ?: $sample['items'],
];
?>

<section class="sec-home-workshop relative py-(--pd-sc)">
    <span class="absolute inset-0 bg-[#fbf9f4] z-[-1]"></span>

    <div class="container">
        <!-- Header -->
        <div class="flex items-end justify-between mb-12 max-md:flex-col max-md:items-center max-md:text-center gap-4">
            <div>
                <p class="text-pri text-[12px] font-semibold uppercase tracking-[1.2px] mb-4">
                    <?php echo esc_html($data['label']); ?>
                </p>
                <h2 class="font-title text-sec text-[32px] font-normal max-md:text-[24px]">
                    <?php echo esc_html($data['heading']); ?>
                </h2>
            </div>

            <?php if (!empty($data['link']['url'])) : ?>
                <a href="<?php echo esc_url($data['link']['url']); ?>"
                    target="<?php echo esc_attr($data['link']['target'] ?? ''); ?>"
                    class="flex items-center gap-1 text-pri text-[12px] font-semibold uppercase tracking-[1.2px] shrink-0">
                    <?php echo esc_html($data['link']['title'] ?: 'XEM TẤT CẢ'); ?>
                    <div class="size-[10px] shrink-0">
                        <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-arrow-right.svg"
                            class="block w-full h-full object-contain" alt="">
                    </div>
                </a>
            <?php endif; ?>
        </div>

        <!-- Cards -->
        <div class="swiper-workshop relative slideSw">
            <div class="swiper-container">
                <div class="swiper row">
                    <div class="swiper-wrapper">
                        <?php foreach ($data['items'] as $item) : ?>
                            <div class="swiper-slide col col-4 max-lg:!w-1/2 max-md:!w-3/4">
                                <?php get_template_part('partials/components/card-workshop', null, ['item' => $item]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination mt-6"></div>
        </div>
    </div>
</section>