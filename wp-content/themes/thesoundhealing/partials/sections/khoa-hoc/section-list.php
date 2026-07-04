<?php
defined('ABSPATH') || exit;

$query = new WP_Query([
    'post_type'      => 'khoa_hoc',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$sample = [
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Hoà âm 7 chuông pha lê'],
        'level'      => 'Foundation',
        'term'       => 'Bộ Môn Âm Thanh',
        'format'     => 'Onsite',
        'title'      => 'Hoà âm 7 chuông pha lê',
        'time'       => '09:00 – 17:00',
        'start_date' => '15 THÁNG 2, 2025',
        'duration'   => '4 tuần · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '12.000.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Liệu pháp chuông đồng'],
        'level'      => 'Mastery',
        'term'       => 'Bộ Môn Âm Thanh',
        'format'     => 'Onsite',
        'title'      => 'Liệu pháp chuông đồng',
        'time'       => '09:00 – 17:00',
        'start_date' => '01 THÁNG 3, 2025',
        'duration'   => '2 ngày · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '15.000.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Liệu pháp Sound Bath'],
        'level'      => 'Chuyên sâu',
        'term'       => 'Bộ Môn Âm Thanh',
        'format'     => 'Onsite',
        'title'      => 'Liệu pháp Sound Bath',
        'time'       => '09:00 – 17:00',
        'start_date' => '15 THÁNG 3, 2025',
        'duration'   => '6 tuần · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '20.000.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Năng Lượng Usui Reiki Level 2'],
        'level'      => 'Nâng cao',
        'term'       => 'Bộ Môn Năng Lượng',
        'format'     => 'Onsite',
        'title'      => 'Năng Lượng Usui Reiki Level 2',
        'time'       => '09:00 – 17:00',
        'start_date' => '05 THÁNG 4, 2025',
        'duration'   => '2 ngày · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '5.500.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Thiền Định Nâng Cao'],
        'level'      => 'Intermediate',
        'term'       => 'Bộ Môn Thiền Định',
        'format'     => 'Onsite',
        'title'      => 'Thiền Định Nâng Cao',
        'time'       => '09:00 – 17:00',
        'start_date' => '19 THÁNG 4, 2025',
        'duration'   => '3 tuần · Thứ 3, 5, 7',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '6.800.000 VNĐ',
        'url'        => '#',
    ],
    [
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Hòa Âm Trống Shaman'],
        'level'      => 'Foundation',
        'term'       => 'Bộ Môn Âm Thanh',
        'format'     => 'Onsite',
        'title'      => 'Hòa Âm Trống Shaman',
        'time'       => '09:00 – 17:00',
        'start_date' => '10 THÁNG 5, 2025',
        'duration'   => '2 ngày · Cuối tuần',
        'instructor' => 'Linh Tâm',
        'location'   => 'Aetheria Studio — Quận 1, TP.HCM',
        'branch'     => 'Cơ sở Quận 1',
        'status'     => 'open',
        'spots'      => 15,
        'price'      => '8.000.000 VNĐ',
        'url'        => '#',
    ],
];

$use_sample = !$query->have_posts();

$items = [];
if ($use_sample) {
    $items = $sample;
} else {
    while ($query->have_posts()) {
        $query->the_post();
        $post_id   = get_the_ID();
        $thumb     = get_the_post_thumbnail_url($post_id, 'full');
        $terms     = get_the_terms($post_id, 'bo_mon_khoa_hoc');
        $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'level'      => get_field('level',           $post_id),
            'term'       => $term_name,
            'format'     => get_field('kh_format',       $post_id) ?: 'Onsite',
            'title'      => get_the_title($post_id),
            'time'       => get_field('kh_time',         $post_id) ?: '09:00 – 17:00',
            'start_date' => mona_schedule_label($post_id) ?: 'Sắp khai giảng',
            'duration'   => get_field('duration',        $post_id) ?: 'Cuối tuần',
            'instructor' => get_field('instructor_name', $post_id),
            'location'   => get_field('location',        $post_id),
            'branch'     => get_field('kh_branch',       $post_id),
            'status'     => get_field('kh_status',       $post_id) ?: 'open',
            'price'      => get_field('price',           $post_id) ?: 'Liên hệ',
            'spots'      => get_field('kh_spots',        $post_id),
            'url'        => get_permalink($post_id),
            'post_id'    => $post_id,
        ];
    }
    wp_reset_postdata();
}

$_kh_page_id      = get_queried_object_id();
$_kh_list_heading = get_field('kh_page_heading', $_kh_page_id) ?: 'Khoá Học';
?>

<section class="sec-kh-list section-pd">
    <div class="container">
        <h2 class="font-title text-pri text-[40px] leading-[48px] font-bold tracking-[-0.8px] mb-10 max-md:text-[32px] max-md:leading-[40px] max-md:mb-8">
            <?php echo wp_kses_post($_kh_list_heading); ?>
        </h2>
        <div class="row js-kh-list-grid">
            <?php foreach ($items as $item) : ?>
                <div class="col col-5i max-lg:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-khoa-hoc', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>