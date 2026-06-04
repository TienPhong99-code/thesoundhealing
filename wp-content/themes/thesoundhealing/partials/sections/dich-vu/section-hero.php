<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Dịch Vụ Trị Liệu',
    'desc'    => 'Khám phá các liệu pháp chữa lành cá nhân hóa, từ trị liệu âm thanh đến năng lượng, được thiết kế để mang lại sự an lạc tuyệt đối.',
];

$data = [
    'heading' => get_field('dv_page_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('dv_page_desc', $page_id)    ?: $sample['desc'],
];
?>

<section class="sec-dv-hero py-(--pd-sc) bg-[#fbf9f4]">
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