<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Khóa Học Của Chúng Tôi',
    'desc'    => 'Khám phá các khóa học về âm thanh trị liệu, năng lượng và thực hành tâm thức — được thiết kế để dẫn dắt bạn trên hành trình chuyển hóa sâu sắc.',
];

$data = [
    'heading' => get_field('kh_page_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('kh_page_desc', $page_id)    ?: $sample['desc'],
];
?>

<section class="sec-kh-hero py-(--pd-sc) bg-[#fbf9f4]">
    <div class="container">
        <div class="flex flex-col items-center text-center gap-6 max-w-[768px] mx-auto">
            <h1 class="font-title max-md:text-[40px] text-pri text-[56px] font-normal tracking-[-1.12px] leading-[64px]">
                <?php echo wp_kses_post($data['heading']); ?>
            </h1>
            <?php if (!empty($data['desc'])) : ?>
                <p class="text-[#414847] text-[18px] leading-[28px]">
                    <?php echo wp_kses_post($data['desc']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>