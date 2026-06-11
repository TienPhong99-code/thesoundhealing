<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$raw_link    = get_field('featured_link', $page_id);
$raw_objects = get_field('featured_items', $page_id);

if (empty($raw_objects)) return;

$data = [
    'label'   => get_field('featured_label', $page_id)   ?: 'SỰ KIỆN NỔI BẬT',
    'heading' => get_field('featured_heading', $page_id) ?: 'Các Sự Kiện Nổi Bật',
    'link'    => $raw_link ?: null,
    'items'   => [],
];

foreach ($raw_objects as $post) {
    $post_type = get_post_type($post->ID);
    $thumb     = get_the_post_thumbnail_url($post->ID, 'full');

    if ($post_type === 'dich_vu') {
        $terms = get_the_terms($post->ID, 'loai_dich_vu');
        $data['items'][] = [
            '_card_type'     => 'dich-vu',
            'image'          => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
            'format'         => get_field('dv_format',          $post->ID) ?: 'Onsite',
            'title'          => $post->post_title,
            'desc'           => get_field('dv_short_desc',      $post->ID),
            'available_days' => get_field('dv_available_days',  $post->ID),
            'duration'       => get_field('dv_duration',        $post->ID),
            'branch'         => get_field('dv_branch',          $post->ID),
            'location'       => get_field('dv_location',        $post->ID),
            'instructor'     => get_field('dv_instructor_name', $post->ID),
            'status'         => get_field('dv_status',          $post->ID) ?: 'open',
            'price'          => get_field('dv_price',           $post->ID),
            'spots'          => get_field('dv_spots',           $post->ID),
            'url'            => get_permalink($post->ID),
        ];
    } elseif ($post_type === 'khoa_hoc') {
        $data['items'][] = [
            '_card_type' => 'khoa-hoc',
            'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
            'format'     => get_field('kh_format',       $post->ID) ?: 'Onsite',
            'level'      => get_field('level',           $post->ID),
            'title'      => $post->post_title,
            'desc'       => get_field('short_desc',      $post->ID),
            'time'       => get_field('kh_time',         $post->ID),
            'start_date' => get_field('start_date',      $post->ID),
            'duration'   => get_field('duration',        $post->ID),
            'instructor' => get_field('instructor_name', $post->ID),
            'location'   => get_field('location',        $post->ID),
            'branch'     => get_field('kh_branch',       $post->ID),
            'status'     => get_field('kh_status',       $post->ID) ?: 'open',
            'price'      => get_field('price',           $post->ID),
            'spots'      => get_field('kh_spots',        $post->ID),
            'url'        => get_permalink($post->ID),
        ];
    } elseif ($post_type === 'workshop') {
        $terms = get_the_terms($post->ID, 'loai_workshop');
        $data['items'][] = [
            '_card_type' => 'workshop',
            'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
            'type'       => (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '',
            'format'     => get_field('ws_format',          $post->ID) ?: 'Onsite',
            'status'     => get_field('ws_status',          $post->ID) ?: 'open',
            'date'       => get_field('ws_date',            $post->ID),
            'time'       => get_field('ws_time',            $post->ID),
            'duration'   => get_field('ws_duration',        $post->ID),
            'title'      => $post->post_title,
            'location'   => get_field('ws_location',        $post->ID),
            'instructor' => get_field('ws_instructor_name', $post->ID),
            'desc'       => get_field('ws_short_desc',      $post->ID),
            'price'      => get_field('ws_price',           $post->ID),
            'spots'      => get_field('ws_spots',           $post->ID),
            'url'        => get_permalink($post->ID),
        ];
    }
}

if (empty($data['items'])) return;
?>

<section class="sec-featured section-pd-t">
    <div class="container">

        <!-- Header -->
        <div class="flex items-end justify-between mb-8 max-md:flex-col max-md:items-center max-md:text-center gap-4">
            <div>
                <p class="text-pri text-[16px] font-semibold uppercase tracking-[1.2px] mb-4">
                    <?php echo esc_html($data['label']); ?>
                </p>
                <h2 class="font-title text-sec text-[32px] font-normal max-md:text-[24px]">
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
                <div class="col col-5i max-lg:!w-1/2">
                    <?php
                    $card_type = $item['_card_type'];
                    unset($item['_card_type']);
                    get_template_part('partials/components/card-' . $card_type, null, ['item' => $item]);
                    ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>