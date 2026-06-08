<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'heading'  => 'Đánh Thức Sự Hài Hòa Bên Trong',
    'desc'     => 'Trải nghiệm sự chuyển hóa tâm thức thông qua các liệu pháp âm thanh và năng lượng chuyên sâu, được thiết kế cho tâm hồn hiện đại.',
    'btn_text' => 'KHÁM PHÁ KHÓA HỌC',
    'btn_url'  => home_url('/khoa-hoc'),
    'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/hero-img-1.png', 'alt' => ''],
];

$raw_img = get_field('hero_image', $page_id);

$data = [
    'heading'  => get_field('hero_heading', $page_id)  ?: $sample['heading'],
    'desc'     => get_field('hero_desc', $page_id)     ?: $sample['desc'],
    'btn_text' => get_field('hero_btn_text', $page_id) ?: $sample['btn_text'],
    'btn_url'  => get_field('hero_btn_url', $page_id)  ?: $sample['btn_url'],
    'image'    => $raw_img                              ?: $sample['image'],
];
?>

<section class="sec-hero relative overflow-hidden min-h-[819px]">
    <span class="absolute inset-0 z-[-1] opacity-80 mix-blend-multiply">
        <div class="w-full h-full">
            <img src="<?php echo esc_url($data['image']['url']); ?>"
                class="block w-full h-full object-cover"
                alt="<?php echo esc_attr($data['image']['alt']); ?>">
        </div>
    </span>

    <div class="container relative flex flex-col items-center justify-center min-h-[819px] py-20">
        <div class="flex flex-col items-center text-center max-w-[1000px]">
            <h1 class="font-second text-[#133a35] text-[56px] font-normal mb-6 max-sm:text-[8vw] max-md:text-[40px]">
                <?php echo wp_kses_post($data['heading']); ?>
            </h1>
            <p class="text-[#414847] text-[18px] mb-10 max-w-[672px]">
                <?php echo esc_html($data['desc']); ?>
            </p>
            <a href="<?php echo esc_url($data['btn_url']); ?>"
                class="btn btn-pri">
                <?php echo esc_html($data['btn_text']); ?>
            </a>
        </div>
    </div>
</section>