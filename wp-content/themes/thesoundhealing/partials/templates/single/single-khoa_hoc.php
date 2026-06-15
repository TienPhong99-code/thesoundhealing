<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// ── Thông tin ──
$level       = get_field('level',      $post_id) ?: 'KHOÁ HỌC CHUYÊN SÂU';
$start_date  = get_field('start_date', $post_id) ?: '20 THÁNG 7, 2025';
$kh_time     = get_field('kh_time',    $post_id) ?: '09:00 – 17:00';
$duration    = get_field('duration',   $post_id) ?: '2 ngày · Cuối tuần';
$short_desc     = get_field('short_desc',    $post_id);
$kh_description = get_field('kh_description', $post_id) ?: 'Khóa học chuyên sâu dành cho những ai muốn làm chủ nghệ thuật Sound Healing. Bạn sẽ được học từ nền tảng lý thuyết tần số đến kỹ thuật thực hành với chuông pha lê, sẵn sàng hướng dẫn người khác sau khi hoàn thành.';
$price       = get_field('price',      $post_id) ?: '8.500.000 VNĐ';
$kh_location = get_field('location',   $post_id) ?: 'Aetheria Studio — 104/20 Mai Thị Lựu, Quận 1, TP.HCM';
$kh_capacity  = get_field('kh_capacity', $post_id) ?: '20 học viên';
$kh_spots_raw = get_field('kh_spots',  $post_id);
$kh_spots    = ($kh_spots_raw !== '' && $kh_spots_raw !== null) ? (int) $kh_spots_raw : 8;

// ── Gallery ──
$thumb     = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt = get_the_title($post_id);
$gal_2     = get_field('exp_image_1', $post_id);
$gal_3     = get_field('exp_image_2', $post_id);
$gal_4     = get_field('gallery_4',   $post_id);
$gal_5     = get_field('gallery_5',   $post_id);
$gal_6     = get_field('gallery_6',   $post_id);
$gal_7     = get_field('gallery_7',   $post_id);
$gal_8     = get_field('gallery_8',   $post_id);
$gal_9     = get_field('gallery_9',   $post_id);

// ── Mô tả ──
$exp_title = get_field('exp_title', $post_id) ?: 'Không gian của sự tĩnh lặng';
$exp_desc  = get_field('exp_desc',  $post_id);

// ── Lộ trình ──
$rm_label   = get_field('roadmap_label',   $post_id) ?: 'LỘ TRÌNH HỌC';
$rm_heading = get_field('roadmap_heading', $post_id) ?: 'Hành trình';
$rm_items   = get_field('roadmap_items',   $post_id) ?: [
    [
        'week_title' => 'Tuần 1 – Kết nối với hơi thở',
        'week_desc'  => 'Khám phá sức mạnh của hơi thở có chủ đích. Học các kỹ thuật thở cơ bản để thư giãn hệ thần kinh và đưa cơ thể về trạng thái cân bằng.',
        'week_tags'  => 'Thở có ý thức, Pranayama cơ bản, Thư giãn hệ thần kinh',
    ],
    [
        'week_title' => 'Tuần 2 – Lắng nghe âm thanh nội tâm',
        'week_desc'  => 'Khám phá cách âm thanh ảnh hưởng đến trạng thái cảm xúc và năng lượng. Thực hành với singing bowl và các nhạc cụ trị liệu.',
        'week_tags'  => 'Singing Bowl, Sound Bath, Âm điều trị',
    ],
    [
        'week_title' => 'Tuần 3 – Thiền định & Tích hợp',
        'week_desc'  => 'Kết hợp âm thanh và hơi thở vào thực hành thiền định hàng ngày. Xây dựng thói quen chăm sóc tâm lý bền vững.',
        'week_tags'  => 'Thiền định, Tích hợp thực hành, Chăm sóc bản thân',
    ],
];

// ── Người hướng dẫn ──
$ins_label     = get_field('instructor_label',     $post_id) ?: 'NGƯỜI HƯỚNG DẪN';
$ins_image     = get_field('instructor_image',     $post_id);
$ins_name      = get_field('instructor_name',      $post_id) ?: 'Linh Tâm';
$ins_bio       = get_field('instructor_bio',       $post_id) ?: 'Hơn 10 năm nghiên cứu và thực hành Sound Healing, Yoga & Thiền định. Được đào tạo tại Rishikesh (Ấn Độ) và các trung tâm trị liệu âm thanh tại Châu Á. Linh Tâm tin rằng mỗi người đều có khả năng chữa lành bản thân khi được trao đúng công cụ và không gian an toàn.';
$ins_instagram = get_field('instructor_instagram', $post_id);
$ins_facebook  = get_field('instructor_facebook',  $post_id);
$ins_whatsapp  = get_field('instructor_whatsapp',  $post_id);
$ins_messenger = get_field('instructor_messenger', $post_id);

// ── Lợi ích ──
$bn_heading = get_field('benefits_heading', $post_id) ?: 'Bạn sẽ nhận được gì?';
$bn_items   = get_field('benefits_items',   $post_id) ?: [
    ['benefit_title' => 'Giảm căng thẳng & lo âu hiệu quả',                 'benefit_desc' => 'Các kỹ thuật thở và âm thanh giúp điều hòa hệ thần kinh tự chủ, giảm mức cortisol và tạo cảm giác bình an sâu sắc.'],
    ['benefit_title' => 'Cải thiện chất lượng giấc ngủ',                    'benefit_desc' => 'Thực hành đều đặn giúp cơ thể và tâm trí dễ dàng đi vào trạng thái thư giãn sâu, hỗ trợ giấc ngủ tự nhiên hơn.'],
    ['benefit_title' => 'Tăng khả năng tập trung & kết nối với bản thân',   'benefit_desc' => 'Kỹ năng thiền định và lắng nghe âm thanh giúp bạn hiện diện hơn trong từng khoảnh khắc của cuộc sống.'],
    ['benefit_title' => 'Xây dựng thực hành chăm sóc bản thân bền vững',   'benefit_desc' => 'Bộ công cụ thực tiễn để tự chăm sóc sức khỏe tâm – thân mỗi ngày, không phụ thuộc vào thời gian hay địa điểm.'],
];

// ── Lợi ích nhận được ──
$receive_items = get_field('receive_items', $post_id) ?: [
    ['receive_title' => '70% Practices – 30% Theory | 70% Thực Hành – 30% Lý Thuyết',            'receive_desc' => 'A 70% practice – 30% theory approach gives you step-by-step instructions to select and arrange bowls, master striking/rimming techniques, harmonize 7 bowls, and apply confidently right after the course.'],
    ['receive_title' => 'Relax your body, sleep deeper, stay focused | Thư giãn cơ thể, ngủ sâu hơn', 'receive_desc' => 'The vibrational tones of crystal singing bowls can help release tension, calm the mind, and support deeper sleep and better focus.'],
    ['receive_title' => 'Flexible schedule, for busy learners | Lịch học da dạng, phù hợp',        'receive_desc' => 'Flexible schedules make it easier to fit learning into your lifestyle. Ideal for yoga teachers, therapists, or anyone who is interested in Sound Healing.'],
    ['receive_title' => '15% off your next set or next course | Ưu đãi 15% khi bạn mua',           'receive_desc' => 'Enjoy 15% off when you purchase your bowl set or enroll in your next course — a practical way to reduce investment cost and continue your learning journey.'],
];

// ── Fallbacks ──
$fb_main  = MONA_THEME_PATH_URI . '/assets/images/kh-hero.jpg';
$fb_sub   = MONA_THEME_PATH_URI . '/assets/images/kh-exp-1.jpg';
$fb_ins   = MONA_THEME_PATH_URI . '/assets/images/kh-instructor.jpg';
$ic_check = MONA_THEME_PATH_URI . '/assets/images/ic-check-pri.svg';

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => 'Trang chủ', 'url' => home_url('/'),         'is-active' => false],
        ['title' => 'Khóa học',  'url' => home_url('/khoa-hoc'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '',                'is-active' => true],
    ],
]); ?>

<div class="page-khoa-hoc section-pd">

    <div class="container">

        <!-- ── CONTENT + ASIDE ──────────────────────────────────────────── -->
        <section class="sec-kh-content pb-(--pd-sc)">
            <div class="relative">
                <div class="row">

                    <!-- Left: gallery + long-form content -->
                    <div class="col col-7 max-md:!w-full">

                        <!-- ── PAGE HEADER + GALLERY ────────────────────────────── -->
                        <div class="flex flex-col max-md:flex-col-reverse gap-8 mb-8">

                            <!-- ── PAGE HEADER ──────────────────────────────────────── -->
                            <div class="flex flex-col gap-8">
                                <div class="flex flex-col gap-2">

                                    <!-- Badges: taxonomy + level + share -->
                                    <?php $kh_terms = get_the_terms($post_id, 'bo_mon_khoa_hoc'); ?>
                                    <div class="flex items-center gap-2 flex-wrap mb-3">
                                        <span class="text-[11px] font-medium uppercase tracking-[1px] px-3 py-1 rounded-full border border-[#c0c8c6] text-[#414847]">
                                            Khóa Học
                                        </span>
                                        <button type="button"
                                            class="ml-auto w-9 h-9 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#c2a056] hover:text-[#c2a056] transition-colors shrink-0 cursor-pointer"
                                            data-modal-open="share"
                                            data-share-url="<?php echo esc_url(get_permalink()); ?>"
                                            data-share-title="<?php echo esc_attr(get_the_title()); ?>"
                                            aria-label="Chia sẻ">
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
                                    <?php if ($kh_description) : ?>
                                        <p class="text-[#414847] text-[16px] leading-[26px] mt-3">
                                            <?php echo wp_kses_post(nl2br(esc_html($kh_description))); ?>
                                        </p>
                                    <?php endif; ?>

                                </div>

                                <!-- Start date + location -->
                                <!-- <?php if ($start_date || $kh_location) : ?>
                                    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-3">
                                        <?php if ($start_date) : ?>
                                            <div class="flex items-start gap-3">
                                                <div class="size-10 flex items-center justify-center rounded-[10px] bg-[#f7f5f0] shrink-0">
                                                    <svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col justify-center min-h-10">
                                                    <span class="text-[#1b1c19] font-medium text-[15px] leading-[22px]"><?php echo esc_html($start_date); ?></span>
                                                    <?php if ($kh_time) : ?>
                                                        <span class="text-[#414847] text-[14px] leading-[20px]"><?php echo esc_html($kh_time); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($kh_location) : ?>
                                            <div class="flex items-start gap-3">
                                                <div class="size-10 flex items-center justify-center rounded-[10px] bg-[#f7f5f0] shrink-0">
                                                    <svg class="size-[18px] text-[#9ca3af]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col justify-center min-h-10">
                                                    <span class="text-[#1b1c19] font-medium text-[15px] leading-[22px]"><?php echo esc_html($kh_location); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?> -->
                            </div>

                            <!-- ── GALLERY ──────────────────────────────────────────── -->
                            <div class="rounded-[12px] overflow-hidden">

                                <!-- Main large image -->
                                <a href="<?php echo esc_url($thumb ?: $fb_main); ?>"
                                    data-fancybox="gallery-kh"
                                    data-caption="<?php echo esc_attr($thumb_alt); ?>"
                                    class="block md:h-[420px] max-md:h-[240px] overflow-hidden">
                                    <img src="<?php echo esc_url($thumb ?: $fb_main); ?>"
                                        class="block w-full h-full object-cover cursor-zoom-in"
                                        alt="<?php echo esc_attr($thumb_alt); ?>">
                                </a>

                                <!-- 8 thumbnails (2 rows × 4) -->
                                <div class="grid grid-cols-4 gap-[2px] mt-[2px] max-md:hidden">
                                    <!-- Row 1 -->
                                    <a href="<?php echo esc_url($gal_2['url'] ?? $fb_sub); ?>"
                                        data-fancybox="gallery-kh"
                                        class="block aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($gal_2['url'] ?? $fb_sub); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                            alt="<?php echo esc_attr($gal_2['alt'] ?? ''); ?>">
                                    </a>
                                    <a href="<?php echo esc_url($gal_3['url'] ?? $fb_sub); ?>"
                                        data-fancybox="gallery-kh"
                                        class="block aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($gal_3['url'] ?? $fb_sub); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                            alt="<?php echo esc_attr($gal_3['alt'] ?? ''); ?>">
                                    </a>
                                    <a href="<?php echo esc_url($gal_4['url'] ?? $fb_sub); ?>"
                                        data-fancybox="gallery-kh"
                                        class="block aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($gal_4['url'] ?? $fb_sub); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                            alt="<?php echo esc_attr($gal_4['alt'] ?? ''); ?>">
                                    </a>
                                    <a href="<?php echo esc_url($gal_5['url'] ?? $fb_sub); ?>"
                                        data-fancybox="gallery-kh"
                                        class="block aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($gal_5['url'] ?? $fb_sub); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                            alt="<?php echo esc_attr($gal_5['alt'] ?? ''); ?>">
                                    </a>
                                    <!-- Row 2 -->
                                    <a href="<?php echo esc_url($gal_6['url'] ?? $fb_sub); ?>"
                                        data-fancybox="gallery-kh"
                                        class="block aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($gal_6['url'] ?? $fb_sub); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                            alt="<?php echo esc_attr($gal_6['alt'] ?? ''); ?>">
                                    </a>
                                    <a href="<?php echo esc_url($gal_7['url'] ?? $fb_sub); ?>"
                                        data-fancybox="gallery-kh"
                                        class="block aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($gal_7['url'] ?? $fb_sub); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                            alt="<?php echo esc_attr($gal_7['alt'] ?? ''); ?>">
                                    </a>
                                    <a href="<?php echo esc_url($gal_8['url'] ?? $fb_sub); ?>"
                                        data-fancybox="gallery-kh"
                                        class="block aspect-square overflow-hidden">
                                        <img src="<?php echo esc_url($gal_8['url'] ?? $fb_sub); ?>"
                                            class="block w-full h-full object-cover cursor-zoom-in hover:scale-105 transition-transform duration-300"
                                            alt="<?php echo esc_attr($gal_8['alt'] ?? ''); ?>">
                                    </a>
                                    <!-- Last thumbnail with "Xem tất cả" overlay -->
                                    <div class="relative aspect-square overflow-hidden">
                                        <a href="<?php echo esc_url($gal_9['url'] ?? $fb_sub); ?>"
                                            data-fancybox="gallery-kh"
                                            class="block w-full h-full">
                                            <img src="<?php echo esc_url($gal_9['url'] ?? $fb_sub); ?>"
                                                class="block w-full h-full object-cover cursor-zoom-in"
                                                alt="<?php echo esc_attr($gal_9['alt'] ?? ''); ?>">
                                        </a>
                                        <button data-gallery-trigger="gallery-kh"
                                            class="absolute inset-0 flex flex-col items-center justify-center gap-1 bg-black/50 text-white hover:bg-black/60 transition-colors">
                                            <svg class="size-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                            <span class="text-[11px] font-semibold leading-tight text-center">Xem tất cả</span>
                                        </button>
                                    </div>
                                </div>

                                <button data-gallery-trigger="gallery-kh"
                                    class="md:hidden w-full flex items-center justify-center gap-2 py-3 bg-(--color-pri) text-white text-[13px] font-semibold hover:opacity-90 transition-opacity">
                                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    Xem tất cả ảnh
                                </button>

                            </div>

                        </div><!-- end PAGE HEADER + GALLERY wrapper -->

                        <!-- ── MOBILE BOOKING SLOT ───────────────────────────────── -->
                        <div id="booking-mobile-slot" class="md:hidden mb-8"></div>

                        <div class="flex flex-col divide-y divide-[#e4e2dd]">

                            <!-- 1. About the course -->
                            <div class="pb-10">
                                <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-5">
                                    Về khóa học
                                </h2>
                                <?php if ($short_desc) : ?>
                                    <p class="text-[#414847] text-[16px] leading-[26px] mb-3">
                                        <?php echo wp_kses_post(nl2br(esc_html($short_desc))); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($exp_desc) : ?>
                                    <div class="text-[#414847] text-[16px] leading-[26px]">
                                        <?php echo wp_kses_post(nl2br(esc_html($exp_desc))); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- 2. Benefits & Intentions -->
                            <?php if (!empty($bn_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-6">
                                        Mục tiêu &amp; Lợi ích
                                    </h2>
                                    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-[1px] bg-[#e4e2dd] border border-[#e4e2dd] rounded-[8px] overflow-hidden">
                                        <?php foreach ($bn_items as $item) : ?>
                                            <div class="bg-[#fbf9f4] p-5">
                                                <h4 class="text-pri text-[15px] font-semibold leading-[24px] mb-2">
                                                    <?php echo esc_html($item['benefit_title']); ?>
                                                </h4>
                                                <?php if (!empty($item['benefit_desc'])) : ?>
                                                    <p class="text-[#414847] text-[14px] leading-[22px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['benefit_desc']))); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 3. Healing journey -->
                            <?php if (!empty($rm_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-8">
                                        Hành trình chữa lành
                                    </h2>
                                    <div class="flex flex-col divide-y divide-[#e4e2dd]">
                                        <?php foreach ($rm_items as $i => $item) : ?>
                                            <div class="py-6 first:pt-0 flex gap-5 items-start">
                                                <span class="font-title text-pri text-[18px] font-semibold shrink-0 min-w-[24px] leading-[28px] mt-[2px]">
                                                    <?php echo $i + 1; ?>.
                                                </span>
                                                <div class="flex flex-col gap-2">
                                                    <h3 class="font-title text-pri text-[18px] leading-[26px] font-semibold">
                                                        <?php echo esc_html($item['week_title']); ?>
                                                    </h3>
                                                    <?php if (!empty($item['week_desc'])) : ?>
                                                        <p class="text-[#414847] text-[15px] leading-[23px]">
                                                            <?php echo wp_kses_post(nl2br(esc_html($item['week_desc']))); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($item['week_tags'])) :
                                                        $tags = array_map('trim', explode(',', $item['week_tags']));
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
                            <?php if (!empty($receive_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-6">
                                        Lợi ích khóa học
                                    </h2>
                                    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-[1px] bg-[#e4e2dd] border border-[#e4e2dd] rounded-[8px] overflow-hidden">
                                        <?php foreach ($receive_items as $item) : ?>
                                            <div class="bg-white p-5">
                                                <h4 class="text-pri text-[15px] font-semibold leading-[24px] mb-2">
                                                    <?php echo esc_html($item['receive_title']); ?>
                                                </h4>
                                                <?php if (!empty($item['receive_desc'])) : ?>
                                                    <p class="text-[#414847] text-[14px] leading-[22px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['receive_desc']))); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- 5. About the Instructor -->
                            <?php if ($ins_name) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-6">
                                        Về giảng viên
                                    </h2>
                                    <div class="flex gap-5 items-start">
                                        <div class="size-16 rounded-full overflow-hidden shrink-0">
                                            <img src="<?php echo esc_url($ins_image['url'] ?? $fb_ins); ?>"
                                                class="block w-full h-full object-cover"
                                                alt="<?php echo esc_attr($ins_image['alt'] ?? $ins_name); ?>">
                                        </div>
                                        <div>
                                            <h3 class="font-title text-pri text-[20px] leading-[28px] font-semibold">
                                                <?php echo esc_html($ins_name); ?>
                                            </h3>
                                            <?php if ($ins_bio) : ?>
                                                <p class="text-[#414847] text-[15px] leading-[23px] mt-2">
                                                    <?php echo wp_kses_post(nl2br(esc_html($ins_bio))); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($ins_instagram || $ins_facebook || $ins_whatsapp || $ins_messenger) : ?>
                                                <div class="flex gap-2 mt-4">
                                                    <?php if ($ins_instagram) : ?>
                                                        <a href="<?php echo esc_url($ins_instagram); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($ins_facebook) : ?>
                                                        <a href="<?php echo esc_url($ins_facebook); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($ins_whatsapp) : ?>
                                                        <a href="<?php echo esc_url($ins_whatsapp); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($ins_messenger) : ?>
                                                        <a href="<?php echo esc_url($ins_messenger); ?>" target="_blank" rel="noopener noreferrer"
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

                            <!-- 6. Testimonials -->
                            <?php
                            $fb_heading = get_field('feedbacks_heading', $post_id) ?: 'Testimonials';
                            $fb_items   = get_field('feedbacks', $post_id) ?: [
                                ['fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-1.jpg', 'alt' => '']],
                                ['fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-2.jpg', 'alt' => '']],
                                ['fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-3.jpg', 'alt' => '']],
                                ['fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-4.jpg', 'alt' => '']],
                                ['fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-1.jpg', 'alt' => '']],
                                ['fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-2.jpg', 'alt' => '']],
                            ];
                            if (!empty($fb_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-bold mb-6">
                                        <?php echo esc_html($fb_heading); ?>
                                    </h2>
                                    <div class="grid grid-cols-3 max-md:grid-cols-2 gap-3">
                                        <?php foreach ($fb_items as $item) :
                                            $img_url = $item['fb_image']['url'] ?? '';
                                            $img_alt = $item['fb_image']['alt'] ?? '';
                                            if (!$img_url) continue;
                                        ?>
                                            <a href="<?php echo esc_url($img_url); ?>"
                                                data-fancybox="gallery-fb-kh"
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
                        <div id="form-dang-ky"
                            class="sticky top-[100px] md:max-h-[calc(100vh-100px)] overflow-y-auto bg-white border border-[#e4e2dd] rounded-[12px] shadow-[0_6px_20px_rgba(19,58,53,0.08)] p-6 max-md:p-4 flex flex-col gap-2">

                            <!-- Meta box -->
                            <?php
                            $meta_rows = [];
                            if ($start_date)   $meta_rows[] = ['label' => 'KHAI GIẢNG',  'value' => $start_date,   'type' => 'text',     'icon' => 'calendar'];
                            if ($kh_time)      $meta_rows[] = ['label' => 'THỜI GIAN',   'value' => $kh_time,      'type' => 'text',     'icon' => 'clock'];
                            if ($duration)     $meta_rows[] = ['label' => 'THỜI LƯỢNG',  'value' => $duration,     'type' => 'text',     'icon' => 'clock'];
                            if ($kh_capacity)  $meta_rows[] = ['label' => 'SỐ HỌC VIÊN', 'value' => $kh_capacity,  'type' => 'text',     'icon' => 'users'];
                            if ($kh_location)  $meta_rows[] = ['label' => 'ĐỊA ĐIỂM',   'value' => $kh_location,  'type' => 'location', 'icon' => 'location'];
                            $has_spots = $kh_spots !== null;
                            if ($price || !empty($meta_rows) || $has_spots) : ?>
                                <div class="pb-5 border-b border-[#e4e2dd]">
                                    <?php if ($price) : ?>
                                        <div class="flex items-baseline gap-1 mb-4">
                                            <span class="font-title text-pri text-[28px] max-md:text-[20px] font-semibold"><?php echo esc_html($price); ?></span>
                                            <span class="text-[#717171] text-[14px]">/ khóa học</span>
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
                                                        <p class="text-[11px] font-medium text-[#717171] mb-0.5"><?php echo $row['label']; ?></p>
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
                                                        <p class="text-[11px] font-medium text-[#717171] mb-1">CHỖ CÒN LẠI</p>
                                                        <?php if ($kh_spots === 0) : ?>
                                                            <span class="inline-flex items-center gap-1.5 bg-[#fef9c3] text-[#854d0e] text-[12px] font-semibold px-2 py-1 rounded-[4px]">Fully Booked / Hết chỗ</span>
                                                        <?php else : ?>
                                                            <span class="inline-flex items-center gap-1.5 bg-[#fef9c3] text-[#854d0e] text-[12px] font-semibold px-2 py-1 rounded-[4px]">Còn <?php echo $kh_spots; ?> chỗ trống</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- CF7 Form -->
                            <div id="kh-form-inner" class="flex flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php
                                $cf7_id = defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '';
                                if ($cf7_id) : ?>
                                    <div class="cf7-khoa-hoc">
                                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_id) . '"]'); ?>
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
        var widget = document.getElementById('form-dang-ky');
        var slot = document.getElementById('booking-mobile-slot');
        if (!widget || !slot) return;
        widget.classList.remove('sticky');
        slot.appendChild(widget);
    })();
</script>
<?php get_footer(); ?>