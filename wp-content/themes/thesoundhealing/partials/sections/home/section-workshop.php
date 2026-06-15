<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'SỰ KIỆN SẮP TỚI',
    'heading' => 'Các sự kiện nổi bật',
    'link'    => ['url' => home_url('/workshop'), 'title' => 'XEM TẤT CẢ', 'target' => ''],
    'items'   => [
        [
            'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Sound Bath Buổi Tối'],
            'type'       => 'Workshop Âm Thanh',
            'format'     => 'Onsite',
            'status'     => 'open',
            'date'       => '18 THÁNG 1, 2025',
            'time'       => '19:00 – 21:00',
            'duration'   => '2 giờ',
            'title'      => 'Sound Bath Buổi Tối',
            'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor' => 'Linh Tâm',
            'desc'       => 'Buổi tắm âm thanh thư giãn cuối tuần với bát pha lê và trống Himalaya.',
            'spots'      => 18,
            'price'      => '850.000 VNĐ',
            'url'        => '#',
        ],
        [
            'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Nhập Môn Reiki'],
            'type'       => 'Workshop Năng Lượng',
            'format'     => 'Onsite',
            'status'     => 'limited',
            'date'       => '25 THÁNG 1, 2025',
            'time'       => '09:00 – 17:00',
            'duration'   => '8 giờ',
            'title'      => 'Nhập Môn Reiki',
            'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor' => 'Linh Tâm',
            'desc'       => 'Trải nghiệm một ngày khám phá năng lượng Reiki cơ bản cho người mới bắt đầu.',
            'spots'      => 4,
            'price'      => '1.500.000 VNĐ',
            'url'        => '#',
        ],
        [
            'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Hòa Âm Gong Thiêng'],
            'type'       => 'Workshop Âm Thanh',
            'format'     => 'Onsite',
            'status'     => 'upcoming',
            'date'       => '8 THÁNG 2, 2025',
            'time'       => '18:00 – 20:30',
            'duration'   => '2.5 giờ',
            'title'      => 'Hòa Âm Gong Thiêng',
            'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
            'instructor' => 'Linh Tâm',
            'desc'       => 'Đắm chìm trong tần số nguyên thủy của trống gong — thiền sâu qua âm thanh.',
            'spots'      => 0,
            'price'      => '1.200.000 VNĐ',
            'url'        => '#',
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
        'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'type'       => $type_name,
        'format'     => get_field('ws_format',       $post->ID) ?: 'Onsite',
        'status'     => get_field('ws_status',       $post->ID) ?: 'open',
        'date'       => get_field('ws_date',         $post->ID) ?: 'Sắp diễn ra',
        'time'       => get_field('ws_time',         $post->ID) ?: '09:00 – 12:00',
        'duration'   => get_field('ws_duration',     $post->ID) ?: '3 giờ',
        'title'      => $post->post_title,
        'location'   => get_field('ws_location',     $post->ID),
        'instructor' => get_field('ws_instructor_name', $post->ID),
        'desc'       => get_field('ws_short_desc',   $post->ID),
        'price'      => get_field('ws_price',        $post->ID) ?: 'Liên hệ',
        'spots'      => get_field('ws_spots',        $post->ID),
        'url'        => get_permalink($post->ID),
    ];
}

$data = [
    'label'   => get_field('ws_home_label', $page_id)   ?: $sample['label'],
    'heading' => get_field('ws_home_heading', $page_id) ?: $sample['heading'],
    'link'    => $raw_link                              ?: $sample['link'],
    'items'   => $acf_items                            ?: $sample['items'],
];
?>

<section class="sec-home-workshop relative section-pd-t">
    <span class="absolute inset-0 z-[-1]"></span>

    <div class="container">
        <!-- Header -->
        <div class="flex items-end justify-between mb-8 max-md:flex-col max-md:items-center max-md:text-center gap-4">
            <div>
                <p class="text-pri text-[16px] font-semibold  tracking-[1.2px] mb-4">
                    <?php echo esc_html($data['label']); ?>
                </p>
                <h2 class="font-title text-pri text-[32px] font-bold max-md:text-[24px]">
                    <?php echo esc_html($data['heading']); ?>
                </h2>
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
                <div class="col col-5i max-lg:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-workshop', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>