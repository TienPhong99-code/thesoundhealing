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
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-1.png', 'alt' => 'Hoà âm 7 chuông pha lê'],
        'level'    => 'FOUNDATION',
        'term'     => 'Bộ Môn Âm Thanh',
        'title'    => 'Hoà âm 7 chuông pha lê',
        'desc'     => 'Nắm vững kỹ thuật chơi và hoà âm 7 luân xa với chuông pha lê, mang lại sự cân bằng sâu sắc cho cơ thể và tâm trí.',
        'price'    => '12.000.000 VNĐ',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-2.png', 'alt' => 'Liệu pháp chuông đồng'],
        'level'    => 'MASTERY',
        'term'     => 'Bộ Môn Âm Thanh',
        'title'    => 'Liệu pháp chuông đồng',
        'desc'     => 'Khám phá nghệ thuật chữa lành cổ xưa qua rung động vật lý của chuông đồng nguyên bản Himalaya.',
        'price'    => '15.000.000 VNĐ',
        'url'      => '#',
    ],
    [
        'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/courses-img-3.png', 'alt' => 'Liệu pháp Sound Bath'],
        'level'    => 'ADVANCED',
        'term'     => 'Bộ Môn Âm Thanh',
        'title'    => 'Liệu pháp Sound Bath',
        'desc'     => 'Đào tạo chuyên sâu kỹ năng tổ chức và dẫn dắt các buổi tắm âm thanh trị liệu chuyên nghiệp.',
        'price'    => '20.000.000 VNĐ',
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
        $terms     = get_the_terms($post_id, 'bo_mon_khoa_hoc');
        $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'level'      => get_field('level',           $post_id),
            'term'       => $term_name,
            'title'      => get_the_title($post_id),
            'desc'       => get_field('short_desc',      $post_id),
            'start_date' => get_field('start_date',      $post_id),
            'duration'   => get_field('duration',        $post_id),
            'instructor' => get_field('instructor_name', $post_id),
            'location'   => get_field('location',        $post_id),
            'branch'     => get_field('kh_branch',       $post_id),
            'status'     => get_field('kh_status',       $post_id) ?: '',
            'price'      => get_field('price',           $post_id),
            'spots'      => get_field('kh_spots',        $post_id),
            'url'        => get_permalink($post_id),
        ];
    }
    wp_reset_postdata();
}
?>

<section class="sec-khws-kh-list pt-0 pb-(--pd-sc)">
    <div class="container">
        <h2 class="font-title text-pri text-[40px] font-normal tracking-[-0.8px] leading-[48px] mb-10 max-md:text-[28px] max-md:mb-6">
            Khóa Học
        </h2>
        <div class="row">
            <?php foreach ($items as $item) : ?>
                <div class="col col-2 max-lg:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-khoa-hoc', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>