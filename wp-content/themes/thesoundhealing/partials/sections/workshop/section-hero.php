<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Workshop & Sự Kiện',
    'desc'    => 'Tham gia các buổi trải nghiệm trực tiếp — nơi âm thanh và năng lượng hội tụ để tạo ra sự thay đổi sâu sắc.',
];

$data = [
    'heading' => get_field('ws_page_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('ws_page_desc', $page_id)    ?: $sample['desc'],
];
?>

<section class="sec-ws-hero py-(--pd-sc)">
    <div class="container">
        <div class="flex flex-col items-center text-center gap-6 max-w-[768px] mx-auto">
            <h1 class="font-title text-pri max-md:text-[40px] text-[56px] font-normal tracking-[-1.12px] leading-[64px]">
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