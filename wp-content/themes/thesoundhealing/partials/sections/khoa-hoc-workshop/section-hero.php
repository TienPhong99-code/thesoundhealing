<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Khóa Học & Workshop',
    'desc'    => 'Khám phá các khóa học chuyên sâu và tham gia các buổi workshop trải nghiệm trực tiếp — hành trình chuyển hóa qua âm thanh và năng lượng.',
];

$data = [
    'heading' => get_field('kh_ws_page_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('kh_ws_page_desc', $page_id)    ?: $sample['desc'],
];
?>

<section class="sec-khws-hero pt-(--pd-sc) pb-12 max-md:pb-8">
    <div class="container">
        <div class="flex flex-col items-center text-center gap-5 max-w-[760px] mx-auto">
            <h1 class="font-title text-pri text-[56px] max-md:text-[36px] font-normal tracking-[-1.12px] leading-[64px] max-md:leading-[44px]">
                <?php echo wp_kses_post($data['heading']); ?>
            </h1>
            <?php if (!empty($data['desc'])) : ?>
                <p class="text-[#414847] text-[18px] max-md:text-[15px] leading-[28px] max-md:leading-[24px]">
                    <?php echo wp_kses_post($data['desc']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>