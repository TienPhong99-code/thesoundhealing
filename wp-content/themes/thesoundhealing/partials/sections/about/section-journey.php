<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading'   => 'Hành Trình Của Chúng Tôi',
    'desc_1'    => 'Aetheria ra đời từ một nhận thức đơn giản: trong một thế giới không ngừng chuyển động, sự tĩnh lặng đã trở thành một thứ xa xỉ. Chúng tôi không chỉ tạo ra một không gian vật lý, mà là một nơi tôn trú cho tâm hồn. Hành trình của chúng tôi bắt đầu bằng việc khám phá các phương pháp chữa lành cổ xưa qua âm thanh và năng lượng, sau đó tinh chỉnh chúng qua lăng kính của sự tối giản hiện đại.',
    'desc_2'    => 'Mỗi chi tiết tại Aetheria, từ ánh sáng phản chiếu trên mặt sàn gỗ đến âm vang của những chiếc bát hát pha lê, đều được thiết kế tỉ mỉ để hướng bạn về với trạng thái cân bằng tự nhiên. Chúng tôi loại bỏ những yếu tố thừa thãi, để lại một không gian trong trẻo, nơi bạn có thể thực sự lắng nghe chính mình.',
    'link_text' => 'Khám Phá Triết Lý',
    'link_url'  => '#',
    'video_url' => '',
    'image'     => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-journey-img.jpg', 'alt' => 'Chiếc bát hát pha lê'],
];

$raw_img = get_field('ab_journey_image', $page_id);

$data = [
    'heading'   => get_field('ab_journey_heading', $page_id)   ?: $sample['heading'],
    'desc_1'    => get_field('ab_journey_desc_1', $page_id)    ?: $sample['desc_1'],
    'desc_2'    => get_field('ab_journey_desc_2', $page_id)    ?: $sample['desc_2'],
    'link_text' => get_field('ab_journey_link_text', $page_id) ?: $sample['link_text'],
    'link_url'  => get_field('ab_journey_link_url', $page_id)  ?: $sample['link_url'],
    'video_url' => get_field('ab_journey_video_url', $page_id) ?: $sample['video_url'],
    'image'     => $raw_img                                    ?: $sample['image'],
];

// Convert YouTube/Vimeo URL to embed URL
$embed_url = '';
if ($data['video_url']) {
    $embed = wp_oembed_get($data['video_url'], ['width' => 800]);
    if ($embed) {
        preg_match('/src="([^"]+)"/', $embed, $m);
        $embed_url = $m[1] ?? '';
    }
}
?>

<section class="sec-ab-journey section-pd">
    <div class="container">
        <div class="row items-center" style="--cg: 64px;">

            <div class="col col-6 max-md:!w-full">
                <?php if ($embed_url) : ?>
                    <div class="overflow-hidden rounded-[12px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.08)] aspect-video">
                        <iframe src="<?php echo esc_url($embed_url); ?>"
                            class="block w-full h-full"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    </div>
                <?php elseif ($data['image']) : ?>
                    <div class="overflow-hidden rounded-[12px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)] aspect-video">
                        <img src="<?php echo esc_url($data['image']['url']); ?>"
                            class="block w-full h-full object-cover"
                            alt="<?php echo esc_attr($data['image']['alt']); ?>">
                    </div>
                <?php endif; ?>
            </div>

            <div class="col col-6 max-md:!w-full">
                <div class="flex flex-col gap-6">
                    <h2 class="font-title text-pri text-[32px] font-normal leading-[40px] max-md:text-[24px] max-md:leading-[32px]">
                        <?php echo wp_kses_post($data['heading']); ?>
                    </h2>

                    <div class="flex flex-col gap-4">
                        <p class="text-[#414847] text-[16px] leading-[26px]">
                            <?php echo wp_kses_post($data['desc_1']); ?>
                        </p>
                        <p class="text-[#414847] text-[16px] leading-[26px]">
                            <?php echo wp_kses_post($data['desc_2']); ?>
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>