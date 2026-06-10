<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// ── Thông tin ──
$ws_date       = get_field('ws_date',       $post_id);
$ws_time       = get_field('ws_time',       $post_id);
$ws_location   = get_field('ws_location',   $post_id);
$ws_short_desc = get_field('ws_short_desc', $post_id);
$ws_price      = get_field('ws_price',      $post_id);
$ws_capacity   = get_field('ws_capacity',   $post_id);
$ws_status     = get_field('ws_status',     $post_id);

// ── Gallery ──
$thumb    = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt = get_the_title($post_id);
$ws_gal_2 = get_field('ws_exp_image_1', $post_id);
$ws_gal_3 = get_field('ws_exp_image_2', $post_id);
$ws_gal_4 = get_field('ws_gallery_4',   $post_id);
$ws_gal_5 = get_field('ws_gallery_5',   $post_id);

// ── Mô tả ──
$ws_exp_title = get_field('ws_exp_title', $post_id) ?: 'Không gian chữa lành qua âm thanh';
$ws_exp_desc  = get_field('ws_exp_desc',  $post_id);

// ── Người hướng dẫn ──
$ws_ins_label     = get_field('ws_instructor_label',     $post_id) ?: 'NGƯỜI HƯỚNG DẪN';
$ws_ins_image     = get_field('ws_instructor_image',     $post_id);
$ws_ins_name      = get_field('ws_instructor_name',      $post_id) ?: 'Linh Tâm';
$ws_ins_bio       = get_field('ws_instructor_bio',       $post_id) ?: 'Hơn 10 năm nghiên cứu và thực hành Sound Healing, Yoga & Thiền định. Được đào tạo tại Rishikesh (Ấn Độ) và các trung tâm trị liệu âm thanh tại Châu Á. Linh Tâm tin rằng mỗi người đều có khả năng chữa lành bản thân khi được trao đúng công cụ và không gian an toàn.';
$ws_ins_instagram = get_field('ws_instructor_instagram', $post_id);
$ws_ins_facebook  = get_field('ws_instructor_facebook',  $post_id);
$ws_ins_whatsapp  = get_field('ws_instructor_whatsapp',  $post_id);
$ws_ins_messenger = get_field('ws_instructor_messenger', $post_id);

// ── Lợi ích ──
$ws_bn_heading = get_field('ws_benefits_heading', $post_id) ?: 'Bạn sẽ nhận được gì?';
$ws_bn_items   = get_field('ws_benefits_items',   $post_id) ?: [
    ['ws_benefit_title' => 'Thư giãn sâu & giải phóng căng thẳng',    'ws_benefit_desc' => 'Âm thanh từ singing bowl và nhạc cụ trị liệu giúp hệ thần kinh đi vào trạng thái thư giãn sâu chỉ trong vài phút.'],
    ['ws_benefit_title' => 'Trải nghiệm Sound Bath toàn thân',          'ws_benefit_desc' => 'Cảm nhận sóng âm lan toả qua từng tế bào cơ thể, tạo cảm giác nhẹ nhàng và kết nối sâu với bản thân.'],
    ['ws_benefit_title' => 'Không gian thiền định được hướng dẫn',      'ws_benefit_desc' => 'Người hướng dẫn sẽ đồng hành cùng bạn trong suốt buổi, phù hợp cho cả người mới bắt đầu.'],
];

// ── Fallbacks ──
$fb_main = MONA_THEME_PATH_URI . '/assets/images/kh-hero.jpg';
$fb_sub  = MONA_THEME_PATH_URI . '/assets/images/kh-exp-1.jpg';
$fb_ins  = MONA_THEME_PATH_URI . '/assets/images/kh-instructor.jpg';
$ic_check = MONA_THEME_PATH_URI . '/assets/images/ic-check-pri.svg';

// ── Status badge ──
$status_map = [
    'open'     => ['label' => 'Còn chỗ',       'color' => '#2e7d32', 'bg' => '#e8f5e9'],
    'limited'  => ['label' => 'Sắp hết chỗ',   'color' => '#e65100', 'bg' => '#fff3e0'],
    'closed'   => ['label' => 'Hết chỗ',        'color' => '#c62828', 'bg' => '#ffebee'],
    'upcoming' => ['label' => 'Sắp diễn ra',    'color' => '#1565c0', 'bg' => '#e3f2fd'],
];
$status_info = $status_map[$ws_status] ?? null;

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => 'Trang chủ', 'url' => home_url('/'),        'is-active' => false],
        ['title' => 'Workshop',  'url' => home_url('/workshop'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '',               'is-active' => true],
    ],
]); ?>

<div class="page-workshop section-pd">

    <!-- ── PAGE HEADER ──────────────────────────────────────────────────── -->
    <div class="container">
        <div class="flex flex-col gap-8 mb-8">
            <div class="flex flex-col gap-2">
                <?php
                $terms = get_the_terms($post_id, 'loai_workshop');
                if (!is_wp_error($terms) && !empty($terms)) : ?>
                    <div class="flex gap-2 flex-wrap mb-3">
                        <?php foreach ($terms as $term) : ?>
                            <span class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">
                                <?php echo esc_html($term->name); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h1 class="font-title text-pri text-[48px] max-md:text-[32px] leading-[56px] max-md:leading-[40px] tracking-[-0.96px] font-normal ">
                    <?php the_title(); ?>
                </h1>
            </div>
            <?php if ($ws_date || $ws_time || $ws_location) : ?>
                <div class="flex flex-wrap gap-x-6 gap-y-2 mt-4 text-[#414847] text-[15px]">
                    <?php if ($ws_date) : ?>
                        <div class="flex items-center gap-1.5">
                            <svg class="size-4 text-[#4e635a] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <span><?php echo esc_html($ws_date); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($ws_time) : ?>
                        <div class="flex items-center gap-1.5">
                            <svg class="size-4 text-[#4e635a] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?php echo esc_html($ws_time); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($ws_location) : ?>
                        <div class="flex items-center gap-1.5">
                            <svg class="size-4 text-[#4e635a] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span><?php echo esc_html($ws_location); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>


        <!-- ── GALLERY ───────────────────────────────────────────────────────── -->
        <div class="relative pb-12">
            <div class="relative flex gap-2 max-md:block overflow-hidden rounded-[12px] md:h-[480px]">

                <!-- Main image -->
                <div class="flex-[1.3] overflow-hidden max-md:h-[260px] max-md:w-full">
                    <a href="<?php echo esc_url($thumb ?: $fb_main); ?>"
                        data-fancybox="gallery-ws"
                        data-caption="<?php echo esc_attr($thumb_alt); ?>"
                        class="block w-full h-full">
                        <img src="<?php echo esc_url($thumb ?: $fb_main); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($thumb_alt); ?>">
                    </a>
                </div>

                <!-- 2×2 grid (desktop only) -->
                <div class="flex-[0.7] grid grid-cols-2 grid-rows-2 gap-2 max-md:hidden">
                    <a href="<?php echo esc_url($ws_gal_2['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-ws"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($ws_gal_2['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($ws_gal_2['alt'] ?? ''); ?>">
                    </a>
                    <a href="<?php echo esc_url($ws_gal_3['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-ws"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($ws_gal_3['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($ws_gal_3['alt'] ?? ''); ?>">
                    </a>
                    <a href="<?php echo esc_url($ws_gal_4['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-ws"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($ws_gal_4['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($ws_gal_4['alt'] ?? ''); ?>">
                    </a>
                    <a href="<?php echo esc_url($ws_gal_5['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-ws"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($ws_gal_5['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($ws_gal_5['alt'] ?? ''); ?>">
                    </a>
                </div>

                <!-- Xem tất cả ảnh -->
                <button data-gallery-trigger="gallery-ws"
                    class="absolute bottom-4 right-4 flex items-center gap-2 bg-white text-[#1b1c19] text-[13px] font-semibold border border-[#1b1c19] rounded-[6px] px-4 py-2 hover:bg-[#f5f3ee] transition-colors shadow-sm">
                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Xem tất cả ảnh
                </button>

            </div>
        </div>
        <section class="sec-ws-content pb-(--pd-sc)">
            <div class="relative">
                <div class="row">

                    <!-- Left: long-form content -->
                    <div class="col col-7 max-md:!w-full">
                        <div class="flex flex-col divide-y divide-[#e4e2dd]">

                            <!-- About / description -->
                            <div class="pb-10">
                                <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-4">
                                    <?php echo esc_html($ws_exp_title); ?>
                                </h2>
                                <?php if ($ws_short_desc) : ?>
                                    <p class="text-[#414847] text-[16px] leading-[26px] mb-3">
                                        <?php echo wp_kses_post(nl2br(esc_html($ws_short_desc))); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($ws_exp_desc) : ?>
                                    <div class="text-[#414847] text-[16px] leading-[26px]">
                                        <?php echo wp_kses_post(nl2br(esc_html($ws_exp_desc))); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Benefits -->
                            <?php if (!empty($ws_bn_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-6">
                                        <?php echo esc_html($ws_bn_heading); ?>
                                    </h2>
                                    <div class="flex flex-col gap-5">
                                        <?php foreach ($ws_bn_items as $item) : ?>
                                            <div class="flex gap-4 items-start">
                                                <div class="size-5 shrink-0 mt-[3px]">
                                                    <img src="<?php echo esc_url($ic_check); ?>"
                                                        class="block w-full h-full object-contain" alt="">
                                                </div>
                                                <div>
                                                    <h4 class="text-pri text-[16px] font-semibold leading-[24px]">
                                                        <?php echo esc_html($item['ws_benefit_title']); ?>
                                                    </h4>
                                                    <?php if (!empty($item['ws_benefit_desc'])) : ?>
                                                        <p class="text-[#414847] text-[15px] leading-[23px] mt-1">
                                                            <?php echo wp_kses_post(nl2br(esc_html($item['ws_benefit_desc']))); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Instructor -->
                            <?php if ($ws_ins_name) : ?>
                                <div class="py-10">
                                    <p class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px] mb-5">
                                        <?php echo esc_html($ws_ins_label); ?>
                                    </p>
                                    <div class="flex gap-5 items-start">
                                        <div class="size-16 rounded-full overflow-hidden shrink-0">
                                            <img src="<?php echo esc_url($ws_ins_image['url'] ?? $fb_ins); ?>"
                                                class="block w-full h-full object-cover"
                                                alt="<?php echo esc_attr($ws_ins_image['alt'] ?? $ws_ins_name); ?>">
                                        </div>
                                        <div>
                                            <h3 class="font-title text-pri text-[20px] leading-[28px] font-normal">
                                                <?php echo esc_html($ws_ins_name); ?>
                                            </h3>
                                            <?php if ($ws_ins_bio) : ?>
                                                <p class="text-[#414847] text-[15px] leading-[23px] mt-2">
                                                    <?php echo wp_kses_post(nl2br(esc_html($ws_ins_bio))); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($ws_ins_instagram || $ws_ins_facebook || $ws_ins_whatsapp || $ws_ins_messenger) : ?>
                                                <div class="flex gap-2 mt-4">
                                                    <?php if ($ws_ins_instagram) : ?>
                                                        <a href="<?php echo esc_url($ws_ins_instagram); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($ws_ins_facebook) : ?>
                                                        <a href="<?php echo esc_url($ws_ins_facebook); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($ws_ins_whatsapp) : ?>
                                                        <a href="<?php echo esc_url($ws_ins_whatsapp); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($ws_ins_messenger) : ?>
                                                        <a href="<?php echo esc_url($ws_ins_messenger); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.652V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.975 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8.1l3.131 3.26L19.752 8.1l-6.561 6.863z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Feedback photos -->
                            <?php
                            $fb_heading = get_field('ws_feedbacks_heading', $post_id) ?: 'Cảm nhận của học viên';
                            $fb_items   = get_field('ws_feedbacks', $post_id) ?: [
                                ['ws_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-1.jpg', 'alt' => '']],
                                ['ws_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-2.jpg', 'alt' => '']],
                                ['ws_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-3.jpg', 'alt' => '']],
                                ['ws_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-4.jpg', 'alt' => '']],
                                ['ws_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-1.jpg', 'alt' => '']],
                                ['ws_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-2.jpg', 'alt' => '']],
                            ];
                            if (!empty($fb_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-6">
                                        <?php echo esc_html($fb_heading); ?>
                                    </h2>
                                    <div class="grid grid-cols-3 max-md:grid-cols-2 gap-3">
                                        <?php foreach ($fb_items as $item) :
                                            $img_url = $item['ws_fb_image']['url'] ?? '';
                                            $img_alt = $item['ws_fb_image']['alt'] ?? '';
                                            if (!$img_url) continue;
                                        ?>
                                            <a href="<?php echo esc_url($img_url); ?>"
                                                data-fancybox="gallery-fb-ws"
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
                    <div class="col col-5 max-md:!w-full">
                        <div id="form-dang-ky"
                            class="sticky top-[100px] bg-white border border-[#e4e2dd] rounded-[12px] shadow-[0_6px_20px_rgba(19,58,53,0.08)] p-6 flex flex-col gap-5">

                            <!-- Price -->
                            <?php if ($ws_price) : ?>
                                <div>
                                    <span class="font-title text-pri text-[26px] leading-[34px] font-normal">
                                        <?php echo esc_html($ws_price); ?>
                                    </span>
                                    <span class="text-[#717171] text-[14px]"> / người</span>
                                </div>
                            <?php endif; ?>

                            <!-- Meta box (Airbnb date-picker style) -->
                            <?php if ($ws_date || $ws_time || $ws_location) : ?>
                                <div class="border border-[#b0b0b0] rounded-[8px] overflow-hidden text-[14px]">
                                    <?php
                                    $meta_rows = [];
                                    if ($ws_date)     $meta_rows[] = ['label' => 'NGÀY',     'value' => $ws_date];
                                    if ($ws_time)     $meta_rows[] = ['label' => 'THỜI GIAN', 'value' => $ws_time];
                                    if ($ws_location) $meta_rows[] = ['label' => 'ĐỊA ĐIỂM', 'value' => $ws_location];
                                    $last = count($meta_rows) - 1;
                                    foreach ($meta_rows as $i => $row) : ?>
                                        <div class="p-3 <?php echo $i < $last ? 'border-b border-[#b0b0b0]' : ''; ?>">
                                            <p class="text-[10px] font-semibold uppercase tracking-[1px] text-[#717171] mb-0.5">
                                                <?php echo $row['label']; ?>
                                            </p>
                                            <p class="text-[#1b1c19] font-medium"><?php echo esc_html($row['value']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Capacity badge -->
                            <?php if ($ws_capacity && $status_info) : ?>
                                <div class="flex items-center gap-2 rounded-[6px] px-3 py-2"
                                    style="background:<?php echo esc_attr($status_info['bg']); ?>; color:<?php echo esc_attr($status_info['color']); ?>;">
                                    <svg class="size-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                    <span class="text-[13px] font-semibold"><?php echo esc_html($ws_capacity); ?></span>
                                </div>
                            <?php endif; ?>


                            <!-- CF7 Form -->
                            <div id="ws-form-inner" class="border-t border-[#e4e2dd] pt-5 flex flex-col gap-3">
                                <h3 class="font-title text-center text-pri text-[18px] leading-[26px] font-normal">
                                    Đăng ký tham dự
                                </h3>
                                <?php
                                $ws_cf7_id = defined('WS_CF7_FORM_ID') ? WS_CF7_FORM_ID : '';
                                if ($ws_cf7_id) : ?>
                                    <div class="cf7-workshop">
                                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($ws_cf7_id) . '"]'); ?>
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

<?php get_footer(); ?>