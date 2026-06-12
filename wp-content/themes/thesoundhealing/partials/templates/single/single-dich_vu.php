<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// ── Thông tin ──
$dv_duration    = get_field('dv_duration',    $post_id) ?: '60 - 90 phút mỗi phiên';
$dv_clothing    = get_field('dv_clothing',    $post_id) ?: 'Đồ tập hoặc quần áo thoải mái, nhẹ nhàng';
$dv_location    = get_field('dv_location',    $post_id) ?: 'Aetheria Sanctuary, Level 4, Thảo Điền';
$dv_preparation = get_field('dv_preparation', $post_id) ?: 'Hạn chế ăn no 2 tiếng trước giờ trị liệu';
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

// ── Feature cards ──
$dv_f1_icon  = get_field('dv_feature_1_icon',  $post_id) ?: MONA_THEME_PATH_URI . '/assets/images/ic-dv-feature-1.svg';
$dv_f1_title = get_field('dv_feature_1_title', $post_id) ?: 'Pha lê Alchemy';
$dv_f1_desc  = get_field('dv_feature_1_desc',  $post_id) ?: 'Sử dụng các loại chuông pha lê quý hiếm cho tần số chữa lành cao nhất.';
$dv_f2_icon  = get_field('dv_feature_2_icon',  $post_id) ?: MONA_THEME_PATH_URI . '/assets/images/ic-dv-feature-2.svg';
$dv_f2_title = get_field('dv_feature_2_title', $post_id) ?: 'Tĩnh lặng tuyệt đối';
$dv_f2_desc  = get_field('dv_feature_2_desc',  $post_id) ?: 'Không gian được cách âm hoàn toàn, tách biệt với thế giới bên ngoài.';

// ── Lợi ích ──
$dv_bn_heading = get_field('dv_benefits_heading', $post_id) ?: 'Lợi ích của liệu pháp';
$dv_bn_items   = get_field('dv_benefits_items',   $post_id) ?: [
    ['dv_benefit_title' => 'CẢI THIỆN GIẤC NGỦ',      'dv_benefit_desc' => 'Giúp đưa sóng não về trạng thái Delta và Theta, hỗ trợ ngủ sâu và ngon hơn.'],
    ['dv_benefit_title' => 'GIẢM STRESS & CĂNG THẲNG', 'dv_benefit_desc' => 'Hạ mức cortisol trong máu, làm dịu hệ thống thần kinh thực vật sau những giờ làm việc mệt mỏi.'],
    ['dv_benefit_title' => 'MINH MẪN TÂM TRÍ',         'dv_benefit_desc' => 'Giải phóng những suy nghĩ thừa thãi, giúp bạn tập trung và sáng tạo hơn.'],
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
                <p class="text-[12px] font-semibold uppercase mb-3">
                    <?php echo esc_html($term_name); ?>
                </p>

                <h1 class="font-title text-pri font-semibold text-[48px] max-md:text-[32px]">
                    <?php the_title(); ?>
                </h1>

                <?php if ($dv_short_desc) : ?>
                    <p class="text-[16px]  mt-3 max-w-[600px]">
                        <?php echo wp_kses_post(nl2br(esc_html($dv_short_desc))); ?>
                    </p>
                <?php endif; ?>
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

                            <!-- Description + Feature cards -->
                            <div class="pb-10">
                                <h2 class="font-title text-pri text-[24px]  font-normal mb-4">
                                    <?php echo esc_html($dv_exp_title); ?>
                                </h2>
                                <p class="text-[#414847] text-[16px]  mb-6">
                                    <?php echo wp_kses_post(nl2br(esc_html($dv_exp_desc))); ?>
                                </p>
                                <!-- Feature cards 2-col -->
                                <div class="grid grid-cols-2 max-md:grid-cols-1 gap-4">
                                    <div class="bg-[#f5f3ee] rounded-[4px] p-5 flex flex-col gap-2">
                                        <?php if ($dv_f1_icon) : ?>
                                            <div class="size-5 shrink-0 mb-1">
                                                <img src="<?php echo esc_url($dv_f1_icon); ?>"
                                                    class="block w-full h-full object-contain" alt="">
                                            </div>
                                        <?php endif; ?>
                                        <h4 class="font-title text-pri text-[20px]  font-normal">
                                            <?php echo esc_html($dv_f1_title); ?>
                                        </h4>
                                        <p class="text-[#414847] text-[16px] ">
                                            <?php echo esc_html($dv_f1_desc); ?>
                                        </p>
                                    </div>
                                    <div class="bg-[#f5f3ee] rounded-[4px] p-5 flex flex-col gap-2">
                                        <?php if ($dv_f2_icon) : ?>
                                            <div class="size-5 shrink-0 mb-1">
                                                <img src="<?php echo esc_url($dv_f2_icon); ?>"
                                                    class="block w-full h-full object-contain" alt="">
                                            </div>
                                        <?php endif; ?>
                                        <h4 class="font-title text-pri text-[20px]  font-normal">
                                            <?php echo esc_html($dv_f2_title); ?>
                                        </h4>
                                        <p class="text-[#414847] text-[16px] ">
                                            <?php echo esc_html($dv_f2_desc); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Benefits -->
                            <?php if (!empty($dv_bn_items)) : ?>
                                <div class="py-10">
                                    <h2 class="font-title text-pri text-[24px]  font-normal mb-6">
                                        <?php echo esc_html($dv_bn_heading); ?>
                                    </h2>
                                    <div class="flex flex-col gap-5">
                                        <?php foreach ($dv_bn_items as $item) : ?>
                                            <div class="flex gap-4 items-start">
                                                <div class="size-5 shrink-0 mt-[3px]">
                                                    <img src="<?php echo esc_url($ic_check); ?>"
                                                        class="block w-full h-full object-contain" alt="">
                                                </div>
                                                <div class="flex flex-col gap-1">
                                                    <p class="text-pri text-[16px] font-semibold uppercase tracking-[1.2px]">
                                                        <?php echo esc_html($item['dv_benefit_title']); ?>
                                                    </p>
                                                    <?php if (!empty($item['dv_benefit_desc'])) : ?>
                                                        <p class="text-[#414847] text-[16px] ">
                                                            <?php echo wp_kses_post(nl2br(esc_html($item['dv_benefit_desc']))); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Info grid: thời lượng / trang phục / địa điểm / chuẩn bị -->
                            <div class="py-10">
                                <h3 class="font-title text-pri text-[24px]  font-normal mb-6">
                                    Thông tin cần lưu ý
                                </h3>
                                <div class="grid grid-cols-2 max-md:grid-cols-1 gap-x-8 gap-y-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">THỜI LƯỢNG</span>
                                        <span class="text-[#1b1c19] text-[16px] "><?php echo esc_html($dv_duration); ?></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">TRANG PHỤC</span>
                                        <span class="text-[#1b1c19] text-[16px] "><?php echo esc_html($dv_clothing); ?></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">ĐỊA ĐIỂM</span>
                                        <span class="text-[#1b1c19] text-[16px] "><?php echo esc_html($dv_location); ?></span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">CHUẨN BỊ</span>
                                        <span class="text-[#1b1c19] text-[16px] "><?php echo esc_html($dv_preparation); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Feedback photos -->
                            <?php
                            $fb_heading = get_field('dv_feedbacks_heading', $post_id) ?: 'Khách hàng nói gì?';
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
                                <h3 class="font-title text-pri text-[28px] text-center font-normal">
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