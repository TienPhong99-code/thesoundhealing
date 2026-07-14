<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

// Bài do admin chọn (kéo thả để sắp thứ tự); để trống → 3 bài mới nhất của cả 2 post type
$raw_objects = get_field('cwlist_items', $page_id);

if (empty($raw_objects)) {
    $raw_objects = get_posts([
        'post_type'      => ['khoa_hoc', 'workshop'],
        'post_status'    => 'publish',
        'suppress_filters' => false, // WPML: chỉ lấy bài theo ngôn ngữ hiện tại
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}

$all_items = [];
foreach ($raw_objects as $post) {
    $thumb = get_the_post_thumbnail_url($post->ID, 'full');

    if ($post->post_type === 'workshop') {
        $terms     = get_the_terms($post->ID, 'loai_workshop');
        $type_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

        $all_items[] = [
            '_type'      => 'workshop',
            'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
            'type'       => $type_name,
            'format'     => get_field('ws_format',        $post->ID) ?: 'Onsite',
            'status'     => get_field('ws_status',        $post->ID) ?: 'open',
            'date'       => mona_schedule_label($post->ID) ?: 'Sắp diễn ra',
            'is_past'    => mona_schedule_is_past($post->ID),
            'time'       => get_field('ws_time',          $post->ID) ?: '09:00 – 12:00',
            'duration'   => get_field('ws_duration',      $post->ID) ?: '3 giờ',
            'title'      => $post->post_title,
            'location'   => get_field('ws_location',      $post->ID),
            'instructor' => get_field('ws_instructor_name', $post->ID),
            'desc'       => get_field('ws_short_desc',    $post->ID),
            'price'      => get_field('ws_price',         $post->ID) ?: 'Liên hệ',
            'spots'      => get_field('ws_spots',         $post->ID),
            'url'        => get_permalink($post->ID),
        ];

        continue;
    }

    $terms     = get_the_terms($post->ID, 'bo_mon_khoa_hoc');
    $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    $all_items[] = [
        '_type'      => 'khoa_hoc',
        'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'term'       => $term_name,
        'level'      => get_field('level',           $post->ID),
        'format'     => get_field('kh_format',       $post->ID) ?: 'Onsite',
        'title'      => $post->post_title,
        'desc'       => get_field('short_desc',      $post->ID),
        'time'       => get_field('kh_time',         $post->ID) ?: '09:00 – 17:00',
        'start_date' => mona_schedule_label($post->ID) ?: 'Sắp khai giảng',
        'is_past'    => mona_schedule_is_past($post->ID),
        'duration'   => get_field('duration',        $post->ID) ?: 'Cuối tuần',
        'instructor' => get_field('instructor_name', $post->ID),
        'location'   => get_field('location',        $post->ID),
        'branch'     => get_field('kh_branch',       $post->ID),
        'status'     => get_field('kh_status',       $post->ID) ?: 'open',
        'price'      => get_field('price',           $post->ID) ?: 'Liên hệ',
        'spots'      => get_field('kh_spots',        $post->ID),
        'url'        => get_permalink($post->ID),
    ];
}

$data = [
    'heading'         => get_field('cwlist_heading', $page_id),
    'link_all'        => mona_section_link('page-template/page-khoa-hoc-workshop.php'),
    'items'           => $all_items,
];
?>

<section class="sec-courses-workshop bg-white section-pd-t">
    <div class="container">

        <!-- Header -->
        <div class="flex md:items-end justify-between mb-8 max-md:flex-col gap-4">
            <div>
                <?php if (!empty($data['heading'])) : ?>
                    <h2 class="font-title text-pri text-[32px] font-bold max-md:text-[24px]">
                        <?php echo esc_html($data['heading']); ?>
                    </h2>
                <?php endif; ?>
            </div>

            <?php if (!empty($data['link_all']['url'])) : ?>
                <a href="<?php echo esc_url($data['link_all']['url']); ?>"
                    target="<?php echo esc_attr($data['link_all']['target'] ?? ''); ?>"
                    class="flex items-center gap-1 shrink-0 text-pri text-[16px] font-semibold uppercase tracking-[1.2px]">
                    <?php echo esc_html($data['link_all']['title']); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Cards -->
        <div class="row">
            <?php foreach ($data['items'] as $item) : ?>
                <div class="col col-5i max-lg:!w-1/2 max-sm:!w-full">
                    <?php if ($item['_type'] === 'workshop') : ?>
                        <?php get_template_part('partials/components/card-workshop', null, ['item' => $item]); ?>
                    <?php else : ?>
                        <?php get_template_part('partials/components/card-khoa-hoc', null, ['item' => $item]); ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>