<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// ── Thông tin ──
$dv_duration    = get_field('dv_duration',    $post_id) ?: '60 - 90 phút mỗi phiên';
$dv_clothing    = get_field('dv_clothing',    $post_id) ?: 'Đồ tập hoặc quần áo thoải mái, nhẹ nhàng';
$dv_location    = get_field('dv_location',    $post_id) ?: 'Aetheria Sanctuary, Level 4, Thảo Điền';
$dv_preparation = get_field('dv_preparation', $post_id) ?: 'Hạn chế ăn no 2 tiếng trước giờ trị liệu';
$dv_short_desc  = get_field('dv_short_desc',  $post_id);
$dv_price       = get_field('dv_price',       $post_id);

// ── Trải nghiệm ──
$dv_exp_title  = get_field('dv_exp_title',   $post_id) ?: 'Hành trình Trải nghiệm';
$dv_exp_desc   = get_field('dv_exp_desc',    $post_id) ?: 'Mỗi buổi Tắm Âm được thiết kế như một nghi lễ thanh tẩy. Bạn sẽ được nằm thoải mái trên thảm, hỗ trợ bởi gối lót và chăn ấm. Trong không gian tĩnh lặng, những rung động tinh khiết từ bộ chuông pha lê sẽ bao bọc cơ thể, giúp giải phóng các tắc nghẽn năng lượng.';
$dv_exp_img_1  = get_field('dv_exp_image_1', $post_id);
$dv_exp_img_2  = get_field('dv_exp_image_2', $post_id);

$dv_f1_icon  = get_field('dv_feature_1_icon',  $post_id) ?: MONA_THEME_PATH_URI . '/assets/images/ic-dv-feature-1.svg';
$dv_f1_title = get_field('dv_feature_1_title', $post_id) ?: 'Pha lê Alchemy';
$dv_f1_desc  = get_field('dv_feature_1_desc',  $post_id) ?: 'Sử dụng các loại chuông pha lê quý hiếm cho tần số chữa lành cao nhất.';
$dv_f2_icon  = get_field('dv_feature_2_icon',  $post_id) ?: MONA_THEME_PATH_URI . '/assets/images/ic-dv-feature-2.svg';
$dv_f2_title = get_field('dv_feature_2_title', $post_id) ?: 'Tĩnh lặng tuyệt đối';
$dv_f2_desc  = get_field('dv_feature_2_desc',  $post_id) ?: 'Không gian được cách âm hoàn toàn, tách biệt với thế giới bên ngoài.';

// ── Lợi ích ──
$dv_bn_heading = get_field('dv_benefits_heading', $post_id) ?: 'Lợi ích của liệu pháp';
$dv_bn_items   = get_field('dv_benefits_items',   $post_id) ?: [
    [
        'dv_benefit_title' => 'CẢI THIỆN GIẤC NGỦ',
        'dv_benefit_desc'  => 'Giúp đưa sóng não về trạng thái Delta và Theta, hỗ trợ ngủ sâu và ngon hơn.',
    ],
    [
        'dv_benefit_title' => 'GIẢM STRESS & CĂNG THẲNG',
        'dv_benefit_desc'  => 'Hạ mức cortisol trong máu, làm dịu hệ thống thần kinh thực vật sau những giờ làm việc mệt mỏi.',
    ],
    [
        'dv_benefit_title' => 'MINH MẪN TÂM TRÍ',
        'dv_benefit_desc'  => 'Giải phóng những suy nghĩ thừa thãi, giúp bạn tập trung và sáng tạo hơn.',
    ],
];

// ── Người hướng dẫn ──
$dv_ins_label = get_field('dv_instructor_label', $post_id) ?: 'NGƯỜI HƯỚNG DẪN';
$dv_ins_image = get_field('dv_instructor_image', $post_id);
$dv_ins_name  = get_field('dv_instructor_name',  $post_id) ?: 'Linh Tâm';
$dv_ins_bio   = get_field('dv_instructor_bio',   $post_id) ?: 'Hơn 10 năm nghiên cứu và thực hành Sound Healing, Yoga & Thiền định. Được đào tạo tại Rishikesh (Ấn Độ) và các trung tâm trị liệu âm thanh tại Châu Á.';

// ── CTA ──
$dv_cta_title = get_field('dv_cta_title', $post_id) ?: 'Bắt đầu hành trình chữa lành';
$dv_cta_desc  = get_field('dv_cta_desc',  $post_id) ?: 'Mỗi buổi trị liệu là một bước tiến trên hành trình khám phá và chữa lành bản thân. Đặt lịch ngay hôm nay.';

// ── Media ──
$banner_img = get_field('dv_banner_image', $post_id);
$banner_url = $banner_img['url'] ?? '';
$banner_alt = $banner_img['alt'] ?? get_the_title($post_id);
$thumb      = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt  = get_the_title($post_id);
$terms     = get_the_terms($post_id, 'loai_dich_vu');
$term_name = (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : 'HEALING MODALITY';

// ── Fallbacks ──
$fallback_hero    = MONA_THEME_PATH_URI . '/assets/images/dv-hero-bg2.jpg';
$fallback_exp1    = MONA_THEME_PATH_URI . '/assets/images/dv-exp-main.jpg';
$fallback_exp2    = MONA_THEME_PATH_URI . '/assets/images/dv-exp-detail.jpg';
$fallback_ins     = MONA_THEME_PATH_URI . '/assets/images/kh-instructor.jpg';
$ic_check         = MONA_THEME_PATH_URI . '/assets/images/ic-check-pri.svg';

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => 'Trang chủ', 'url' => home_url('/'),          'is-active' => false],
        ['title' => 'Dịch Vụ',   'url' => home_url('/dich-vu'),   'is-active' => false],
        ['title' => get_the_title(), 'url' => '',                   'is-active' => true],
    ],
]); ?>

<div class="page-dich-vu" style="background:#fbf9f4;">

    <!-- ── HERO 100VH ────────────────────────────────────────────────── -->
    <section class="sec-dv-hero relative h-screen min-h-[600px] overflow-hidden">

        <!-- Background image -->
        <div class="absolute inset-0">
            <img src="<?php echo esc_url($banner_url ?: $fallback_hero); ?>"
                class="block w-full h-full object-cover object-center"
                alt="<?php echo esc_attr($banner_alt); ?>">
        </div>

        <!-- Gradient overlay: solid left → transparent right -->
        <div class="absolute inset-0"
            style="background: linear-gradient(90deg, #fbf9f4 0%, rgba(251,249,244,0.7) 40%, rgba(251,249,244,0.15) 70%, rgba(251,249,244,0) 100%);"></div>

        <!-- Content: bottom-left -->
        <div class="absolute inset-x-0 bottom-0 pb-[80px] max-md:pb-[48px]">
            <div class="container">
                <div class="flex flex-col gap-4 max-w-[900px]">

                    <p class="text-[#133a35] text-[12px] font-semibold uppercase tracking-[2.4px]">
                        <?php echo esc_html($term_name); ?>
                    </p>

                    <h1 class="font-title text-[#133a35] text-[56px] max-md:text-[36px] leading-[64px] max-md:leading-[44px] tracking-[-1.12px] font-normal">
                        <?php the_title(); ?>
                    </h1>

                    <?php if ($dv_short_desc) : ?>
                        <p class="text-[#414847] text-[18px] max-md:text-[16px] leading-[29px] max-w-[560px]">
                            <?php echo wp_kses_post(nl2br(esc_html($dv_short_desc))); ?>
                        </p>
                    <?php endif; ?>

                    <div class="flex items-center gap-4 pt-4">
                        <a href="#form-dat-lich"
                            class="bg-[#133a35] text-white text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-[17px] rounded-[4px] hover:bg-[#1d5047] transition-colors">
                            ĐẶT LỊCH NGAY
                        </a>
                        <?php if ($dv_price) : ?>
                            <span class="font-title text-[#133a35] text-[20px] leading-[28px] font-normal">
                                <?php echo esc_html($dv_price); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- ── EXPERIENCE ────────────────────────────────────────────────── -->
    <section class="sec-dv-exp py-(--pd-sc)">
        <div class="container">
            <div class="row items-center gap-y-12">

                <!-- Left: images -->
                <div class="col col-6 max-md:!w-full">
                    <div class="relative">
                        <!-- Main tall image -->
                        <div class="overflow-hidden rounded-[4px] bg-[#eae8e3]" style="height:625px;">
                            <img src="<?php echo esc_url($dv_exp_img_1['url'] ?? $fallback_exp1); ?>"
                                class="block w-full h-full object-cover"
                                alt="<?php echo esc_attr($dv_exp_img_1['alt'] ?? ''); ?>">
                        </div>
                        <!-- Detail overlay image: bottom-right -->
                        <div class="absolute right-0 translate-x-[10%] bottom-[-48px] w-[40%] bg-[#f5f3ee] p-2 rounded-[2px] shadow-[0px_4px_20px_rgba(19,58,53,0.08)] max-md:hidden">
                            <div class="overflow-hidden aspect-square grayscale">
                                <img src="<?php echo esc_url($dv_exp_img_2['url'] ?? $fallback_exp2); ?>"
                                    class="block w-full h-full object-cover"
                                    alt="<?php echo esc_attr($dv_exp_img_2['alt'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: text + feature cards -->
                <div class="col col-6 max-md:!w-full max-md:mt-0 mt-0">
                    <div class="flex flex-col gap-8 max-md:pl-0 pl-[60px]">

                        <h2 class="font-title text-[#133a35] text-[32px] max-md:text-[24px] leading-[40px] font-normal">
                            <?php echo esc_html($dv_exp_title); ?>
                        </h2>

                        <p class="text-[#414847] text-[16px] leading-[26px]">
                            <?php echo wp_kses_post(nl2br(esc_html($dv_exp_desc))); ?>
                        </p>

                        <!-- Feature cards 2-col -->
                        <div class="grid grid-cols-2 gap-6">

                            <!-- Feature 1 -->
                            <div class="bg-[#f5f3ee] rounded-[4px] p-6 flex flex-col gap-2">
                                <?php if ($dv_f1_icon) : ?>
                                    <div class="size-5 shrink-0 mb-1">
                                        <img src="<?php echo esc_url($dv_f1_icon); ?>"
                                            class="block w-full h-full object-contain" alt="">
                                    </div>
                                <?php endif; ?>
                                <h4 class="font-title text-[#133a35] text-[20px] leading-[28px] font-normal">
                                    <?php echo esc_html($dv_f1_title); ?>
                                </h4>
                                <p class="text-[#414847] text-[14px] leading-[20px]">
                                    <?php echo esc_html($dv_f1_desc); ?>
                                </p>
                            </div>

                            <!-- Feature 2 -->
                            <div class="bg-[#f5f3ee] rounded-[4px] p-6 flex flex-col gap-2">
                                <?php if ($dv_f2_icon) : ?>
                                    <div class="size-5 shrink-0 mb-1">
                                        <img src="<?php echo esc_url($dv_f2_icon); ?>"
                                            class="block w-full h-full object-contain" alt="">
                                    </div>
                                <?php endif; ?>
                                <h4 class="font-title text-[#133a35] text-[20px] leading-[28px] font-normal">
                                    <?php echo esc_html($dv_f2_title); ?>
                                </h4>
                                <p class="text-[#414847] text-[14px] leading-[20px]">
                                    <?php echo esc_html($dv_f2_desc); ?>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── CONTENT + ASIDE ───────────────────────────────────────────── -->
    <section class="sec-dv-content py-(--pd-sc)">
        <div class="container">
            <div class="row">

                <!-- Left: Benefits + Info grid + Instructor -->
                <div class="col col-7 max-md:!w-full">
                    <div class="flex flex-col gap-[64px]">

                        <!-- Benefits -->
                        <?php if (!empty($dv_bn_items)) : ?>
                            <div class="flex flex-col gap-8">
                                <h2 class="font-title text-[#133a35] text-[32px] max-md:text-[24px] leading-[40px] font-normal">
                                    <?php echo esc_html($dv_bn_heading); ?>
                                </h2>
                                <div class="flex flex-col gap-6">
                                    <?php foreach ($dv_bn_items as $item) : ?>
                                        <div class="flex gap-4 items-start">
                                            <div class="size-5 shrink-0 mt-[3px]">
                                                <img src="<?php echo esc_url($ic_check); ?>"
                                                    class="block w-full h-full object-contain" alt="">
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <p class="text-[#1b1c19] text-[12px] font-semibold uppercase tracking-[1.2px] leading-[16px]">
                                                    <?php echo esc_html($item['dv_benefit_title']); ?>
                                                </p>
                                                <?php if (!empty($item['dv_benefit_desc'])) : ?>
                                                    <p class="text-[#414847] text-[16px] leading-[24px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['dv_benefit_desc']))); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Info grid: Thời lượng / Trang phục / Địa điểm / Chuẩn bị -->
                        <div class="bg-[#fbf9f4] border border-[rgba(192,200,198,0.3)] rounded-[4px] p-10 max-md:p-6">
                            <h3 class="font-title text-[#133a35] text-[24px] leading-[32px] font-normal mb-8">
                                Thông tin cần lưu ý
                            </h3>
                            <div class="grid grid-cols-2 gap-x-8 gap-y-8">

                                <div class="flex flex-col gap-2">
                                    <span class="text-[rgba(19,58,53,0.6)] text-[12px] font-semibold uppercase tracking-[1.2px]">THỜI LƯỢNG</span>
                                    <span class="text-[#1b1c19] text-[16px] leading-[24px]"><?php echo esc_html($dv_duration); ?></span>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <span class="text-[rgba(19,58,53,0.6)] text-[12px] font-semibold uppercase tracking-[1.2px]">TRANG PHỤC</span>
                                    <span class="text-[#1b1c19] text-[16px] leading-[24px]"><?php echo esc_html($dv_clothing); ?></span>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <span class="text-[rgba(19,58,53,0.6)] text-[12px] font-semibold uppercase tracking-[1.2px]">ĐỊA ĐIỂM</span>
                                    <span class="text-[#1b1c19] text-[16px] leading-[24px]"><?php echo esc_html($dv_location); ?></span>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <span class="text-[rgba(19,58,53,0.6)] text-[12px] font-semibold uppercase tracking-[1.2px]">CHUẨN BỊ</span>
                                    <span class="text-[#1b1c19] text-[16px] leading-[24px]"><?php echo esc_html($dv_preparation); ?></span>
                                </div>

                            </div>
                        </div>

                        <!-- Instructor -->
                        <?php if ($dv_ins_name) : ?>
                            <div class="bg-[#f5f3ee] rounded-[4px] shadow-[0px_10px_20px_rgba(44,81,76,0.05)] max-md:p-4 p-8 flex flex-col gap-4">
                                <p class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">
                                    <?php echo esc_html($dv_ins_label); ?>
                                </p>
                                <div class="size-24 rounded-[12px] overflow-hidden shrink-0">
                                    <img src="<?php echo esc_url($dv_ins_image['url'] ?? $fallback_ins); ?>"
                                        class="block w-full h-full object-cover"
                                        alt="<?php echo esc_attr($dv_ins_image['alt'] ?? $dv_ins_name); ?>">
                                </div>
                                <h3 class="font-title text-[#133a35] text-[24px] leading-[32px] font-normal pt-2">
                                    <?php echo esc_html($dv_ins_name); ?>
                                </h3>
                                <?php if ($dv_ins_bio) : ?>
                                    <p class="text-[#414847] text-[16px] leading-[24px]">
                                        <?php echo wp_kses_post(nl2br(esc_html($dv_ins_bio))); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Right: Booking form aside -->
                <div class="col col-5 max-md:!w-full">
                    <div id="form-dat-lich"
                        class="sticky top-[100px] backdrop-blur-[6px] bg-[rgba(251,249,244,0.85)] border border-[rgba(255,255,255,0.4)] rounded-[4px] shadow-[0px_10px_40px_0px_rgba(19,58,53,0.05)] max-md:p-6 p-8 flex flex-col gap-6">

                        <div class="text-center flex flex-col gap-2 pb-6 border-b border-[rgba(192,200,198,0.3)]">
                            <h3 class="font-title text-[#133a35] text-[24px] leading-[32px] font-normal">
                                Đặt lịch trải nghiệm ngay
                            </h3>
                            <p class="text-[#414847] text-[14px] leading-[20px]">
                                Hãy chọn thời gian phù hợp để bắt đầu hành trình hồi phục.
                            </p>
                            <?php if ($dv_price) : ?>
                                <span class="font-title text-[#133a35] text-[20px] leading-[28px] font-normal mt-1">
                                    <?php echo esc_html($dv_price); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php
                        $dv_cf7_id = defined('DV_CF7_FORM_ID') ? DV_CF7_FORM_ID : (defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '');
                        if ($dv_cf7_id) : ?>
                            <style>
                                /* Ẩn lỗi validation cho đến khi form thực sự được submit */
                                .cf7-dich-vu .wpcf7-form:not(.invalid):not(.failed) .wpcf7-not-valid-tip {
                                    display: none;
                                }

                                .cf7-dich-vu .wpcf7-form:not(.invalid):not(.failed) .wpcf7-form-control.wpcf7-not-valid {
                                    border-color: inherit;
                                }
                            </style>
                            <div class="cf7-dich-vu">
                                <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($dv_cf7_id) . '"]'); ?>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var dateInput = document.querySelector('.cf7-dich-vu input[type="date"]');
                                        if (!dateInput) return;
                                        dateInput.addEventListener('click', function() {
                                            try {
                                                this.showPicker();
                                            } catch (e) {}
                                        });
                                    });
                                </script>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── CTA ───────────────────────────────────────────────────────── -->
    <section class="sec-dv-cta py-(--pd-sc) relative overflow-hidden" style="background:#133a35;">
        <div class="absolute inset-0 opacity-10"
            style="background-image:radial-gradient(circle at center, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 70%);"></div>
        <div class="container relative">
            <div class="flex flex-col items-center text-center max-w-[768px] mx-auto gap-6">
                <h2 class="font-title text-white text-[56px] max-md:text-[8vw] leading-[64px] max-md:leading-[50px] tracking-[-1.12px] font-normal">
                    <?php echo esc_html($dv_cta_title); ?>
                </h2>
                <p class="text-[rgba(255,255,255,0.8)] text-[18px] leading-[28px]">
                    <?php echo wp_kses_post(nl2br(esc_html($dv_cta_desc))); ?>
                </p>
                <a href="#form-dat-lich" class="bg-[#fbf9f4] text-[#133a35] text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-4 rounded-[4px] shadow-[0px_10px_15px_-3px_rgba(0,0,0,0.1)] hover:bg-white transition-colors mt-4">
                    ĐẶT LỊCH NGAY
                </a>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>