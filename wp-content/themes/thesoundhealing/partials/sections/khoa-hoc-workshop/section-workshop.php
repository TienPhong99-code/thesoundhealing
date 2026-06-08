<?php
defined('ABSPATH') || exit;

$query = new WP_Query([
    'post_type'      => 'workshop',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$sample = [
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Sound Bath Buổi Tối'],
        'type'     => 'Workshop Âm Thanh',
        'status'   => 'open',
        'date'     => '18 THÁNG 1, 2025',
        'time'     => '19:00 – 21:00',
        'title'    => 'Sound Bath Buổi Tối',
        'location' => 'Aetheria Studio — Quận 1, TP.HCM',
        'desc'     => 'Buổi tắm âm thanh thư giãn cuối tuần với bát pha lê và trống Himalaya, dành cho mọi trình độ.',
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
        'desc'     => 'Trải nghiệm một ngày khám phá năng lượng Reiki cơ bản — phù hợp cho người chưa có kiến thức trước.',
        'price'    => '1.500.000 VNĐ',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Hòa Âm Gong'],
        'type'     => 'Workshop Âm Thanh',
        'status'   => 'upcoming',
        'date'     => '8 THÁNG 2, 2025',
        'time'     => '18:00 – 20:30',
        'title'    => 'Hòa Âm Gong Thiêng',
        'location' => 'Aetheria Studio — Quận 1, TP.HCM',
        'desc'     => 'Đắm chìm trong tần số nguyên thủy của trống gong — hành trình đi sâu vào trạng thái thiền sâu.',
        'price'    => '1.200.000 VNĐ',
        'url'      => '#',
    ],
];

$use_sample = !$query->have_posts();
$items      = [];

if ($use_sample) {
    $items = $sample;
} else {
    while ($query->have_posts()) {
        $query->the_post();
        $post_id   = get_the_ID();
        $thumb     = get_the_post_thumbnail_url($post_id, 'full');
        $terms     = get_the_terms($post_id, 'loai_workshop');
        $type_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'type'     => $type_name,
            'status'   => get_field('ws_status', $post_id) ?: 'open',
            'date'     => get_field('ws_date', $post_id),
            'time'     => get_field('ws_time', $post_id),
            'title'    => get_the_title($post_id),
            'location' => get_field('ws_location', $post_id),
            'desc'     => get_field('ws_short_desc', $post_id),
            'price'    => get_field('ws_price', $post_id),
            'url'      => get_permalink($post_id),
        ];
    }
    wp_reset_postdata();
}
?>

<section class="sec-khws-ws-list pt-0 pb-(--pd-sc)">
    <div class="container">
        <h2 class="font-title text-pri text-[40px] font-normal tracking-[-0.8px] leading-[48px] mb-10 max-md:text-[28px] max-md:mb-6">
            Workshop
        </h2>
        <div class="row">
            <?php foreach ($items as $item) : ?>
                <div class="col col-2 max-md:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-workshop', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>