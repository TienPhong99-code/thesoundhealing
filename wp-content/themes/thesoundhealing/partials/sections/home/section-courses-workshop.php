<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

// --- Courses ---
$course_posts = get_posts([
    'post_type'      => 'khoa_hoc',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

$course_items = [];
foreach ($course_posts as $post) {
    $thumb     = get_the_post_thumbnail_url($post->ID, 'full');
    $terms     = get_the_terms($post->ID, 'bo_mon_khoa_hoc');
    $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    $course_items[] = [
        '_type'      => 'khoa_hoc',
        'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'term'       => $term_name,
        'level'      => get_field('level', $post->ID),
        'title'      => $post->post_title,
        'desc'       => get_field('short_desc', $post->ID),
        'start_date' => get_field('start_date', $post->ID),
        'duration'   => get_field('duration', $post->ID),
        'instructor' => get_field('instructor_name', $post->ID),
        'price'      => get_field('price', $post->ID),
        'url'        => get_permalink($post->ID),
        '_date_sort' => get_field('start_date', $post->ID) ?: $post->post_date,
    ];
}

// --- Workshops ---
$workshop_posts = get_posts([
    'post_type'      => 'workshop',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order date',
    'order'          => 'ASC',
]);

$workshop_items = [];
foreach ($workshop_posts as $post) {
    $thumb     = get_the_post_thumbnail_url($post->ID, 'full');
    $terms     = get_the_terms($post->ID, 'loai_workshop');
    $type_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    $workshop_items[] = [
        '_type'      => 'workshop',
        'image'      => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'type'       => $type_name,
        'status'     => get_field('ws_status', $post->ID) ?: 'open',
        'date'       => get_field('ws_date', $post->ID),
        'time'       => get_field('ws_time', $post->ID),
        'title'      => $post->post_title,
        'location'   => get_field('ws_location', $post->ID),
        'desc'       => get_field('ws_short_desc', $post->ID),
        'price'      => get_field('ws_price', $post->ID),
        'url'        => get_permalink($post->ID),
        '_date_sort' => get_field('ws_date', $post->ID) ?: $post->post_date,
    ];
}

// Merge & sort by date ascending
$all_items = array_merge($course_items, $workshop_items);
usort($all_items, function ($a, $b) {
    return strcmp($a['_date_sort'], $b['_date_sort']);
});

$data = [
    'label'           => get_field('cwlist_label', $page_id)   ?: 'ĐÀO TẠO & SỰ KIỆN',
    'heading'         => get_field('cwlist_heading', $page_id) ?: 'Khóa Học & Workshop',
    'link_courses'    => get_field('cwlist_link_courses', $page_id)  ?: ['url' => home_url('/khoa-hoc'),  'title' => 'Khóa học', 'target' => ''],
    'link_workshops'  => get_field('cwlist_link_workshops', $page_id) ?: ['url' => home_url('/workshop'), 'title' => 'Workshop', 'target' => ''],
    'items'           => $all_items,
];
?>

<section class="sec-courses-workshop bg-white section-pd-t">
    <div class="container">

        <!-- Header -->
        <div class="flex items-end justify-between mb-8 max-md:flex-col max-md:items-center max-md:text-center gap-4">
            <div>
                <p class="text-pri text-[12px] font-semibold uppercase tracking-[1.2px] mb-4">
                    <?php echo esc_html($data['label']); ?>
                </p>
                <h2 class="font-title text-sec text-[32px] font-normal max-md:text-[24px]">
                    <?php echo esc_html($data['heading']); ?>
                </h2>
            </div>

            <div class="flex items-center gap-4 shrink-0">
                <?php if (!empty($data['link_courses']['url'])) : ?>
                    <a href="<?php echo esc_url($data['link_courses']['url']); ?>"
                        target="<?php echo esc_attr($data['link_courses']['target'] ?? ''); ?>"
                        class="flex items-center gap-1 text-pri text-[12px] font-semibold uppercase tracking-[1.2px]">
                        <?php echo esc_html($data['link_courses']['title']); ?>
                        <div class="size-[10px] shrink-0">
                            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-arrow-right.svg"
                                class="block w-full h-full object-contain" alt="">
                        </div>
                    </a>
                <?php endif; ?>

                <?php if (!empty($data['link_workshops']['url'])) : ?>
                    <a href="<?php echo esc_url($data['link_workshops']['url']); ?>"
                        target="<?php echo esc_attr($data['link_workshops']['target'] ?? ''); ?>"
                        class="flex items-center gap-1 text-pri text-[12px] font-semibold uppercase tracking-[1.2px]">
                        <?php echo esc_html($data['link_workshops']['title']); ?>
                        <div class="size-[10px] shrink-0">
                            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-arrow-right.svg"
                                class="block w-full h-full object-contain" alt="">
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Cards -->
        <div class="row">
            <?php foreach ($data['items'] as $item) : ?>
                <div class="col col-2 max-lg:!w-1/2 max-md:!w-full">
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