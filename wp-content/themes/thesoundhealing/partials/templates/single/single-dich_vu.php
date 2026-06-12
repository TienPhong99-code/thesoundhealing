<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// ── Thông tin ──
$dv_duration    = get_field('dv_duration',    $post_id) ?: '60 - 90 phút mỗi phiên';
$dv_location    = get_field('dv_location',    $post_id) ?: 'Aetheria Sanctuary, Level 4, Thảo Điền';
$dv_avail_days  = get_field('dv_available_days', $post_id);
$dv_branch      = get_field('dv_branch',      $post_id);
$dv_short_desc  = get_field('dv_short_desc',  $post_id);
$dv_price       = get_field('dv_price',       $post_id);
$dv_spots_raw   = get_field('dv_spots',       $post_id);
$dv_spots       = ($dv_spots_raw !== '' && $dv_spots_raw !== null) ? (int) $dv_spots_raw : null;
$dv_guests      = get_field('dv_guests', $post_id);

// ── Gallery ──
$thumb      = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt  = get_the_title($post_id);
$banner_img = get_field('dv_banner_image', $post_id);
$gal_3      = get_field('dv_exp_image_1',  $post_id);
$gal_4      = get_field('dv_exp_image_2',  $post_id);
$gal_5      = get_field('dv_gallery_5',    $post_id);

// ── Mô tả / Trải nghiệm ──
$dv_exp_title = get_field('dv_exp_title', $post_id) ?: 'Hành trình Trải nghiệm';
$dv_exp_desc  = get_field('dv_exp_desc',  $post_id) ?: 'Mỗi buổi Tắm Âm được thiết kế như một nghi lễ thanh tẩy. Bạn sẽ được nằm thoải mái trên thảm, hỗ trợ bởi gối lót và chăn ấm. Trong không gian tĩnh lặng, những rung động tinh khiết từ bộ chuông pha lê sẽ bao bọc cơ thể, giúp giải phóng các tắc nghẽn năng lượng.';

// ── Lộ trình ──
$dv_rm_label   = get_field('dv_roadmap_label',   $post_id) ?: 'LỘ TRÌNH TRỊ LIỆU';
$dv_rm_heading = get_field('dv_roadmap_heading', $post_id) ?: 'Hành trình';
$dv_rm_items   = get_field('dv_roadmap_items',   $post_id) ?: [
    [
        'dv_week_title' => 'Bước 1 – Ổn định & Kết nối hơi thở',
        'dv_week_desc'  => 'Bạn được hướng dẫn nằm thoải mái trên thảm với gối lót và chăn ấm, sau đó đưa cơ thể về trạng thái thư giãn qua các kỹ thuật thở có chủ đích.',
        'dv_week_tags'  => 'Thở có ý thức, Thư giãn hệ thần kinh, Grounding',
    ],
    [
        'dv_week_title' => 'Bước 2 – Đắm mình trong sóng âm',
        'dv_week_desc'  => 'Những rung động tinh khiết từ bộ chuông pha lê bao bọc cơ thể, giúp giải phóng các tắc nghẽn năng lượng và đưa sóng não về trạng thái thư giãn sâu.',
        'dv_week_tags'  => 'Singing Bowl, Sound Bath, Âm điều trị',
    ],
    [
        'dv_week_title' => 'Bước 3 – Tích hợp & Trở về',
        'dv_week_desc'  => 'Nhẹ nhàng đưa cơ thể trở lại trạng thái tỉnh thức, lắng nghe cảm nhận và nhận hướng dẫn chăm sóc bản thân sau buổi trị liệu.',
        'dv_week_tags'  => 'Thiền định, Tích hợp thực hành, Chăm sóc bản thân',
    ],
];

// ── Lợi ích ──
$dv_bn_heading = get_field('dv_benefits_heading', $post_id) ?: 'Lợi ích của liệu pháp';
$dv_bn_items   = get_field('dv_benefits_items',   $post_id) ?: [
    ['dv_benefit_title' => 'CẢI THIỆN GIẤC NGỦ',      'dv_benefit_desc' => 'Giúp đưa sóng não về trạng thái Delta và Theta, hỗ trợ ngủ sâu và ngon hơn.'],
    ['dv_benefit_title' => 'GIẢM STRESS & CĂNG THẲNG', 'dv_benefit_desc' => 'Hạ mức cortisol trong máu, làm dịu hệ thống thần kinh thực vật sau những giờ làm việc mệt mỏi.'],
    ['dv_benefit_title' => 'MINH MẪN TÂM TRÍ',         'dv_benefit_desc' => 'Giải phóng những suy nghĩ thừa thãi, giúp bạn tập trung và sáng tạo hơn.'],
];

// ── Lợi ích nhận được ──
$dv_receive_items = get_field('dv_receive_items', $post_id) ?: [
    ['dv_receive_title' => '100% Trải nghiệm cá nhân hóa | Personalized Experience',         'dv_receive_desc' => 'Mỗi buổi trị liệu được điều chỉnh theo trạng thái cơ thể và cảm xúc của bạn, đảm bảo trải nghiệm chữa lành trọn vẹn nhất.'],
    ['dv_receive_title' => 'Thư giãn cơ thể, ngủ sâu hơn | Relax your body, sleep deeper',   'dv_receive_desc' => 'Rung động từ chuông pha lê giúp giải phóng căng thẳng, làm dịu tâm trí và hỗ trợ giấc ngủ sâu, sự tập trung tốt hơn.'],
    ['dv_receive_title' => 'Lịch linh hoạt, dễ dàng đặt chỗ | Flexible schedule',            'dv_receive_desc' => 'Nhiều khung giờ trong tuần phù hợp với lịch trình bận rộn. Đặt lịch nhanh chóng qua form hoặc liên hệ trực tiếp.'],
    ['dv_receive_title' => 'Ưu đãi 15% cho lần trị liệu tiếp theo | 15% off next session',   'dv_receive_desc' => 'Nhận ưu đãi 15% khi đặt buổi trị liệu tiếp theo — duy trì hành trình chăm sóc thân tâm với chi phí tốt hơn.'],
];

// ── Người hướng dẫn ──
$dv_ins_label     = get_field('dv_instructor_label',     $post_id) ?: 'NGƯỜI HƯỚNG DẪN';
$dv_ins_image     = get_field('dv_instructor_image',     $post_id);
$dv_ins_name      = get_field('dv_instructor_name',      $post_id) ?: 'Linh Tâm';
$dv_ins_bio       = get_field('dv_instructor_bio',       $post_id) ?: 'Hơn 10 năm nghiên cứu và thực hành Sound Healing, Yoga & Thiền định. Được đào tạo tại Rishikesh (Ấn Độ) và các trung tâm trị liệu âm thanh tại Châu Á.';
$dv_ins_instagram = get_field('dv_instructor_instagram', $post_id);
$dv_ins_facebook  = get_field('dv_instructor_facebook',  $post_id);
$dv_ins_whatsapp  = get_field('dv_instructor_whatsapp',  $post_id);
$dv_ins_messenger = get_field('dv_instructor_messenger', $post_id);

// ── Terms ──
$terms     = get_the_terms($post_id, 'loai_dich_vu');
$term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : 'HEALING MODALITY';

// ── Fallbacks ──
$fb_main  = MONA_THEME_PATH_URI . '/assets/images/dv-hero-bg2.jpg';
$fb_sub   = MONA_THEME_PATH_URI . '/assets/images/dv-exp-main.jpg';
$fb_ins   = MONA_THEME_PATH_URI . '/assets/images/kh-instructor.jpg';
$ic_check = MONA_THEME_PATH_URI . '/assets/images/ic-check-pri.svg';

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => 'Trang chủ', 'url' => home_url('/'),        'is-active' => false],
        ['title' => 'Dịch Vụ',  'url' => home_url('/dich-vu'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '',               'is-active' => true],
    ],
]); ?>

<div class="page-dich-vu section-pd">

    <!-- ── PAGE HEADER ──────────────────────────────────────────────────── -->
    <div class="container">
        <div class="flex flex-col gap-8 mb-8">
            <div class="flex flex-col gap-2">
                <div class="flex gap-2 flex-wrap mb-3">
                    <span class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">
                        <?php echo esc_html($term_name); ?>
                    </span>
                </div>

                <h1 class="font-title text-pri text-[48px] max-md:text-[32px] leading-[56px] max-md:leading-[40px] tracking-[-0.96px] font-bold">
                    <?php the_title(); ?>
                </h1>
            </div>
            <!-- Quick meta: branch + available days -->
            <?php if ($dv_branch || $dv_avail_days) : ?>
                <div class="flex flex-wrap gap-x-5 gap-y-1.5 mt-4 text-[#414847] text-[15px]">
                    <?php if ($dv_branch) : ?>
                        <div class="flex items-center gap-1.5">
                            <svg class="size-4 text-[#4e635a] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span><?php echo esc_html($dv_branch); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($dv_avail_days) : ?>
                        <div class="flex items-center gap-1.5">
                            <svg class="size-4 text-[#4e635a] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <span><?php echo esc_html($dv_avail_days); ?></span>
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
                        data-fancybox="gallery-dv"
                        data-caption="<?php echo esc_attr($thumb_alt); ?>"
                        class="block w-full h-full">
                        <img src="<?php echo esc_url($thumb ?: $fb_main); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($thumb_alt); ?>">
                    </a>
                </div>

                <!-- 2×2 grid (desktop only) -->
                <div class="flex-[0.7] grid grid-cols-2 grid-rows-2 gap-2 max-md:hidden">
                    <a href="<?php echo esc_url($banner_img['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-dv"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($banner_img['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($banner_img['alt'] ?? ''); ?>">
                    </a>
                    <a href="<?php echo esc_url($gal_3['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-dv"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($gal_3['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($gal_3['alt'] ?? ''); ?>">
                    </a>
                    <a href="<?php echo esc_url($gal_4['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-dv"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($gal_4['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($gal_4['alt'] ?? ''); ?>">
                    </a>
                    <a href="<?php echo esc_url($gal_5['url'] ?? $fb_sub); ?>"
                        data-fancybox="gallery-dv"
                        class="overflow-hidden block">
                        <img src="<?php echo esc_url($gal_5['url'] ?? $fb_sub); ?>"
                            class="block w-full h-full object-cover cursor-zoom-in"
                            alt="<?php echo esc_attr($gal_5['alt'] ?? ''); ?>">
                    </a>
                </div>

                <!-- Xem tất cả ảnh -->
                <button data-gallery-trigger="gallery-dv"
                    class="absolute bottom-4 right-4 flex items-center gap-2 bg-white text-[#1b1c19] text-[13px] font-semibold border border-[#1b1c19] rounded-[6px] px-4 py-2 hover:bg-[#f5f3ee] transition-colors shadow-sm">
                    <svg class="size-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Xem tất cả ảnh
                </button>

            </div>
        </div>

        <!-- ── CONTENT + ASIDE ───────────────────────────────────────────────── -->
        <section class="sec-dv-content pb-(--pd-sc)">
            <div class="relative">
                <div class="row">

                    <!-- Left: long-form content -->
                    <div class="col col-7 max-md:!w-full">
                        <div class="flex flex-col divide-y divide-[#e4e2dd]">

                            <!-- 1. About the service -->
                            <div class="pb-10">
                                <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-5">
                                    Về dịch vụ
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

                            <!-- 2. Benefits & Intentions -->
                            <?php if (!empty($dv_bn_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-6">
                                        Mục tiêu &amp; Lợi ích
                                    </h2>
                                    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-[1px] bg-[#e4e2dd] border border-[#e4e2dd] rounded-[8px] overflow-hidden">
                                        <?php foreach ($dv_bn_items as $item) : ?>
                                            <div class="bg-[#fbf9f4] p-5 border-l-[1px] border-[#133a35]">
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
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-8">
                                        Hành trình chữa lành
                                    </h2>
                                    <div class="flex flex-col divide-y divide-[#e4e2dd]">
                                        <?php foreach ($dv_rm_items as $i => $item) : ?>
                                            <div class="py-6 first:pt-0 flex gap-5 items-start">
                                                <span class="font-title text-[#133a35] text-[18px] font-semibold shrink-0 min-w-[24px] leading-[28px] mt-[2px]">
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
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-6">
                                        Lợi ích dịch vụ
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
                                    <h2 class="font-title text-pri text-[24px] leading-[32px] font-normal mb-6">
                                        Về giảng viên
                                    </h2>
                                    <div class="flex gap-5 items-start">
                                        <div class="size-16 rounded-full overflow-hidden shrink-0">
                                            <img src="<?php echo esc_url($dv_ins_image['url'] ?? $fb_ins); ?>"
                                                class="block w-full h-full object-cover"
                                                alt="<?php echo esc_attr($dv_ins_image['alt'] ?? $dv_ins_name); ?>">
                                        </div>
                                        <div>
                                            <h3 class="font-title text-pri text-[20px] leading-[28px] font-semibold">
                                                <?php echo esc_html($dv_ins_name); ?>
                                            </h3>
                                            <?php if ($dv_ins_bio) : ?>
                                                <p class="text-[#414847] text-[15px] leading-[23px] mt-2">
                                                    <?php echo wp_kses_post(nl2br(esc_html($dv_ins_bio))); ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($dv_ins_instagram || $dv_ins_facebook || $dv_ins_whatsapp || $dv_ins_messenger) : ?>
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
                                                    <?php if ($dv_ins_whatsapp) : ?>
                                                        <a href="<?php echo esc_url($dv_ins_whatsapp); ?>" target="_blank" rel="noopener noreferrer"
                                                            class="size-8 flex items-center justify-center rounded-full border border-[#e4e2dd] text-[#414847] hover:border-[#4e635a] hover:text-[#4e635a] transition-colors">
                                                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?>
                                                    <?php if ($dv_ins_messenger) : ?>
                                                        <a href="<?php echo esc_url($dv_ins_messenger); ?>" target="_blank" rel="noopener noreferrer"
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
                            $fb_heading = get_field('dv_feedbacks_heading', $post_id) ?: 'Testimonials';
                            $fb_items   = get_field('dv_feedbacks', $post_id) ?: [
                                ['dv_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-exp-main.jpg',             'alt' => '']],
                                ['dv_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-exp-detail.jpg',           'alt' => '']],
                                ['dv_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/dv-chua-lanh-reiki-nhom.jpg', 'alt' => '']],
                                ['dv_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-1.jpg',           'alt' => '']],
                                ['dv_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-2.jpg',           'alt' => '']],
                                ['dv_fb_image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-1.jpg',         'alt' => '']],
                            ];
                            if (!empty($fb_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px]  font-normal mb-6">
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
                    <div class="col col-5 max-md:!w-full">
                        <div id="form-dat-lich"
                            class="sticky top-[100px] bg-white border border-[#e4e2dd] rounded-[12px] shadow-[0_6px_20px_rgba(19,58,53,0.08)] p-6 flex flex-col gap-5">

                            <!-- Price -->
                            <?php if ($dv_price) : ?>
                                <div class="flex items-baseline gap-1 justify-center">
                                    <span class="font-title text-center inline-block text-pri text-[26px] font-semibold">
                                        <?php echo esc_html($dv_price); ?>
                                    </span>
                                    <span class="text-[#717171] text-[14px]"> / khách</span>
                                </div>
                            <?php endif; ?>

                            <!-- Meta box -->
                            <?php
                            $meta_rows = [];
                            if ($dv_avail_days) $meta_rows[] = ['label' => 'LỊCH',              'value' => $dv_avail_days,  'type' => 'text'];
                            if ($dv_duration)   $meta_rows[] = ['label' => 'THỜI LƯỢNG',         'value' => $dv_duration,    'type' => 'text'];
                            if ($dv_guests)     $meta_rows[] = ['label' => 'SỐ KHÁCH / PHIÊN',   'value' => $dv_guests,      'type' => 'text'];
                            if ($dv_location)   $meta_rows[] = ['label' => 'ĐỊA ĐIỂM',           'value' => $dv_location,    'type' => 'location'];
                            $has_spots = $dv_spots !== null;
                            if (!empty($meta_rows) || $has_spots) : ?>
                                <div class="border border-[#b0b0b0] rounded-[8px] overflow-hidden text-[14px]">
                                    <?php foreach ($meta_rows as $row) : ?>
                                        <div class="p-3 border-b border-[#b0b0b0] last:border-b-0">
                                            <p class="text-[10px] font-semibold uppercase tracking-[1px] text-[#717171] mb-0.5">
                                                <?php echo $row['label']; ?>
                                            </p>
                                            <?php if ($row['type'] === 'location') :
                                                $lines = array_filter(array_map('trim', explode("\n", $row['value'])));
                                                if (count($lines) > 1) : ?>
                                                    <ul class="text-[#1b1c19] font-medium list-disc list-inside flex flex-col gap-0.5">
                                                        <?php foreach ($lines as $line) : ?>
                                                            <li><?php echo esc_html($line); ?></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php else : ?>
                                                    <p class="text-[#1b1c19] font-medium"><?php echo esc_html($row['value']); ?></p>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <p class="text-[#1b1c19] font-medium"><?php echo esc_html($row['value']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>

                                    <?php if ($has_spots) : ?>
                                        <div class="p-3">
                                            <p class="text-[10px] font-semibold uppercase tracking-[1px] text-[#717171] mb-1.5">THÊM</p>
                                            <?php if ($dv_spots === 0) : ?>
                                                <span class="inline-flex items-center gap-1.5 bg-[#fef9c3] text-[#854d0e] text-[12px] font-semibold px-3 py-1.5 rounded-[4px]">
                                                    Fully Booked / Hết chỗ
                                                </span>
                                            <?php else : ?>
                                                <span class="inline-flex items-center gap-1.5 bg-[#fef9c3] text-[#854d0e] text-[12px] font-semibold px-3 py-1.5 rounded-[4px]">
                                                    Only <?php echo $dv_spots; ?> Spots Left / Còn <?php echo $dv_spots; ?> chỗ
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- CF7 Form -->
                            <div id="dv-form-inner" class="border-t border-[#e4e2dd] pt-5 flex flex-col gap-3">
                                <h3 class="font-title text-pri text-center text-[28px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php
                                $dv_cf7_id = defined('DV_CF7_FORM_ID') ? DV_CF7_FORM_ID : (defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '');
                                if ($dv_cf7_id) : ?>
                                    <style>
                                        .cf7-dich-vu .wpcf7-form:not(.invalid):not(.failed) .wpcf7-not-valid-tip {
                                            display: none;
                                        }

                                        .cf7-dich-vu .wpcf7-form:not(.invalid):not(.failed) .wpcf7-form-control.wpcf7-not-valid {
                                            border-color: inherit;
                                        }
                                    </style>
                                    <div class="cf7-dich-vu">
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

<?php get_footer(); ?>