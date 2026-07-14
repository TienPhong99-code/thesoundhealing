<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$raw_objects = get_field('service_items', $page_id);

if (empty($raw_objects)) {
    $raw_objects = get_posts([
        'post_type'      => 'dich_vu',
        'post_status'    => 'publish',
        'suppress_filters' => false, // WPML: chỉ lấy bài theo ngôn ngữ hiện tại
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}

$acf_items = [];
foreach ($raw_objects as $post) {
    $thumb     = get_the_post_thumbnail_url($post->ID, 'full');
    $terms     = get_the_terms($post->ID, 'loai_dich_vu');
    $term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

    $acf_items[] = [
        'image'          => ['url' => $thumb ?: '', 'alt' => get_the_title($post->ID)],
        'category'       => $term_name,
        'format'         => get_field('dv_format',         $post->ID) ?: 'Onsite',
        'title'          => $post->post_title,
        'desc'           => get_field('dv_short_desc',     $post->ID),
        'available_days' => mona_schedule_label($post->ID) ?: 'Thứ 2 – Chủ nhật',
        'is_past'        => mona_schedule_is_past($post->ID),
        'duration'       => get_field('dv_duration',       $post->ID) ?: '60 - 90 phút mỗi phiên',
        'branch'         => get_field('dv_branch',         $post->ID),
        'location'       => get_field('dv_location',       $post->ID),
        'instructor'     => get_field('dv_instructor_name', $post->ID),
        'status'         => get_field('dv_status',         $post->ID) ?: 'open',
        'price'          => get_field('dv_price',          $post->ID) ?: 'Liên hệ',
        'spots'          => get_field('dv_spots',          $post->ID),
        'best_seller'    => get_field('dv_best_seller',    $post->ID),
        'url'            => get_permalink($post->ID),
    ];
}

$data = [
    'heading' => get_field('service_heading', $page_id) ?: 'Trải Nghiệm Dịch Vụ',
    'link'    => mona_section_link('page-template/page-dich-vu.php'),
    'items'   => $acf_items,
];
?>

<section class="sec-service bg-white section-pd-t">
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

            <?php if (!empty($data['link']['url'])) : ?>
                <a href="<?php echo esc_url($data['link']['url']); ?>"
                    target="<?php echo esc_attr($data['link']['target'] ?? ''); ?>"
                    class="flex items-center gap-1 text-pri text-[16px] font-semibold uppercase tracking-[1.2px] shrink-0">
                    <?php echo esc_html($data['link']['title'] ?: __('XEM TẤT CẢ', 'monamedia')); ?>
                </a>
            <?php endif; ?>
        </div>

        <!-- Cards -->
        <div class="row">
            <?php foreach ($data['items'] as $item) : ?>
                <div class="col col-5i max-lg:!w-1/2 max-sm:!w-full">
                    <?php get_template_part('partials/components/card-dich-vu', null, ['item' => $item]); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA link -->
        <!-- <?php if (!empty($data['link']['url'])) : ?>
            <div class="flex justify-center mt-10">
                <a href="<?php echo esc_url($data['link']['url']); ?>"
                    target="<?php echo esc_attr($data['link']['target'] ?? ''); ?>"
                    class="flex items-center gap-2 text-[#133a35] text-[12px] font-semibold uppercase tracking-[1.2px]">
                    <?php echo esc_html($data['link']['title'] ?: 'XEM TẤT CẢ DỊCH VỤ'); ?>
                    <div class="size-[10px] shrink-0">
                        <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-arrow-right.svg"
                            class="block w-full h-full object-contain" alt="">
                    </div>
                </a>
            </div>
        <?php endif; ?> -->

    </div>
</section>