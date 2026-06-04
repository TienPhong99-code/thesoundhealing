<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Sống Trọn Vẹn',
    'desc'    => 'Những khoảnh khắc chân thực tại không gian của chúng tôi.',
    'images'  => [
        ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-1.jpg', 'alt' => 'Chi tiết không gian'],
        ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-2.jpg', 'alt' => 'Thưởng trà'],
        ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-3.jpg', 'alt' => 'Góc thiền'],
        ['url' => MONA_THEME_PATH_URI . '/assets/images/about-gallery-4.jpg', 'alt' => 'Vật dụng trị liệu'],
    ],
];

$raw_images = get_field('ab_gallery_images', $page_id);


$data = [
    'heading' => get_field('ab_gallery_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('ab_gallery_desc', $page_id)    ?: $sample['desc'],
    'images'  => $raw_images                                ?: $sample['images'],
];

$imgs = array_pad((array) $data['images'], 4, ['url' => '', 'alt' => '']);

$get_img = function ($item) {
    return [
        'url' => is_array($item) ? ($item['url'] ?? '') : '',
        'alt' => is_array($item) ? ($item['alt'] ?? '') : '',
    ];
};
?>

<section class="sec-ab-gallery py-[120px]">
    <div class="container">
        <div class="flex flex-col gap-16">

            <div class="flex flex-col items-center gap-4 text-center">
                <h2 class="font-title text-pri text-[32px] font-normal leading-[40px]">
                    <?php echo wp_kses_post($data['heading']); ?>
                </h2>
                <p class="text-[#414847] text-[16px] leading-[24px]">
                    <?php echo wp_kses_post($data['desc']); ?>
                </p>
            </div>

            <?php
            $i1 = $get_img($imgs[0]);
            $i2 = $get_img($imgs[1]);
            $i3 = $get_img($imgs[2]);
            $i4 = $get_img($imgs[3]);
            ?>
            <div class="grid grid-cols-4 grid-rows-2 gap-6" style="grid-template-rows: 300px 300px;">
                <div class="col-span-2 row-span-2 overflow-hidden rounded-[4px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)]">
                    <?php if ($i1['url']) : ?>
                        <img src="<?php echo esc_url($i1['url']); ?>"
                            class="block w-full h-full object-cover"
                            alt="<?php echo esc_attr($i1['alt']); ?>">
                    <?php endif; ?>
                </div>

                <div class="col-span-1 row-span-1 overflow-hidden rounded-[4px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)]">
                    <?php if ($i2['url']) : ?>
                        <img src="<?php echo esc_url($i2['url']); ?>"
                            class="block w-full h-full object-cover"
                            alt="<?php echo esc_attr($i2['alt']); ?>">
                    <?php endif; ?>
                </div>

                <div class="col-span-1 row-span-2 overflow-hidden rounded-[4px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)]">
                    <?php if ($i3['url']) : ?>
                        <img src="<?php echo esc_url($i3['url']); ?>"
                            class="block w-full h-full object-cover"
                            alt="<?php echo esc_attr($i3['alt']); ?>">
                    <?php endif; ?>
                </div>

                <div class="col-span-1 row-span-1 overflow-hidden rounded-[4px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.05)]">
                    <?php if ($i4['url']) : ?>
                        <img src="<?php echo esc_url($i4['url']); ?>"
                            class="block w-full h-full object-cover"
                            alt="<?php echo esc_attr($i4['alt']); ?>">
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>