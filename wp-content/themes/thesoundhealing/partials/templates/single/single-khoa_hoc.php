<?php
defined('ABSPATH') || exit;

$post_id = get_the_ID();

// Fields
$level       = get_field('level',            $post_id);
$duration    = get_field('duration',         $post_id);
$short_desc  = get_field('short_desc',       $post_id);
$price       = get_field('price',            $post_id);

$exp_title   = get_field('exp_title',        $post_id) ?: 'Không gian của sự tĩnh lặng';
$exp_desc    = get_field('exp_desc',         $post_id);
$exp_img_1   = get_field('exp_image_1',      $post_id);
$exp_img_2   = get_field('exp_image_2',      $post_id);

$rm_label    = get_field('roadmap_label',    $post_id) ?: 'LỘ TRÌNH HỌC';
$rm_heading  = get_field('roadmap_heading',  $post_id) ?: 'Hành trình';
$rm_items    = get_field('roadmap_items',    $post_id) ?: [
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

$ins_label   = get_field('instructor_label', $post_id) ?: 'NGƯỜI HƯỚNG DẪN';
$ins_image   = get_field('instructor_image', $post_id);
$ins_name    = get_field('instructor_name',  $post_id) ?: 'Linh Tâm';
$ins_bio     = get_field('instructor_bio',   $post_id) ?: 'Hơn 10 năm nghiên cứu và thực hành Sound Healing, Yoga & Thiền định. Được đào tạo tại Rishikesh (Ấn Độ) và các trung tâm trị liệu âm thanh tại Châu Á. Linhtâm tin rằng mỗi người đều có khả năng chữa lành bản thân khi được trao đúng công cụ và không gian an toàn.';

$bn_heading  = get_field('benefits_heading', $post_id) ?: 'Bạn sẽ nhận được gì?';
$bn_items    = get_field('benefits_items',   $post_id) ?: [
    [
        'benefit_title' => 'Giảm căng thẳng & lo âu hiệu quả',
        'benefit_desc'  => 'Các kỹ thuật thở và âm thanh giúp điều hòa hệ thần kinh tự chủ, giảm mức cortisol và tạo cảm giác bình an sâu sắc.',
    ],
    [
        'benefit_title' => 'Cải thiện chất lượng giấc ngủ',
        'benefit_desc'  => 'Thực hành đều đặn giúp cơ thể và tâm trí dễ dàng đi vào trạng thái thư giãn sâu, hỗ trợ giấc ngủ tự nhiên hơn.',
    ],
    [
        'benefit_title' => 'Tăng khả năng tập trung & kết nối với bản thân',
        'benefit_desc'  => 'Kỹ năng thiền định và lắng nghe âm thanh giúp bạn hiện diện hơn trong từng khoảnh khắc của cuộc sống.',
    ],
    [
        'benefit_title' => 'Xây dựng thực hành chăm sóc bản thân bền vững',
        'benefit_desc'  => 'Bộ công cụ thực tiễn để tự chăm sóc sức khỏe tâm – thân mỗi ngày, không phụ thuộc vào thời gian hay địa điểm.',
    ],
];

$cta_title   = get_field('cta_title',        $post_id) ?: 'Bắt đầu hành trình của bạn';
$cta_desc    = get_field('cta_desc',         $post_id) ?: 'Lớp học giới hạn số lượng học viên để đảm bảo chất lượng hướng dẫn tốt nhất. Vui lòng đăng ký sớm để giữ chỗ.';

$thumb       = get_the_post_thumbnail_url($post_id, 'full');
$thumb_alt   = get_the_title($post_id);

// Fallback images
$fallback_hero  = MONA_THEME_PATH_URI . '/assets/images/kh-hero.jpg';
$fallback_exp1  = MONA_THEME_PATH_URI . '/assets/images/kh-exp-1.jpg';
$fallback_exp2  = MONA_THEME_PATH_URI . '/assets/images/kh-exp-2.jpg';
$fallback_ins   = MONA_THEME_PATH_URI . '/assets/images/kh-instructor.jpg';
$ic_check       = MONA_THEME_PATH_URI . '/assets/images/ic-check-pri.svg';

get_header();
?>

<?php get_template_part('partials/components/breadcrumb', null, [
    'links' => [
        ['title' => 'Trang chủ', 'url' => home_url('/'),         'is-active' => false],
        ['title' => 'Khóa học',  'url' => home_url('/khoa-hoc'), 'is-active' => false],
        ['title' => get_the_title(), 'url' => '',                 'is-active' => true],
    ],
]); ?>

<div class="page-khoa-hoc" style="background:#fbf9f4;">

    <!-- ── HERO ──────────────────────────────────────────────────────── -->
    <section class="sec-kh-hero py-(--pd-sc)">
        <div class="container">
            <div class="row items-center">

                <!-- Left: Info -->
                <div class="col col-7 max-md:!w-full">
                    <div class="flex flex-col gap-6">

                        <!-- Badges -->
                        <?php if ($level || $duration) : ?>
                            <div class="flex gap-3 flex-wrap">
                                <?php if ($level) : ?>
                                    <span class="bg-[#eae8e3] text-pri text-[12px] font-semibold uppercase tracking-[1.2px] px-3 py-1 rounded-[2px]">
                                        <?php echo esc_html($level); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($duration) : ?>
                                    <span class="bg-[#eae8e3] text-pri text-[12px] font-semibold uppercase tracking-[1.2px] px-3 py-1 rounded-[2px]">
                                        <?php echo esc_html($duration); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Title -->
                        <h1 class="font-title text-pri text-[56px] max-md:text-[32px] leading-[64px] tracking-[-1.12px] font-normal">
                            <?php the_title(); ?>
                        </h1>

                        <!-- Short desc -->
                        <?php if ($short_desc) : ?>
                            <p class="text-[#414847] text-[18px] leading-[28px] max-w-[448px]">
                                <?php echo wp_kses_post(nl2br(esc_html($short_desc))); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Price + CTA -->
                        <div class="flex items-center gap-6 pt-4">
                            <?php if ($price) : ?>
                                <span class="font-title text-pri text-[24px] leading-[32px] font-normal">
                                    <?php echo esc_html($price); ?>
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
    <section class="sec-kh-exp py-(--pd-sc)" style="background:#f5f3ee; position:relative; overflow:hidden;">
        <div class="absolute size-[500px] bg-[rgba(206,229,218,0.2)] rounded-[12px] blur-[50px] top-[-250px] right-[-250px] pointer-events-none"></div>
        <div class="container">
            <div class="row items-center">

                <!-- Images grid -->
                <div class="col col-6 max-md:!w-full">
                    <div class="grid grid-cols-2 gap-4" style="height:300px;">
                        <div class="overflow-hidden rounded-[4px] h-full">
                            <img src="<?php echo esc_url($exp_img_1['url'] ?? $fallback_exp1); ?>"
                                class="block w-full h-full object-cover"
                                alt="<?php echo esc_attr($exp_img_1['alt'] ?? ''); ?>">
                        </div>
                        <div class="overflow-hidden rounded-[4px] h-full">
                            <img src="<?php echo esc_url($exp_img_2['url'] ?? $fallback_exp2); ?>"
                                class="block w-full h-full object-cover"
                                alt="<?php echo esc_attr($exp_img_2['alt'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Text -->
                <div class="col col-6 max-md:!w-full">
                    <div class="flex flex-col gap-6">
                        <h2 class="font-title text-pri text-[32px] max-md:text-[24px] leading-[40px] font-normal">
                            <?php echo esc_html($exp_title); ?>
                        </h2>
                        <?php if ($exp_desc) : ?>
                            <div class="text-[#414847] text-[16px] leading-[24px]">
                                <?php echo wp_kses_post(nl2br(esc_html($exp_desc))); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── ROADMAP + ASIDE ───────────────────────────────────────────── -->
    <section class="sec-kh-content py-(--pd-sc)">
        <div class="container">
            <div class="row">

                <!-- Left: Roadmap + Instructor + Benefits -->
                <div class="col col-7 max-md:!w-full">
                    <div class="flex flex-col max-md:gap-[40px] gap-[80px]">

                        <!-- Timeline -->
                        <?php if (!empty($rm_items)) : ?>
                            <div class="flex flex-col gap-4">
                                <p class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">
                                    <?php echo esc_html($rm_label); ?>
                                </p>
                                <h2 class="font-title text-pri text-[32px] max-md:text-[24px] leading-[40px] font-normal mb-10">
                                    <?php echo esc_html($rm_heading); ?>
                                </h2>

                                <div class="relative border-l border-[#e4e2dd] md:pl-16 pl-8 flex flex-col gap-16">
                                    <?php foreach ($rm_items as $i => $item) :
                                        $is_first = ($i === 0);
                                        $dot_bg   = $is_first ? '#133a35' : '#e4e2dd';
                                    ?>
                                        <div class="relative">
                                            <!-- Dot -->
                                            <div class="absolute max-md:-left-[40px] -left-[73px] top-[4px] size-4 rounded-full shadow-[0_0_0_4px_#fbf9f4]"
                                                style="background:<?php echo $dot_bg; ?>;"></div>

                                            <div class="flex flex-col gap-2">
                                                <h3 class="font-title text-pri text-[24px] leading-[32px] font-normal">
                                                    <?php echo esc_html($item['week_title']); ?>
                                                </h3>
                                                <?php if (!empty($item['week_desc'])) : ?>
                                                    <p class="text-[#414847] text-[16px] leading-[24px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['week_desc']))); ?>
                                                    </p>
                                                <?php endif; ?>
                                                <?php if (!empty($item['week_tags'])) :
                                                    $tags = array_map('trim', explode(',', $item['week_tags']));
                                                ?>
                                                    <div class="flex gap-2 flex-wrap pt-2">
                                                        <?php foreach ($tags as $tag) : ?>
                                                            <span class="bg-[#f0eee9] text-[#414847] text-[12px] tracking-[1.2px] px-3 py-1 rounded-[2px]">
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

                        <!-- Instructor -->
                        <?php if ($ins_name) : ?>
                            <div class="bg-[#f5f3ee] rounded-[4px] shadow-[0px_10px_20px_rgba(44,81,76,0.05)] max-md:p-4 p-8 flex flex-col gap-4">
                                <p class="text-[#4e635a] text-[12px] font-semibold uppercase tracking-[1.2px]">
                                    <?php echo esc_html($ins_label); ?>
                                </p>
                                <?php if ($ins_image) : ?>
                                    <div class="size-24 rounded-[12px] overflow-hidden shrink-0">
                                        <img src="<?php echo esc_url($ins_image['url']); ?>"
                                            class="block w-full h-full object-cover"
                                            alt="<?php echo esc_attr($ins_image['alt'] ?? $ins_name); ?>">
                                    </div>
                                <?php else : ?>
                                    <div class="size-24 rounded-[12px] overflow-hidden shrink-0">
                                        <img src="<?php echo esc_url($fallback_ins); ?>"
                                            class="block w-full h-full object-cover"
                                            alt="<?php echo esc_attr($ins_name); ?>">
                                    </div>
                                <?php endif; ?>
                                <h3 class="font-title text-pri text-[24px] leading-[32px] font-normal pt-4">
                                    <?php echo esc_html($ins_name); ?>
                                </h3>
                                <?php if ($ins_bio) : ?>
                                    <p class="text-[#414847] text-[16px] leading-[24px]">
                                        <?php echo wp_kses_post(nl2br(esc_html($ins_bio))); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Benefits -->
                        <?php if (!empty($bn_items)) : ?>
                            <div class="flex flex-col gap-8">
                                <h2 class="font-title text-pri text-[32px] max-md:text-[24px] leading-[40px] font-normal">
                                    <?php echo esc_html($bn_heading); ?>
                                </h2>
                                <div class="flex flex-col gap-6">
                                    <?php foreach ($bn_items as $item) : ?>
                                        <div class="flex gap-4 items-start">
                                            <div class="size-5 shrink-0 mt-[4px]">
                                                <img src="<?php echo esc_url($ic_check); ?>"
                                                    class="block w-full h-full object-contain" alt="">
                                            </div>
                                            <div>
                                                <h4 class="text-pri text-[18px] font-semibold leading-[28px]">
                                                    <?php echo esc_html($item['benefit_title']); ?>
                                                </h4>
                                                <?php if (!empty($item['benefit_desc'])) : ?>
                                                    <p class="text-[#414847] text-[16px] leading-[24px]">
                                                        <?php echo wp_kses_post(nl2br(esc_html($item['benefit_desc']))); ?>
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
                        class="bg-[#f5f3ee] border border-[rgba(192,200,198,0.3)] rounded-[4px] shadow-[0px_10px_20px_rgba(44,81,76,0.05)] max-md:p-4 p-8 flex flex-col gap-4 sticky top-[100px]">

                        <div class="flex flex-col gap-2 pb-6 border-b border-[rgba(192,200,198,0.4)]">
                            <h3 class="font-title text-pri text-[24px] max-md:text-[40px] mb-4 leading-[32px] font-normal">
                                Đăng ký tư vấn
                            </h3>
                            <p class="text-[#414847] text-[20px] leading-[24px] font-medium">
                                <?php echo esc_html(get_the_title()); ?>
                            </p>
                            <?php if ($price) : ?>
                                <span class="font-title text-pri text-[20px] leading-[28px] font-normal">
                                    <?php echo esc_html($price); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php
                        // Thay KH_CF7_FORM_ID bằng ID form trong WP Admin > Contact > Forms
                        // Cấu trúc form CF7 cần có các field:
                        //   [hidden course_id]  [hidden course_name]
                        //   [text* fullname]  [email* email]  [tel* phone]
                        $cf7_id = defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '';
                        if ($cf7_id) { ?>
                            <div class="cf7-khoa-hoc">
                                <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_id) . '"]'); ?>
                            </div>
                        <?php }
                        ?>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ── CTA ───────────────────────────────────────────────────────── -->
    <section class="sec-kh-cta py-(--pd-sc) relative overflow-hidden" style="background:#133a35;">
        <div class="absolute inset-0 opacity-10"
            style="background-image:radial-gradient(circle at center, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 70%);"></div>
        <div class="container relative">
            <div class="flex flex-col items-center text-center max-w-[768px] mx-auto gap-6">
                <h2 class="font-title text-white text-[56px] max-md:text-[8vw] leading-[64px] max-md:leading-[50px] tracking-[-1.12px] font-normal">
                    <?php echo esc_html($cta_title); ?>
                </h2>
                <p class="text-[rgba(255,255,255,0.8)] text-[18px] leading-[28px]">
                    <?php echo wp_kses_post(nl2br(esc_html($cta_desc))); ?>
                </p>
                <a href="#form-dang-ky" class="bg-[#fbf9f4] text-pri text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-4 rounded-[2px] shadow-[0px_10px_15px_-3px_rgba(0,0,0,0.1)] hover:bg-white transition-colors mt-4">
                    ĐĂNG KÝ KHOÁ HỌC
                </a>
            </div>
        </div>
    </section>

</div>

<?php get_footer(); ?>