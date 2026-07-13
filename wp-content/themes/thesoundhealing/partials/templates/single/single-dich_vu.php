<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// ── Tiêu đề các mục (ACF, trống → mặc định) ──
$dv_st_about      = get_field('dv_sectitle_about',      $post_id) ?: __('Về dịch vụ', 'monamedia');
$dv_st_benefits   = get_field('dv_sectitle_benefits',   $post_id) ?: __('Mục tiêu & Lợi ích', 'monamedia');
$dv_st_roadmap    = get_field('dv_sectitle_roadmap',    $post_id) ?: __('Lộ trình học', 'monamedia');
$dv_st_receive    = get_field('dv_sectitle_receive',    $post_id) ?: __('Lợi ích dịch vụ', 'monamedia');
$dv_st_instructor = get_field('dv_sectitle_instructor', $post_id) ?: __('Người hướng dẫn', 'monamedia');

// ── Thông tin ──
$dv_duration    = get_field('dv_duration',    $post_id);
$dv_location    = get_field('dv_location',    $post_id);
$dv_sched       = mona_expand_schedule($post_id);
$dv_avail_days  = $dv_sched['summary'];
$dv_branch      = get_field('dv_branch',      $post_id);
$dv_short_desc  = get_field('dv_short_desc',  $post_id);
$dv_price       = get_field('dv_price',       $post_id);
$dv_spots_raw   = get_field('dv_spots',       $post_id);
$dv_spots       = ($dv_spots_raw !== '' && $dv_spots_raw !== null) ? (int) $dv_spots_raw : null;
$dv_guests      = get_field('dv_guests', $post_id);

// ── Gallery ── (chỉ dùng ảnh thật)
$thumb      = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt  = get_the_title($post_id);
$banner_img = get_field('dv_banner_image', $post_id);
$gal_3      = get_field('dv_exp_image_1',  $post_id);
$gal_4      = get_field('dv_exp_image_2',  $post_id);
$gal_5      = get_field('dv_gallery_5',    $post_id);
$gal_6      = get_field('dv_gallery_6',    $post_id);
$gal_7      = get_field('dv_gallery_7',    $post_id);
$gal_8      = get_field('dv_gallery_8',    $post_id);
$gal_9      = get_field('dv_gallery_9',    $post_id);

// Ảnh phụ có thật (bỏ ô trống)
$gallery_subs = array_values(array_filter(
    [$banner_img, $gal_3, $gal_4, $gal_5, $gal_6, $gal_7, $gal_8, $gal_9],
    fn($img) => !empty($img['url'])
));
// Ảnh chính: featured image, nếu chưa có thì lấy ảnh phụ đầu tiên
$gallery_main = null;
if ($thumb) {
    $gallery_main = ['url' => $thumb, 'alt' => $thumb_alt];
} elseif (!empty($gallery_subs)) {
    $first        = array_shift($gallery_subs);
    $gallery_main = ['url' => $first['url'], 'alt' => $first['alt'] ?? ''];
}
$has_gallery = (bool) $gallery_main;
$thumbs      = array_slice($gallery_subs, 0, 3); // tối đa 3 thumbnail hiển thị
$extra       = array_slice($gallery_subs, 3);    // ảnh còn lại cho nút "Xem tất cả"

// ── Mô tả / Trải nghiệm ──
$dv_exp_desc  = get_field('dv_exp_desc',  $post_id);

// ── Lộ trình ──
$dv_rm_items   = get_field('dv_roadmap_items',   $post_id) ?: [];

// ── Lợi ích ──
$dv_bn_items   = get_field('dv_benefits_items',   $post_id) ?: [];

// ── Lợi ích nhận được ──
$dv_receive_items = get_field('dv_receive_items', $post_id) ?: [];

// ── Người hướng dẫn ──
$dv_ins_image     = get_field('dv_instructor_image',     $post_id);
$dv_ins_name      = get_field('dv_instructor_name',      $post_id);
$dv_ins_bio       = get_field('dv_instructor_bio',       $post_id);
$dv_ins_instagram = get_field('dv_instructor_instagram', $post_id);
$dv_ins_facebook  = get_field('dv_instructor_facebook',  $post_id);
$dv_ins_youtube   = get_field('dv_instructor_youtube',   $post_id);
$dv_ins_tiktok    = get_field('dv_instructor_tiktok',    $post_id);

// ── Terms ──
$terms     = get_the_terms($post_id, 'loai_dich_vu');
$term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '';

// Buy Now
$_dv_wc_id  = (int) get_post_meta($post_id, '_wc_product_id', true);
$_dv_has_wc = $_dv_wc_id && TSH_WC_Product_Sync::parse_price($dv_price) > 0;
$_dv_buy_url = $_dv_has_wc
    ? add_query_arg(['product_id' => $_dv_wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : '';

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => __('Trang chủ', 'monamedia'), 'url' => home_url('/'),        'is-active' => false],
        ['title' => __('Dịch Vụ', 'monamedia'),  'url' => home_url('/dich-vu'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '',               'is-active' => true],
    ],
]); ?>

<div class="page-dich-vu section-pd-t max-md:!pt-4">

    <div class="container">

        <!-- ── CONTENT + ASIDE ───────────────────────────────────────────────── -->
        <section class="sec-dv-content pb-(--pd-sc)">
            <div class="relative">
                <div class="row">

                    <!-- Left: gallery + long-form content -->
                    <div class="col col-7 max-md:!w-full">

                        <!-- ── PAGE HEADER + GALLERY ────────────────────────────── -->
                        <div class="flex flex-col max-md:flex-col-reverse gap-8 mb-8">

                            <!-- ── PAGE HEADER ──────────────────────────────────────── -->
                            <div class="flex flex-col gap-8">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-2 flex-wrap mb-3">
                                        <span class="text-[11px] font-medium uppercase tracking-[1px] px-3 py-1 rounded-full border border-[#c0c8c6] text-[#414847]">
                                            <?php esc_html_e('Dịch Vụ', 'monamedia'); ?>
                                        </span>
                                        <button type="button"
                                            class="ml-auto w-9 h-9 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#c2a056] hover:text-[#c2a056] transition-colors shrink-0 cursor-pointer"
                                            data-modal-open="share"
                                            data-share-url="<?php echo esc_url(get_permalink()); ?>"
                                            data-share-title="<?php echo esc_attr(get_the_title()); ?>"
                                            aria-label="<?php echo esc_attr__('Chia sẻ', 'monamedia'); ?>">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="18" cy="5" r="3" />
                                                <circle cx="6" cy="12" r="3" />
                                                <circle cx="18" cy="19" r="3" />
                                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                                            </svg>
                                        </button>
                                    </div>

                                    <h1 class="font-title text-pri text-[48px] max-md:text-[24px] leading-[56px] max-md:leading-[40px] tracking-[-0.96px] font-bold">
                                        <?php the_title(); ?>
                                    </h1>
                                </div>
                                <!-- Quick meta: available days + location -->
                                <!-- <?php if ($dv_avail_days || $dv_location) : ?>
                                    <div class="flex flex-col gap-3">
                                        <?php if ($dv_avail_days) : ?>
                                            <div class="flex items-start gap-3">
                                                <div class="size-10 flex items-center justify-center rounded-[10px] bg-[#f7f5f0] shrink-0">
                                                    <svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col justify-center min-h-10">
                                                    <span class="text-[#1b1c19] font-medium text-[15px] leading-[22px]"><?php echo esc_html($dv_avail_days); ?></span>
                                                    <?php if ($dv_duration) : ?>
                                                        <span class="text-[#414847] text-[14px] leading-[20px]"><?php echo esc_html($dv_duration); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($dv_location) : ?>
                                            <div class="flex items-start gap-3">
                                                <div class="size-10 flex items-center justify-center rounded-[10px] bg-[#f7f5f0] shrink-0">
                                                    <svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col justify-center min-h-10">
                                                    <span class="text-[#1b1c19] font-medium text-[15px] leading-[22px]"><?php echo esc_html($dv_location); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?> -->
                            </div>

                            <!-- ── GALLERY ──────────────────────────────────────────── -->
                            <?php if ($has_gallery) : ?>
                                <div class="aspect-[4/3] max-md:aspect-square overflow-hidden rounded-[12px] flex gap-[2px]">

                                    <!-- Left: main image -->
                                    <a href="<?php echo esc_url($gallery_main['url']); ?>"
                                        data-fancybox="gallery-dv"
                                        data-caption="<?php echo esc_attr($gallery_main['alt']); ?>"
                                        class="<?php echo $thumbs ? 'flex-[3]' : 'flex-1'; ?> max-md:!flex-1 min-w-0 overflow-hidden block">
                                        <img src="<?php echo esc_url($gallery_main['url']); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in"
                                            alt="<?php echo esc_attr($gallery_main['alt']); ?>">
                                    </a>

                                    <?php if ($thumbs) : ?>
                                        <!-- Right col: thumbnails có thật -->
                                        <div class="flex-1 flex flex-col gap-[2px] max-md:hidden">
                                            <?php foreach ($thumbs as $idx => $t) :
                                                $is_last  = ($idx === count($thumbs) - 1);
                                                $show_all = $is_last && !empty($extra);
                                            ?>
                                                <?php if ($show_all) : ?>
                                                    <div class="relative flex-1 overflow-hidden">
                                                        <img src="<?php echo esc_url($t['url']); ?>"
                                                            class="block w-full h-full object-cover"
                                                            alt="<?php echo esc_attr($t['alt'] ?? ''); ?>">
                                                        <a href="<?php echo esc_url($t['url']); ?>" data-fancybox="gallery-dv" class="hidden" aria-hidden="true"></a>
                                                        <?php foreach ($extra as $ex) : ?>
                                                            <a href="<?php echo esc_url($ex['url']); ?>" data-fancybox="gallery-dv" class="hidden" aria-hidden="true"></a>
                                                        <?php endforeach; ?>
                                                        <button data-fancybox-trigger="gallery-dv"
                                                            class="absolute inset-0 flex flex-col items-center justify-center gap-1 bg-black/50 text-white hover:bg-black/60 transition-colors">
                                                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                            </svg>
                                                            <span class="text-[11px] font-semibold leading-tight text-center"><?php esc_html_e('Xem tất cả', 'monamedia'); ?></span>
                                                        </button>
                                                    </div>
                                                <?php else : ?>
                                                    <a href="<?php echo esc_url($t['url']); ?>"
                                                        data-fancybox="gallery-dv"
                                                        class="flex-1 overflow-hidden block">
                                                        <img src="<?php echo esc_url($t['url']); ?>"
                                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                                            alt="<?php echo esc_attr($t['alt'] ?? ''); ?>">
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            <?php endif; ?>

                        </div><!-- end PAGE HEADER + GALLERY wrapper -->

                        <!-- ── MOBILE BOOKING SLOT ───────────────────────────────── -->
                        <div id="booking-mobile-slot" class="md:hidden mb-8"></div>

                        <div class="flex flex-col divide-y divide-[#e4e2dd]">

                            <!-- 1. About the service -->
                            <?php if ($dv_short_desc || $dv_exp_desc) : ?>
                                <div class="pb-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-5">
                                        <?php echo esc_html($dv_st_about); ?>
                                    </h2>
                                    <?php if ($dv_short_desc) : ?>
                                        <p class="text-[#414847] text-[16px] leading-[26px] mb-3">
                                            <?php echo wp_kses_post(nl2br(esc_html($dv_short_desc))); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if ($dv_exp_desc) : ?>
                                        <div class="text-[#414847] text-[16px] leading-[26px]">
                                            <?php echo wp_kses_post(nl2br(esc_html($dv_exp_desc))); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- 2. Benefits & Intentions -->
                            <?php if (!empty($dv_bn_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-6">
                                        <?php echo esc_html($dv_st_benefits); ?>
                                    </h2>
                                    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-[1px] bg-[#e4e2dd] border border-[#e4e2dd] rounded-[8px] overflow-hidden">
                                        <?php foreach ($dv_bn_items as $item) : ?>
                                            <div class="bg-[#fbf9f4] p-5">
                                                <h4 class="text-pri text-[15px] font-semibold leading-[24px] mb-2">
                                                    <?php echo esc_html($item['dv_benefit_title']); ?>
                                                </h4>
                                                <?php if (!empty($item['dv_benefit_desc'])) : ?>
                                                    <p class="text-[#414847] text-[14px] leading-[22px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['dv_benefit_desc']))); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 3. Healing journey -->
                            <?php if (!empty($dv_rm_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title font-bold text-pri text-[24px] leading-[32px] mb-8">
                                        <?php echo esc_html($dv_st_roadmap); ?>
                                    </h2>
                                    <div class="flex flex-col divide-y divide-[#e4e2dd]">
                                        <?php foreach ($dv_rm_items as $i => $item) : ?>
                                            <div class="py-6 first:pt-0 flex gap-5 items-start">
                                                <span class="font-title text-pri text-[18px] font-semibold shrink-0 min-w-[24px] leading-[28px] mt-[2px]">
                                                    <?php echo $i + 1; ?>.
                                                </span>
                                                <div class="flex flex-col gap-2">
                                                    <h3 class="font-title text-pri text-[18px] leading-[26px] font-semibold">
                                                        <?php echo esc_html($item['dv_week_title']); ?>
                                                    </h3>
                                                    <?php if (!empty($item['dv_week_desc'])) : ?>
                                                        <p class="text-[#414847] text-[15px] leading-[23px]">
                                                            <?php echo wp_kses_post(nl2br(esc_html($item['dv_week_desc']))); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['dv_week_tags'])) :
                                                        $tags = array_map('trim', explode(',', $item['dv_week_tags']));
                                                    ?>
                                                        <div class="flex gap-2 flex-wrap pt-1">
                                                            <?php foreach ($tags as $tag) : ?>
                                                                <span class="bg-[#f0eee9] text-[#414847] text-[12px] tracking-[1px] px-3 py-1 rounded-[2px]">
                                                                    <?php echo esc_html($tag); ?>
                                                                </span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 4. What You Will Receive -->
                            <?php if (!empty($dv_receive_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-6">
                                        <?php echo esc_html($dv_st_receive); ?>
                                    </h2>
                                    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-[1px] bg-[#e4e2dd] border border-[#e4e2dd] rounded-[8px] overflow-hidden">
                                        <?php foreach ($dv_receive_items as $item) : ?>
                                            <div class="bg-white p-5">
                                                <h4 class="text-pri text-[15px] font-semibold leading-[24px] mb-2">
                                                    <?php echo esc_html($item['dv_receive_title']); ?>
                                                </h4>
                                                <?php if (!empty($item['dv_receive_desc'])) : ?>
                                                    <p class="text-[#414847] text-[14px] leading-[22px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['dv_receive_desc']))); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 5. About the Instructor -->
                            <?php if ($dv_ins_name) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-6">
                                        <?php echo esc_html($dv_st_instructor); ?>
                                    </h2>
                                    <div class="flex gap-5 items-start">
                                        <?php if (!empty($dv_ins_image['url'])) : ?>
                                            <div class="size-16 rounded-full overflow-hidden shrink-0">
                                                <img src="<?php echo esc_url($dv_ins_image['url']); ?>"
                                                    class="block w-full h-full object-cover"
                                                    alt="<?php echo esc_attr($dv_ins_image['alt'] ?? $dv_ins_name); ?>">
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h3 class="font-title text-pri text-[20px] leading-[28px] font-semibold">
                                                <?php echo esc_html($dv_ins_name); ?>
                                            </h3>
                                            <?php if ($dv_ins_bio) : ?>
                                                <p class="text-[#414847] text-[15px] leading-[23px] mt-2">
                                                    <?php echo wp_kses_post(nl2br(esc_html($dv_ins_bio))); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($dv_ins_instagram || $dv_ins_facebook || $dv_ins_youtube || $dv_ins_tiktok) : ?>
                                                <div class="flex gap-2 mt-4">
                                                    <?php if ($dv_ins_instagram) : ?>
                                                        <a href="<?php echo esc_url($dv_ins_instagram); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($dv_ins_facebook) : ?>
                                                        <a href="<?php echo esc_url($dv_ins_facebook); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($dv_ins_youtube) : ?>
                                                        <a href="<?php echo esc_url($dv_ins_youtube); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($dv_ins_tiktok) : ?>
                                                        <a href="<?php echo esc_url($dv_ins_tiktok); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 6. Testimonials -->
                            <?php
                            $fb_heading = get_field('dv_feedbacks_heading', $post_id) ?: 'Testimonials';
                            $fb_items   = get_field('dv_feedbacks', $post_id) ?: [];
                            if (!empty($fb_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px]  font-bold mb-6">
                                        <?php echo esc_html($fb_heading); ?>
                                    </h2>
                                    <div class="grid grid-cols-3 max-md:grid-cols-2 gap-3">
                                        <?php foreach ($fb_items as $item) :
                                            $img_url = $item['dv_fb_image']['url'] ?? '';
                                            $img_alt = $item['dv_fb_image']['alt'] ?? '';
                                            if (!$img_url) continue;
                                        ?>
                                            <a href="<?php echo esc_url($img_url); ?>"
                                                data-fancybox="gallery-fb-dv"
                                                class="block aspect-square overflow-hidden rounded-[4px]">
                                                <img src="<?php echo esc_url($img_url); ?>"
                                                    class="block w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-zoom-in"
                                                    alt="<?php echo esc_attr($img_alt); ?>">
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- Right: Booking widget -->
                    <div class="col col-5 max-md:hidden">
                        <div id="form-dat-lich"
                            class="sticky top-[100px] bg-white border border-[#e4e2dd] rounded-[12px] shadow-[0_6px_20px_rgba(19,58,53,0.08)]  flex flex-col">

                            <!-- Meta box -->
                            <?php
                            $meta_rows = [];
                            if ($dv_avail_days) $meta_rows[] = ['label' => __('LỊCH', 'monamedia'),            'value' => $dv_avail_days, 'type' => 'text',     'icon' => 'calendar'];
                            if ($dv_duration)   $meta_rows[] = ['label' => __('THỜI LƯỢNG', 'monamedia'),       'value' => $dv_duration,   'type' => 'text',     'icon' => 'clock'];
                            if ($dv_guests)     $meta_rows[] = ['label' => __('SỐ KHÁCH / PHIÊN', 'monamedia'), 'value' => $dv_guests,     'type' => 'text',     'icon' => 'users'];
                            if ($dv_location)   $meta_rows[] = ['label' => __('ĐỊA ĐIỂM', 'monamedia'),         'value' => $dv_location,   'type' => 'location', 'icon' => 'location'];
                            $has_spots = $dv_spots !== null;
                            if ($dv_price || !empty($meta_rows) || $has_spots) : ?>
                                <div class="p-6 max-md:p-4 border-b border-[#e4e2dd]">
                                    <?php if ($dv_price) : ?>
                                        <div class="flex items-baseline gap-1 mb-4">
                                            <span class="font-title text-pri text-[28px] max-md:text-[20px] font-bold"><?php echo esc_html($dv_price); ?></span>
                                            <?php if (strtolower(trim($dv_price)) !== 'liên hệ') : ?><span class="text-[#717171] text-[14px]"><?php esc_html_e('/ khách', 'monamedia'); ?></span><?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($meta_rows) || $has_spots) : ?>
                                        <?php
                                        $icon_svgs = [
                                            'calendar' => '<svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>',
                                            'clock'    => '<svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                                            'location' => '<svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>',
                                            'users'    => '<svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>',
                                        ];
                                        ?>
                                        <div class="grid grid-cols-2 max-md:grid-cols-1 gap-1">
                                            <?php foreach ($meta_rows as $row) : ?>
                                                <div class="flex items-start gap-3">
                                                    <div class="size-10 flex items-center justify-center rounded-[10px] bg-[#f7f5f0] shrink-0">
                                                        <?php echo $icon_svgs[$row['icon'] ?? 'calendar']; ?>
                                                    </div>
                                                    <div class="flex flex-col justify-center min-h-10">
                                                        <p class="text-[11px] font-medium text-[#717171] mb-0.5"><?php echo esc_html($row['label']); ?></p>
                                                        <?php if ($row['type'] === 'location') :
                                                            $lines = array_filter(array_map('trim', explode("\n", $row['value'])));
                                                            if (count($lines) > 1) : ?>
                                                                <ul class="text-[#1b1c19] font-medium text-[13px] leading-[20px] flex flex-col gap-0.5">
                                                                    <?php foreach ($lines as $line) : ?>
                                                                        <li><?php echo esc_html($line); ?></li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php else : ?>
                                                                <p class="text-[#1b1c19] font-medium text-[13px] leading-[20px]"><?php echo esc_html($row['value']); ?></p>
                                                            <?php endif; ?>
                                                        <?php else : ?>
                                                            <p class="text-[#1b1c19] font-medium text-[13px] leading-[20px]"><?php echo esc_html($row['value']); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php if ($has_spots) : ?>
                                                <div class="flex items-start gap-3">
                                                    <div class="size-10 flex items-center justify-center rounded-[10px] bg-[#f7f5f0] shrink-0">
                                                        <svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a3 3 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex flex-col justify-center min-h-10">
                                                        <p class="text-[11px] font-medium text-[#717171] mb-1"><?php esc_html_e('SỐ SUẤT CÒN LẠI', 'monamedia'); ?></p>
                                                        <?php if ($dv_spots === 0) : ?>
                                                            <span class="inline-flex items-center gap-1.5 bg-[#fef9c3] text-[#854d0e] text-[12px] font-semibold px-2 py-1 rounded-[4px]"><?php esc_html_e('Fully Booked / Hết suất', 'monamedia'); ?></span>
                                                        <?php else : ?>
                                                            <span class="inline-flex items-center gap-1.5 bg-[#fef9c3] text-[#854d0e] text-[12px] font-semibold px-2 py-1 rounded-[4px]"><?php echo sprintf(__('Còn %d suất', 'monamedia'), $dv_spots); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- CF7 Form / Buy Now -->
                            <div id="dv-form-inner" class="flex p-6 max-md:p-4 flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    <?php esc_html_e('Đăng ký', 'monamedia'); ?>
                                </h3>
                                <?php
                                $dv_cf7_id = defined('DV_CF7_FORM_ID') ? DV_CF7_FORM_ID : (defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '');
                                if ($dv_cf7_id) : ?>
                                    <div class="cf7-dich-vu" <?php if ($_dv_has_wc) : ?> data-buy-url="<?php echo esc_url($_dv_buy_url); ?>" <?php endif; ?>>
                                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($dv_cf7_id) . '"]'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>
</div>

<script>
    (function() {
        if (window.innerWidth >= 768) return;
        var widget = document.getElementById('form-dat-lich');
        var slot = document.getElementById('booking-mobile-slot');
        if (!widget || !slot) return;
        widget.classList.remove('sticky');
        slot.appendChild(widget);
    })();
</script>
<script>
    window.dvSchedule = <?php echo json_encode([
                            'availDates' => $dv_sched['future'],
                            'isPast'     => $dv_sched['is_past'],
                        ]); ?>;
</script>
<?php get_footer(); ?>