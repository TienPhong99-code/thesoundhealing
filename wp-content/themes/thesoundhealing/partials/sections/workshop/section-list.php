<?php
defined('ABSPATH') || exit;

$query = new WP_Query([
    'post_type'      => 'workshop',
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
        $terms     = get_the_terms($post_id, 'loai_workshop');
        $type_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $items[] = [
            'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
            'type'       => $type_name,
            'format'     => get_field('ws_format',        $post_id) ?: 'Onsite',
            'status'     => get_field('ws_status',        $post_id) ?: 'open',
            'date'       => mona_schedule_label($post_id) ?: 'Sắp diễn ra',
            'time'       => get_field('ws_time',          $post_id) ?: '09:00 – 12:00',
            'duration'   => get_field('ws_duration',      $post_id) ?: '3 giờ',
            'title'      => get_the_title($post_id),
            'location'   => get_field('ws_location',      $post_id),
            'instructor' => get_field('ws_instructor_name', $post_id),
            'desc'       => get_field('ws_short_desc',    $post_id),
            'price'      => get_field('ws_price',         $post_id) ?: 'Liên hệ',
            'spots'      => get_field('ws_spots',         $post_id),
            'url'        => get_permalink($post_id),
            'post_id'    => $post_id,
        ];
    }
    wp_reset_postdata();
}
?>

<section class="sec-ws-list pt-0 pb-(--pd-sc)">
    <div class="container">
        <div class="row js-ws-list-grid">
            <?php foreach ($items as $item) : ?>
                <div class="col col-5i max-md:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-workshop', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>