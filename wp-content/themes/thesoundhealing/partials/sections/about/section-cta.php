<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading'       => 'Bắt Đầu Hành Trình Của Bạn',
    'desc'          => 'Dù bạn đang tìm kiếm sự tĩnh lặng sâu sắc hay muốn khám phá sức mạnh của âm thanh, không gian của chúng tôi luôn rộng mở chào đón bạn.',
    'btn_primary'   => ['text' => 'Liên Hệ', 'url' => home_url('/lien-he')],
    'btn_secondary' => ['text' => 'Xem Các Khóa Học',    'url' => '#'],
];

$data = [
    'heading'       => get_field('ab_cta_heading', $page_id) ?: $sample['heading'],
    'desc'          => get_field('ab_cta_desc', $page_id)    ?: $sample['desc'],
    'btn_primary'   => [
        'text' => get_field('ab_cta_btn_primary_text', $page_id)  ?: $sample['btn_primary']['text'],
        'url'  => get_field('ab_cta_btn_primary_url', $page_id)   ?: home_url('/lien-he'),
    ],
    'btn_secondary' => [
        'text' => get_field('ab_cta_btn_secondary_text', $page_id) ?: $sample['btn_secondary']['text'],
        'url'  => get_field('ab_cta_btn_secondary_url', $page_id)  ?: home_url('/khoa-hoc'),
    ],
];
?>

<section class="sec-ab-cta py-24">
    <div class="container">
        <div class="flex flex-col items-center text-center max-w-[672px] mx-auto gap-6">
            <h2 class="font-title text-pri max-md:text-[24px] text-[32px] font-normal leading-[40px]">
                <?php echo wp_kses_post($data['heading']); ?>
            </h2>
            <p class="text-[#414847] text-[16px] leading-[24px]">
                <?php echo wp_kses_post($data['desc']); ?>
            </p>

            <div class="flex md:items-center max-md:flex-col gap-4 pt-4">
                <a href="<?php echo esc_url($data['btn_primary']['url']); ?>"
                    class="inline-flex items-center justify-center bg-pri text-white text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-4 rounded-[2px]">
                    <?php echo esc_html($data['btn_primary']['text']); ?>
                </a>
                <a href="<?php echo esc_url($data['btn_secondary']['url']); ?>"
                    class="inline-flex items-center justify-center border border-[#c0c8c6] text-pri text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-4 rounded-[2px]">
                    <?php echo esc_html($data['btn_secondary']['text']); ?>
                </a>
            </div>
        </div>
    </div>
</section>