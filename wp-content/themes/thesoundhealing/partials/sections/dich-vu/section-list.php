<?php
defined('ABSPATH') || exit;

$query = new WP_Query([
    'post_type'      => 'dich_vu',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$items = [];

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $post_id   = get_the_ID();
        $thumb     = get_the_post_thumbnail_url($post_id, 'full');
        $terms     = get_the_terms($post_id, 'loai_dich_vu');
        $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'          => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'category'       => $term_name,
            'format'         => get_field('dv_format',          $post_id) ?: 'Onsite',
            'title'          => get_the_title($post_id),
            'desc'           => get_field('dv_short_desc',      $post_id),
            'available_days' => mona_schedule_label($post_id) ?: 'Thứ 2 – Chủ nhật',
            'duration'       => get_field('dv_duration',        $post_id) ?: '60 - 90 phút mỗi phiên',
            'branch'         => get_field('dv_branch',          $post_id),
            'location'       => get_field('dv_location',        $post_id),
            'instructor'     => get_field('dv_instructor_name', $post_id),
            'status'         => get_field('dv_status',          $post_id) ?: 'open',
            'price'          => get_field('dv_price',           $post_id) ?: 'Liên hệ',
            'spots'          => get_field('dv_spots',           $post_id),
            'best_seller'    => get_field('dv_best_seller',     $post_id),
            'url'            => get_permalink($post_id),
            'post_id'        => $post_id,
        ];
    }
    wp_reset_postdata();
}

$_dv_page_id      = get_queried_object_id();
$_dv_list_heading = get_field('dv_page_heading', $_dv_page_id) ?: __('Dịch Vụ', 'monamedia');
?>

<section class="sec-dv-list section-pd max-md:!pt-20">
    <div class="container">
        <h2 class="font-title text-pri text-[40px] leading-[48px] font-bold tracking-[-0.8px] mb-10 max-md:text-[32px] max-md:leading-[40px] max-md:mb-8">
            <?php echo wp_kses_post($_dv_list_heading); ?>
        </h2>
        <div class="row js-dv-list-grid">
            <?php foreach ($items as $item) : ?>
                <div class="col col-5i max-lg:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-dich-vu', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>