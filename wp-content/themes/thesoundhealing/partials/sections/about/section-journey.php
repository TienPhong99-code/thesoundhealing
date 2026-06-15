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

$eco_sample = [
    'heading_top'    => 'Hệ sinh thái',
    'heading_bottom' => 'HEALIVERSE HOLISTIC CENTRE',
    'items'          => [
        [
            'logo'  => ['url' => '', 'alt' => 'Healiverse'],
            'name'  => 'HEALIVERSE.VN',
            'url'   => 'https://healiverse.vn',
            'desc'  => 'Chuyên các <strong>sản phẩm mùi hương từ Khối thơm và Thảo mộc thanh tẩy, các sản phẩm Chuông xoay và nhạc cụ chữa lành</strong>, đang có mặt ở 30 cửa hàng, khách hàng từ hơn 30 quốc gia tin dùng.',
        ],
        [
            'logo'  => ['url' => '', 'alt' => 'Quantumleap'],
            'name'  => 'QUANTUMLEAP.VN',
            'url'   => 'https://quantumleap.vn',
            'desc'  => 'Khoá học trực tuyến, dạy nghề Healing thực chiến hiện đại. Nền tảng E-learning số hoá tự động, xây dựng hơn 200+ Videos bài học chất lượng cao. Có ngay 10 bài học miễn phí chỉ cần đăng nhập tài khoản Google là trải nghiệm ngay!',
        ],
        [
            'logo'  => ['url' => '', 'alt' => 'The Sound Healing'],
            'name'  => 'THESOUNDHEALING.VN',
            'url'   => 'https://thesoundhealing.vn',
            'desc'  => '<strong>Nền tảng booking dịch vụ trải nghiệm về Healing và chữa lành</strong> hiện đại như: Sound healing, sound bath, chuông xoay, Reiki. Các sự kiện và khoá học trải nghiệm truyền nghề thực chiến.',
        ],
    ],
];

$eco_heading_top    = get_field('ab_eco_heading_top', $page_id)    ?: $eco_sample['heading_top'];
$eco_heading_bottom = get_field('ab_eco_heading_bottom', $page_id) ?: $eco_sample['heading_bottom'];
$eco_raw_items      = get_field('ab_eco_items', $page_id);
$eco_items          = [];

if ($eco_raw_items) {
    foreach ($eco_raw_items as $item) {
        $eco_items[] = [
            'logo' => $item['logo'] ?: ['url' => '', 'alt' => ''],
            'name' => $item['name'] ?? '',
            'url'  => $item['url']  ?? '#',
            'desc' => $item['desc'] ?? '',
        ];
    }
} else {
    $eco_items = $eco_sample['items'];
}
?>

<section class="sec-ab-journey section-pd">
    <div class="container">
        <div class="relative">
            <div class="row items-center">

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
                    <div class="flex flex-col gap-1">
                        <h2 class="font-title text-pri text-[32px] font-bold  max-md:text-[24px]">
                            <?php echo wp_kses_post($data['heading']); ?>
                        </h2>

                        <div class="flex flex-col gap-2">
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
    </div>
</section>

<section class="sec-ab-ecosystem section-pd">
    <div class="container">
        <div class="text-center mb-10">
            <?php if ($eco_heading_top) : ?>
                <p class="font-title text-pri text-[28px] font-bold leading-[36px] tracking-wider max-md:text-[20px] max-md:leading-[28px]">
                    <?php echo esc_html($eco_heading_top); ?>
                </p>
            <?php endif; ?>
            <h2 class="font-title text-pri text-[28px] font-bold leading-[36px] tracking-wider max-md:text-[20px] max-md:leading-[28px]">
                <?php echo esc_html($eco_heading_bottom); ?>
            </h2>
        </div>
        <div class="row">
            <?php foreach ($eco_items as $item) : ?>
                <div class="col col-4 max-md:!w-full">
                    <a href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener noreferrer"
                        class="flex flex-col items-center gap-4 group">
                        <div class="w-full border border-[#e8e0d0] rounded-[8px] overflow-hidden flex items-center justify-center bg-[#fafaf8] aspect-[4/3]">
                            <?php if (!empty($item['logo']['url'])) : ?>
                                <img src="<?php echo esc_url($item['logo']['url']); ?>"
                                    alt="<?php echo esc_attr($item['logo']['alt']); ?>"
                                    class="max-w-[70%] max-h-[60%] object-contain">
                            <?php else : ?>
                                <span class="text-[#b0a890] text-[14px]">LOGO</span>
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-col gap-2 text-center">
                            <p class="font-bold text-[18px] text-[#1b1c19] tracking-wide group-hover:text-pri transition-colors">
                                <?php echo esc_html($item['name']); ?>
                            </p>
                            <p class="text-[#414847]">
                                <?php echo wp_kses_post($item['desc']); ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>