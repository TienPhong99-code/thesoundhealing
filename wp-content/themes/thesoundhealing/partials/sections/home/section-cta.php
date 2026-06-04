<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'heading'         => 'Bắt Đầu Hành Trình Chuyển Hoá Của Bạn',
    'desc'            => 'Hãy để Aetheria đồng hành cùng bạn trên con đường tìm lại sự cân bằng và đánh thức tiềm năng vô hạn bên trong.',
    'btn_primary'     => ['text' => 'XEM KHÓA HỌC', 'url' => home_url('/khoa-hoc')],
    'btn_secondary'   => ['text' => 'TƯ VẤN MIỄN PHÍ', 'url' => home_url('/lien-he')],
];

$data = [
    'heading'       => get_field('cta_heading', $page_id)          ?: $sample['heading'],
    'desc'          => get_field('cta_desc', $page_id)             ?: $sample['desc'],
    'btn_primary'   => [
        'text' => get_field('cta_btn_primary_text', $page_id)  ?: $sample['btn_primary']['text'],
        'url'  => get_field('cta_btn_primary_url', $page_id)   ?: home_url('/khoa-hoc'),
    ],
    'btn_secondary' => [
        'text' => get_field('cta_btn_secondary_text', $page_id) ?: $sample['btn_secondary']['text'],
        'url'  => get_field('cta_btn_secondary_url', $page_id)  ?: home_url('/lien-he'),
    ],
];
?>

<section class="sec-cta bg-[#f5f3ee] section-pd">
    <div class="container">
        <div class="flex flex-col items-center text-center">
            <h2 class="font-title text-pri text-[56px] max-md:text-[40px] max-sm:text-[8vw] font-normal tracking-[-1.12px] mb-6">
                <?php echo wp_kses_post($data['heading']); ?>
            </h2>

            <p class="text-[#414847] text-[18px] max-w-[672px] mb-10">
                <?php echo esc_html($data['desc']); ?>
            </p>

            <div class="flex gap-6 max-md:flex-col max-md:gap-4">
                <a href="<?php echo esc_url($data['btn_primary']['url']); ?>"
                    class="inline-flex items-center justify-center bg-pri text-white text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-[18px] rounded-[2px] drop-shadow-[0px_10px_20px_rgba(44,81,76,0.15)]">
                    <?php echo esc_html($data['btn_primary']['text']); ?>
                </a>

                <a href="<?php echo esc_url($data['btn_secondary']['url']); ?>"
                    class="inline-flex items-center justify-center border-2 border-pri text-pri text-[12px] font-semibold uppercase tracking-[1.2px] px-8 py-[18px] rounded-[2px]">
                    <?php echo esc_html($data['btn_secondary']['text']); ?>
                </a>
            </div>
        </div>
    </div>
</section>