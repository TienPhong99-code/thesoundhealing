<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Bạn Cần Hỗ Trợ Nhanh?',
    'desc'    => 'Xem qua các câu hỏi thường gặp của chúng tôi hoặc ghé thăm studio để trải nghiệm không gian tĩnh tại.',
    'items'   => [
        ['faq_question' => 'Sound healing có phù hợp với người mới bắt đầu không?', 'faq_answer' => 'Hoàn toàn phù hợp. Các buổi học được thiết kế để đón nhận mọi cấp độ, từ người chưa từng trải nghiệm đến những ai đã có nền tảng thiền định.'],
        ['faq_question' => 'Tôi cần chuẩn bị gì trước buổi học?', 'faq_answer' => 'Bạn chỉ cần mang theo bản thân thoải mái. Trang phục rộng rãi và tâm thế cởi mở là tất cả những gì cần thiết.'],
        ['faq_question' => 'Mỗi buổi học kéo dài bao lâu?', 'faq_answer' => 'Các buổi học thường kéo dài từ 60 đến 90 phút, bao gồm thời gian khởi động, trải nghiệm âm thanh và nghỉ ngơi sau buổi học.'],
    ],
];

$data = [
    'heading' => get_field('lh_faq_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('lh_faq_desc', $page_id)    ?: $sample['desc'],
    'items'   => [],
];

if (have_rows('lh_faq_items', $page_id)) {
    while (have_rows('lh_faq_items', $page_id)) {
        the_row();
        $data['items'][] = [
            'faq_question' => get_sub_field('faq_question'),
            'faq_answer'   => get_sub_field('faq_answer'),
        ];
    }
} else {
    $data['items'] = $sample['items'];
}
?>

<section class="sec-lh-faq section-pd">
    <div class="container max-w-[1280px]">
        <div class="flex flex-col items-center gap-6 text-center max-w-[672px] mx-auto mb-12">

            <!-- Decorative icon -->
            <div class="w-[27px] h-6">
                <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/ic-contact-faq.svg'); ?>"
                    class="block w-full h-full object-contain" alt="">
            </div>

            <h2 class="font-title text-sec text-[32px] font-normal leading-[40px]">
                <?php echo wp_kses_post($data['heading']); ?>
            </h2>

            <p class="text-[#414847] text-[16px] leading-[24px]">
                <?php echo wp_kses_post($data['desc']); ?>
            </p>

        </div>

        <?php if (!empty($data['items'])) : ?>
            <div class="faq-list max-w-[800px] mx-auto divide-y divide-[#e4e2dd]">
                <?php foreach ($data['items'] as $index => $item) : ?>
                    <details class="faq-item group" <?php echo $index === 0 ? 'open' : ''; ?>>
                        <summary class="flex items-center justify-between gap-4 py-5 cursor-pointer list-none">
                            <span class="font-title text-sec text-[18px] font-normal leading-[28px] text-left">
                                <?php echo wp_kses_post($item['faq_question']); ?>
                            </span>
                            <span class="faq-icon shrink-0 w-5 h-5 flex items-center justify-center border border-[#c8c5be] rounded-full transition-transform duration-300 group-open:rotate-45">
                                <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 1V9M1 5H9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                                </svg>
                            </span>
                        </summary>
                        <div class="faq-body pb-5 text-[#414847] text-[15px] leading-[24px]">
                            <?php echo wp_kses_post($item['faq_answer']); ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>