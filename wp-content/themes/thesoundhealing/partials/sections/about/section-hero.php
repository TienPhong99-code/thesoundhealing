<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Bản Chất Của Sự Tĩnh Lặng',
    'desc'    => 'Nơi âm thanh hòa quyện cùng không gian, tạo nên một hành trình chữa lành sâu sắc từ bên trong. Chúng tôi tin vào sức mạnh của sự im lặng có chủ đích.',
    'image'   => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-hero-bg.jpg', 'alt' => ''],
];

$raw_img = get_field('ab_hero_image', $page_id);

$data = [
    'heading' => get_field('ab_hero_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('ab_hero_desc', $page_id)    ?: $sample['desc'],
    'image'   => $raw_img                               ?: $sample['image'],
];
?>

<section class="sec-ab-hero relative overflow-hidden min-h-[819px] flex items-center justify-center">
    <span class="absolute inset-0 z-0 opacity-90">
        <img src="<?php echo esc_url($data['image']['url']); ?>"
            class="block w-full h-full object-cover"
            alt="<?php echo esc_attr($data['image']['alt']); ?>">
    </span>
    <span class="absolute inset-0 z-[1] bg-gradient-to-t from-[#fbf9f4] via-[rgba(251,249,244,0.2)] to-[rgba(251,249,244,0)] via-50%"></span>

    <div class="container relative z-[2]">
        <div class="flex flex-col items-center text-center gap-6 max-w-[768px] mx-auto py-[120px]">
            <h1 class="font-title text-pri text-[56px] font-normal tracking-[-1.12px] leading-[64px] max-md:text-[40px]">
                <?php echo wp_kses_post($data['heading']); ?>
            </h1>
            <p class="text-[#414847] text-[18px] leading-[28px] max-w-[672px]">
                <?php echo wp_kses_post($data['desc']); ?>
            </p>
        </div>
    </div>
</section>