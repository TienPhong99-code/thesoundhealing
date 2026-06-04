<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Kết Nối Cùng Aetheria',
    'desc'    => 'Mỗi hành trình chữa lành đều bắt đầu từ một nhịp thở chậm lại. Hãy để lại lời nhắn, chúng tôi sẽ lắng nghe và đồng hành cùng bạn trên con đường tìm lại sự cân bằng.',
];

$data = [
    'heading' => get_field('lh_hero_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('lh_hero_desc', $page_id)    ?: $sample['desc'],
];
?>

<section class="sec-lh-hero section-pd">
    <div class="container">
        <div class="flex flex-col items-center gap-6 text-center max-w-[896px] mx-auto">
            <h1 class="font-title text-sec text-[56px] max-md:text-[36px] font-normal tracking-[-1.12px] leading-[64px]">
                <?php echo wp_kses_post($data['heading']); ?>
            </h1>
            <p class="text-[#414847] text-[18px] leading-[28px] max-w-[672px]">
                <?php echo wp_kses_post($data['desc']); ?>
            </p>
        </div>
    </div>
</section>