<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// ── Thông tin ──
$ws_date      = get_field('ws_date',      $post_id);
$ws_time      = get_field('ws_time',      $post_id);
$ws_location  = get_field('ws_location',  $post_id);
$ws_short_desc = get_field('ws_short_desc', $post_id);
$ws_price     = get_field('ws_price',     $post_id);

// ── Trải nghiệm ──
$ws_exp_title  = get_field('ws_exp_title',   $post_id) ?: 'Không gian chữa lành qua âm thanh';
$ws_exp_desc   = get_field('ws_exp_desc',    $post_id);
$ws_exp_img_1  = get_field('ws_exp_image_1', $post_id);
$ws_exp_img_2  = get_field('ws_exp_image_2', $post_id);

// ── Người hướng dẫn ──
$ws_ins_label  = get_field('ws_instructor_label', $post_id) ?: 'NGƯỜI HƯỚNG DẪN';
$ws_ins_image  = get_field('ws_instructor_image', $post_id);
$ws_ins_name   = get_field('ws_instructor_name',  $post_id) ?: 'Linh Tâm';
$ws_ins_bio    = get_field('ws_instructor_bio',   $post_id) ?: 'Hơn 10 năm nghiên cứu và thực hành Sound Healing, Yoga & Thiền định. Được đào tạo tại Rishikesh (Ấn Độ) và các trung tâm trị liệu âm thanh tại Châu Á. Linh Tâm tin rằng mỗi người đều có khả năng chữa lành bản thân khi được trao đúng công cụ và không gian an toàn.';

// ── Lợi ích ──
$ws_bn_heading = get_field('ws_benefits_heading', $post_id) ?: 'Bạn sẽ nhận được gì?';
$ws_bn_items   = get_field('ws_benefits_items',   $post_id) ?: [
    [
        'ws_benefit_title' => 'Thư giãn sâu & giải phóng căng thẳng',
        'ws_benefit_desc'  => 'Âm thanh từ singing bowl và nhạc cụ trị liệu giúp hệ thần kinh đi vào trạng thái thư giãn sâu chỉ trong vài phút.',
    ],
    [
        'ws_benefit_title' => 'Trải nghiệm Sound Bath toàn thân',
        'ws_benefit_desc'  => 'Cảm nhận sóng âm lan toả qua từng tế bào cơ thể, tạo cảm giác nhẹ nhàng và kết nối sâu với bản thân.',
    ],
    [
        'ws_benefit_title' => 'Không gian thiền định được hướng dẫn',
        'ws_benefit_desc'  => 'Người hướng dẫn sẽ đồng hành cùng bạn trong suốt buổi, phù hợp cho cả người mới bắt đầu.',
    ],
];

// ── CTA ──
$ws_cta_title  = get_field('ws_cta_title', $post_id) ?: 'Đăng ký trước khi hết chỗ';
$ws_cta_desc   = get_field('ws_cta_desc',  $post_id) ?: 'Số lượng chỗ giới hạn để đảm bảo chất lượng trải nghiệm tốt nhất cho mỗi người tham dự.';

$thumb     = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt = get_the_title($post_id);

// ── Fallbacks ──
$fallback_hero = MONA_THEME_PATH_URI . '/assets/images/kh-hero.jpg';
$fallback_exp1 = MONA_THEME_PATH_URI . '/assets/images/kh-exp-1.jpg';
$fallback_exp2 = MONA_THEME_PATH_URI . '/assets/images/kh-exp-2.jpg';
$fallback_ins  = MONA_THEME_PATH_URI . '/assets/images/kh-instructor.jpg';
$ic_check      = MONA_THEME_PATH_URI . '/assets/images/ic-check-pri.svg';

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => 'Trang chủ', 'url' => home_url('/'),          'is-active' => false],
        ['title' => 'Workshop',  'url' => home_url('/workshop'),   'is-active' => false],
        ['title' => get_the_title(), 'url' => '',                   'is-active' => true],
    ],
]); ?>

<div class="page-workshop" style="background:#fbf9f4;">

    <!-- ── HERO ──────────────────────────────────────────────────────── -->
    <section class="sec-ws-hero py-(--pd-sc)">
        <div class="container">
            <div class="row items-center">

                <!-- Left: Info -->
                <div class="col col-7 max-md:!w-full">
                    <div class="flex flex-col gap-6">

                        <!-- Loại workshop -->
                        <?php
                        $terms = get_the_terms($post_id, 'loai_workshop');
                        if (!is_wp_error($terms) && !empty($terms)) : ?>
                            <div class="flex gap-3 flex-wrap items-center">
                                <?php foreach ($terms as $term) : ?>
                                    <span class="bg-[#eae8e3] text-pri text-[12px] font-semibold uppercase tracking-[1.2px] px-3 py-1 rounded-[2px]">
                                        <?php echo esc_html($term->name); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Title -->
                        <h1 class="font-title text-pri text-[56px] max-md:text-[32px] leading-[64px] tracking-[-1.12px] font-normal">
                            <?php the_title(); ?>
                        </h1>

                        <!-- Short desc -->
                        <?php if ($ws_short_desc) : ?>
                            <p class="text-[#414847] text-[18px] leading-[28px] max-w-[448px]">
                                <?php echo wp_kses_post(nl2br(esc_html($ws_short_desc))); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Meta: date / time / location -->
                        <?php if ($ws_date || $ws_time || $ws_location) : ?>
                            <div class="flex flex-col gap-3 border-t border-[#e4e2dd] pt-6">
                                <?php if ($ws_date) : ?>
                                    <div class="flex items-center gap-3">
                                        <svg class="size-5 shrink-0 text-pri" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        <span class="text-[#414847] text-[16px]"><?php echo esc_html($ws_date); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($ws_time) : ?>
                                    <div class="flex items-center gap-3">
                                        <svg class="size-5 shrink-0 text-pri" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-[#414847] text-[16px]"><?php echo esc_html($ws_time); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($ws_location) : ?>
                                    <div class="flex items-center gap-3">
                                        <svg class="size-5 shrink-0 text-pri" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        <span class="text-[#414847] text-[16px]"><?php echo esc_html($ws_location); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Price + CTA -->
                        <div class="flex items-center gap-6 pt-2">
                            <?php if ($ws_price) : ?>
                                <span class="font-title text-pri text-[24px] leading-[32px] font-normal">
                                    <?php echo esc_html($ws_price); ?>
                                </span>
                            <?php endif; ?>
                            <a href="#form-dang-ky" class="btn btn-pri shadow-[0px_10px_20px_rgba(44,81,76,0.15)]">
                                ĐĂNG KÝ NGAY
                            </a>
                        </div>

                    </div>
                </div>

                <!-- Right: Image -->
                <div class="col col-5 max-md:!w-full">
                    <div class="overflow-hidden rounded-[4px] shadow-[0px_10px_40px_rgba(44,81,76,0.05)] aspect-[3/4] relative">
                        <img src="<?php echo esc_url($thumb ?: $fallback_hero); ?>"
                            class="block w-full h-full object-cover"
                            alt="<?php echo esc_attr($thumb_alt); ?>">
                        <div class="absolute inset-0 bg-[rgba(64,101,96,0.1)] mix-blend-overlay"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── EXPERIENCE ────────────────────────────────────────────────── -->
    <section class="sec-ws-exp py-(--pd-sc)" style="background:#f5f3ee; position:relative; overflow:hidden;">
        <div class="absolute size-[500px] bg-[rgba(206,229,218,0.2)] rounded-[12px] blur-[50px] top-[-250px] right-[-250px] pointer-events-none"></div>
        <div class="container">
            <div class="row items-center">

                <!-- Images grid -->
                <div class="col col-6 max-md:!w-full">
                    <div class="grid grid-cols-2 gap-4" style="height:300px;">
                        <div class="overflow-hidden rounded-[4px] h-full">
                            <img src="<?php echo esc_url($ws_exp_img_1['url'] ?? $fallback_exp1); ?>"
                                class="block w-full h-full object-cover"
                                alt="<?php echo esc_attr($ws_exp_img_1['alt'] ?? ''); ?>">
                        </div>
                        <div class="overflow-hidden rounded-[4px] h-full">
                            <img src="<?php echo esc_url($ws_exp_img_2['url'] ?? $fallback_exp2); ?>"
                                class="block w-full h-full object-cover"
                                alt="<?php echo esc_attr($ws_exp_img_2['alt'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Text -->
                <div class="col col-6 max-md:!w-full">
                    <div class="flex flex-col gap-6">
                        <h2 class="font-title text-pri text-[32px] max-md:text-[24px] leading-[40px] font-normal">
                            <?php echo esc_html($ws_exp_title); ?>
                        </h2>
                        <?php if ($ws_exp_desc) : ?>
                            <div class="text-[#414847] text-[16px] leading-[24px]">
                                <?php echo wp_kses_post(nl2br(esc_html($ws_exp_desc))); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── CONTENT + ASIDE ───────────────────────────────────────────── -->
    <section class="sec-ws-content py-(--pd-sc)">
        <div class="container">
            <div class="row">

                <!-- Left: Instructor + Benefits -->
                <div class="col col-7 max-md:!w-full">
                    <div class="flex flex-col max-md:gap-[40px] gap-[80px]">

                        <!-- Instructor -->
                        <?php if ($ws_ins_name) : ?>
                            <div class="bg-[#f5f3ee] rounded-[4px] shadow-[0px_10px_20px_rgba(44,81,76,0.05)] max-md:p-4 p-8 flex flex-col gap-4">
                                <p class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">
                                    <?php echo esc_html($ws_ins_label); ?>
                                </p>
                                <div class="size-24 rounded-[12px] overflow-hidden shrink-0">
                                    <img src="<?php echo esc_url($ws_ins_image['url'] ?? $fallback_ins); ?>"
                                        class="block w-full h-full object-cover"
                                        alt="<?php echo esc_attr($ws_ins_image['alt'] ?? $ws_ins_name); ?>">
                                </div>
                                <h3 class="font-title text-pri text-[24px] leading-[32px] font-normal pt-4">
                                    <?php echo esc_html($ws_ins_name); ?>
                                </h3>
                                <?php if ($ws_ins_bio) : ?>
                                    <p class="text-[#414847] text-[16px] leading-[24px]">
                                        <?php echo wp_kses_post(nl2br(esc_html($ws_ins_bio))); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Benefits -->
                        <?php if (!empty($ws_bn_items)) : ?>
                            <div class="flex flex-col gap-8">
                                <h2 class="font-title text-pri text-[32px] max-md:text-[24px] leading-[40px] font-normal">
                                    <?php echo esc_html($ws_bn_heading); ?>
                                </h2>
                                <div class="flex flex-col gap-6">
                                    <?php foreach ($ws_bn_items as $item) : ?>
                                        <div class="flex gap-4 items-start">
                                            <div class="size-5 shrink-0 mt-[4px]">
                                                <img src="<?php echo esc_url($ic_check); ?>"
                                                    class="block w-full h-full object-contain" alt="">
                                            </div>
                                            <div>
                                                <h4 class="text-pri text-[18px] font-semibold leading-[28px]">
                                                    <?php echo esc_html($item['ws_benefit_title']); ?>
                                                </h4>
                                                <?php if (!empty($item['ws_benefit_desc'])) : ?>
                                                    <p class="text-[#414847] text-[16px] leading-[24px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['ws_benefit_desc']))); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Right: Form aside -->
                <div class="col col-5 max-md:!w-full">
                    <div id="form-dang-ky"
                        class="bg-[#f5f3ee] border border-[rgba(192,200,198,0.3)] rounded-[4px] shadow-[0px_10px_20px_rgba(44,81,76,0.05)] max-md:p-4 p-8 flex flex-col max-md:gap-4 gap-8 sticky top-[100px]">

                        <!-- Summary info in aside -->
                        <?php if ($ws_date || $ws_time || $ws_location) : ?>
                            <div class="flex flex-col gap-2 border-b border-[#e4e2dd] pb-6">
                                <?php if ($ws_date) : ?>
                                    <div class="flex items-center gap-2">
                                        <svg class="size-4 shrink-0 text-[#4e635a]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        <span class="text-[#414847] text-[14px]"><?php echo esc_html($ws_date); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($ws_time) : ?>
                                    <div class="flex items-center gap-2">
                                        <svg class="size-4 shrink-0 text-[#4e635a]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-[#414847] text-[14px]"><?php echo esc_html($ws_time); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($ws_location) : ?>
                                    <div class="flex items-center gap-2">
                                        <svg class="size-4 shrink-0 text-[#4e635a]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        <span class="text-[#414847] text-[14px]"><?php echo esc_html($ws_location); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($ws_price) : ?>
                                    <div class="flex items-center gap-2 pt-1">
                                        <span class="font-title text-pri text-[20px] font-normal">
                                            <?php echo esc_html($ws_price); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex flex-col gap-2 pb-6 border-b border-[rgba(192,200,198,0.4)]">
                            <h3 class="font-title text-pri text-[24px] leading-[32px] font-normal">
                                Đăng ký tham dự
                            </h3>
                            <p class="text-[#414847] text-[16px] leading-[24px] font-medium">
                                <?php echo esc_html(get_the_title()); ?>
                            </p>
                        </div>

                        <?php
                        $ws_cf7_id = defined('WS_CF7_FORM_ID') ? WS_CF7_FORM_ID : '';
                        if ($ws_cf7_id) { ?>
                            <div class="cf7-workshop">
                                <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($ws_cf7_id) . '"]'); ?>
                            </div>
                        <?php } ?>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── CTA ───────────────────────────────────────────────────────── -->
    <!-- <section class="sec-ws-cta py-(--pd-sc) relative overflow-hidden" style="background:#133a35;">
        <div class="absolute inset-0 opacity-10"
            style="background-image:radial-gradient(circle at center, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 70%);"></div>
        <div class="container relative">
            <div class="flex flex-col items-center text-center max-w-[768px] mx-auto gap-6">
                <h2 class="font-title text-white text-[56px] max-md:text-[8vw] leading-[64px] max-md:leading-[50px] tracking-[-1.12px] font-normal">
                    <?php echo esc_html($ws_cta_title); ?>
                </h2>
                <p class="text-[rgba(255,255,255,0.8)] text-[18px] leading-[28px]">
                    <?php echo wp_kses_post(nl2br(esc_html($ws_cta_desc))); ?>
                </p>
                <a href="#form-dang-ky" class="bg-[#fbf9f4] text-pri text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-4 rounded-[2px] shadow-[0px_10px_15px_-3px_rgba(0,0,0,0.1)] hover:bg-white transition-colors mt-4">
                    ĐĂNG KÝ WORKSHOP
                </a>
            </div>
        </div>
    </section> -->

</div>

<?php get_footer(); ?>