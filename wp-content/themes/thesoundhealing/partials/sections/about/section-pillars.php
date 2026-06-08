<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Nền Tảng Cốt Lõi',
    'desc'    => 'Những nguyên tắc hướng dẫn mọi trải nghiệm và không gian tại Aetheria.',
    'pillars' => [
        [
            'number' => '01',
            'title'  => 'Sự Tối Giản Có Chủ Đích',
            'desc'   => 'Chúng tôi tin rằng sự trống rỗng là nơi tiềm năng bắt đầu. Bằng cách loại bỏ những yếu tố phân tâm, chúng tôi tạo ra không gian cho sự tĩnh lặng nội tâm trỗi dậy.',
            'image'  => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-pillar-1-img.jpg', 'alt' => 'Sự tối giản'],
        ],
        [
            'number' => '02',
            'title'  => 'Cộng Hưởng Chữa Lành',
            'desc'   => 'Mọi rung động đều có ngôn ngữ riêng. Chúng tôi sử dụng âm thanh học tinh tế—từ tần số Solfeggio đến bát hát Tây Tạng—để tái lập sự cân bằng hài hòa của cơ thể.',
            'image'  => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-pillar-2-img.jpg', 'alt' => 'Buổi trị liệu âm thanh'],
        ],
    ],
];

$raw_pillars = get_field('ab_pillars_items', $page_id);

$data = [
    'heading' => get_field('ab_pillars_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('ab_pillars_desc', $page_id)    ?: $sample['desc'],
    'pillars' => $raw_pillars                               ?: $sample['pillars'],
];

?>

<section class="sec-ab-pillars bg-[#f5f3ee] py-[120px]">
    <div class="container">
        <div class="flex flex-col gap-[80px]">

            <div class="flex flex-col items-center gap-4 text-center">
                <h2 class="font-title text-pri text-[32px] font-normal leading-[40px]">
                    <?php echo wp_kses_post($data['heading']); ?>
                </h2>
                <p class="text-[#414847] text-[16px] leading-[24px] max-w-[672px]">
                    <?php echo wp_kses_post($data['desc']); ?>
                </p>
            </div>

            <div class="grid grid-cols-12 gap-x-12 gap-y-12 max-md:grid-cols-1">

                <?php foreach ($data['pillars'] as $index => $pillar) :
                    $img_url = is_array($pillar['image']) ? ($pillar['image']['url'] ?? '') : ($pillar['image'] ?: '');
                    $img_alt = is_array($pillar['image']) ? ($pillar['image']['alt'] ?? '') : '';
                    $is_even = $index % 2 === 0;
                    $default_number = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                ?>

                    <?php if ($is_even) : ?>

                        <div class="col-span-5 overflow-hidden rounded-[4px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)]">
                            <img src="<?php echo esc_url($img_url); ?>"
                                class="block w-full h-full object-cover aspect-[5/6]"
                                alt="<?php echo esc_attr($img_alt); ?>">
                        </div>

                        <div class="col-span-7 flex flex-col gap-2 self-center">
                            <span class="text-[#4e635a] text-[12px] font-semibold tracking-[1.2px] uppercase"><?php echo esc_html($pillar['number'] ?? $default_number); ?></span>
                            <h3 class="text-pri text-[18px] font-normal leading-[32px] max-md:text-[18px]">
                                <?php echo wp_kses_post($pillar['title']); ?>
                                <?php echo wp_kses_post($pillar['title']); ?>
                            </h3>
                            <p class="text-[#414847] text-[16px] leading-[24px] pt-2">
                                <?php echo wp_kses_post($pillar['desc']); ?>
                            </p>
                        </div>

                    <?php else : ?>

                        <div class="col-span-7 flex flex-col gap-2 self-center">
                            <span class="text-[#4e635a] text-[12px] font-semibold tracking-[1.2px] uppercase"><?php echo esc_html($pillar['number'] ?? $default_number); ?></span>
                            <h3 class=" text-pri text-[24px] font-normal leading-[32px]">
                                <?php echo wp_kses_post($pillar['title']); ?>
                            </h3>
                            <p class="text-[#414847] text-[16px] leading-[24px] pt-2">
                                <?php echo wp_kses_post($pillar['desc']); ?>
                            </p>
                        </div>

                        <div class="col-span-5 overflow-hidden rounded-[4px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)] self-end pt-24">
                            <img src="<?php echo esc_url($img_url); ?>"
                                class="block w-full object-cover aspect-[4/3]"
                                alt="<?php echo esc_attr($img_alt); ?>">
                        </div>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>
        </div>
    </div>
</section>