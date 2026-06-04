<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading'   => 'Hành Trình Của Chúng Tôi',
    'desc_1'    => 'Aetheria ra đời từ một nhận thức đơn giản: trong một thế giới không ngừng chuyển động, sự tĩnh lặng đã trở thành một thứ xa xỉ. Chúng tôi không chỉ tạo ra một không gian vật lý, mà là một nơi tôn trú cho tâm hồn. Hành trình của chúng tôi bắt đầu bằng việc khám phá các phương pháp chữa lành cổ xưa qua âm thanh và năng lượng, sau đó tinh chỉnh chúng qua lăng kính của sự tối giản hiện đại.',
    'desc_2'    => 'Mỗi chi tiết tại Aetheria, từ ánh sáng phản chiếu trên mặt sàn gỗ đến âm vang của những chiếc bát hát pha lê, đều được thiết kế tỉ mỉ để hướng bạn về với trạng thái cân bằng tự nhiên. Chúng tôi loại bỏ những yếu tố thừa thãi, để lại một không gian trong trẻo, nơi bạn có thể thực sự lắng nghe chính mình.',
    'link_text' => 'Khám Phá Triết Lý',
    'link_url'  => '#',
    'image'     => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-journey-img.jpg', 'alt' => 'Chiếc bát hát pha lê'],
];

$raw_img = get_field('ab_journey_image', $page_id);

$data = [
    'heading'   => get_field('ab_journey_heading', $page_id)   ?: $sample['heading'],
    'desc_1'    => get_field('ab_journey_desc_1', $page_id)    ?: $sample['desc_1'],
    'desc_2'    => get_field('ab_journey_desc_2', $page_id)    ?: $sample['desc_2'],
    'link_text' => get_field('ab_journey_link_text', $page_id) ?: $sample['link_text'],
    'link_url'  => get_field('ab_journey_link_url', $page_id)  ?: $sample['link_url'],
    'image'     => $raw_img                                     ?: $sample['image'],
];
?>

<section class="sec-ab-journey py-[120px]">
    <div class="container">
        <div class="row items-center" style="--cg: 96px;">
            <div class="col col-6 max-md:!w-full">
                <div class="flex flex-col gap-6">
                    <h2 class="font-title text-pri text-[32px] font-normal leading-[40px]">
                        <?php echo wp_kses_post($data['heading']); ?>
                    </h2>

                    <div class="flex flex-col gap-4 pt-2">
                        <p class="text-[#414847] text-[16px] leading-[26px]">
                            <?php echo wp_kses_post($data['desc_1']); ?>
                        </p>
                        <p class="text-[#414847] text-[16px] leading-[26px]">
                            <?php echo wp_kses_post($data['desc_2']); ?>
                        </p>
                    </div>

                    <?php if ($data['link_url'] && $data['link_text']) : ?>
                        <a href="<?php echo esc_url($data['link_url']); ?>"
                            class="inline-flex items-center gap-2 pt-2 text-pri text-[12px] font-semibold uppercase tracking-[1.2px] group">
                            <span><?php echo esc_html($data['link_text']); ?></span>
                            <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0 transition-transform group-hover:translate-x-1">
                                <path d="M1 4.5H8M8 4.5L5 1.5M8 4.5L5 7.5" stroke="#133a35" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col col-6 max-md:!w-full">
                <div class="relative">
                    <span class="absolute bg-[#f5f3ee] rounded-[12px] size-32 bottom-[-40px] left-[-40px] mix-blend-multiply z-0"></span>
                    <div class="relative z-[1] overflow-hidden rounded-[4px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)]">
                        <img src="<?php echo esc_url($data['image']['url']); ?>"
                            class="block w-full object-cover aspect-[3/4]"
                            alt="<?php echo esc_attr($data['image']['alt']); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>