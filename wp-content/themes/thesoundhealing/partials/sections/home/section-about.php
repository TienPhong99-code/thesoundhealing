<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'VỀ CHÚNG TÔI',
    'heading' => 'Trí tuệ cổ xưa cho cuộc sống hiện đại.',
    'desc'    => 'Tại Aetheria, chúng tôi tin rằng sự tĩnh lặng là nền tảng của mọi sự phát triển. Đội ngũ chuyên gia của chúng tôi kết hợp các phương pháp thực hành tâm linh truyền thống với cách tiếp cận tinh tế, tối giản để tạo ra một không gian chữa lành an toàn và chuyên nghiệp.',
    'image'   => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-img-1.png', 'alt' => ''],
    'items'   => [
        [
            'icon'  => MONA_THEME_PATH_URI . '/assets/images/ic-expert.svg',
            'title' => 'Chuyên Môn Sâu Sắc',
            'desc'  => 'Các giảng viên được đào tạo bài bản quốc tế.',
        ],
        [
            'icon'  => MONA_THEME_PATH_URI . '/assets/images/ic-therapy.svg',
            'title' => 'Liệu Pháp Độc Bản',
            'desc'  => 'Không gian thiết kế tối ưu cho tần số chữa lành.',
        ],
    ],
];

$raw_img   = get_field('about_image', $page_id);
$raw_items = get_field('about_items', $page_id);

$data = [
    'label'   => get_field('about_label', $page_id)   ?: $sample['label'],
    'heading' => get_field('about_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('about_desc', $page_id)    ?: $sample['desc'],
    'image'   => $raw_img                             ?: $sample['image'],
    'items'   => $raw_items                           ?: $sample['items'],
];
?>

<section class="sec-about relative py-(--pd-sc)">
    <div class="container">
        <div class="relative">
            <div class="row">
                <div class="max-md:!w-full col col-5 ">
                    <div class="relative overflow-hidden rounded-tl-[24px] rounded-tr-[80px] rounded-br-[24px] rounded-bl-[24px] aspect-4/5 shadow-[0px_10px_40px_rgba(44,81,76,0.05)]">
                        <img src="<?php echo esc_url($data['image']['url']); ?>"
                            class="block w-full h-full object-cover"
                            alt="<?php echo esc_attr($data['image']['alt']); ?>">
                    </div>
                </div>
                <div class="max-md:!w-full col col-7 ">
                    <div class="flex flex-col justify-center h-full">
                        <p class="text-pri text-[12px] font-semibold uppercase tracking-[1.2px] mb-4">
                            <?php echo esc_html($data['label']); ?>
                        </p>

                        <h2 class="font-title text-sec text-[32px] font-normal mb-6 max-sm:text-[24px]">
                            <?php echo wp_kses_post($data['heading']); ?>
                        </h2>

                        <p class="text-[#414847] text-[16px] mb-8">
                            <?php echo wp_kses_post($data['desc']); ?>
                        </p>

                        <div class="flex flex-col gap-4">
                            <?php foreach ($data['items'] as $item) :
                                $icon_url = is_array($item['icon']) ? ($item['icon']['url'] ?? '') : ($item['icon'] ?: '');
                            ?>
                                <div class="flex gap-3 items-start">
                                    <?php if ($icon_url) : ?>
                                        <div class="size-5 shrink-0 mt-[6px]">
                                            <img src="<?php echo esc_url($icon_url); ?>"
                                                class="block w-full h-full object-contain"
                                                alt="">
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h3 class="font-title text-sec text-[24px] max-md:text-[18px] font-normal">
                                            <?php echo esc_html($item['title']); ?>
                                        </h3>
                                        <p class="text-[#414847] text-[14px]">
                                            <?php echo esc_html($item['desc']); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>