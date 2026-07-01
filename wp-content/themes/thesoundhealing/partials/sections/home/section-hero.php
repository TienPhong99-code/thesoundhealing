<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'heading'  => 'Đánh Thức Sự Hài Hòa Bên Trong',
    'desc'     => 'Trải nghiệm sự chuyển hóa tâm thức thông qua các liệu pháp âm thanh và năng lượng chuyên sâu, được thiết kế cho tâm hồn hiện đại.',
    'btn_text' => 'KHÁM PHÁ KHÓA HỌC',
    'btn_url'  => home_url('/khoa-hoc'),
    'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/banner.gif', 'alt' => ''],
];

$raw_img = get_field('hero_image', $page_id);

$data = [
    'heading'  => get_field('hero_heading', $page_id),
    'desc'     => get_field('hero_desc', $page_id),
    'btn_text' => get_field('hero_btn_text', $page_id) ?: $sample['btn_text'],
    'btn_url'  => get_field('hero_btn_url', $page_id)  ?: $sample['btn_url'],
    'image'    => $raw_img ?: $sample['image'],
];
?>

<section class="sec-hero relative z-10 section-pd mb-10 max-md:mb-5 ">
    <div class="container ">
        <div class="relative max-md:!pt-10">
            <!-- 2-col row: content | image -->
            <div class="grid grid-cols-2 gap-10 items-center max-md:grid-cols-1 max-md:gap-8">

                <!-- Col 1: Content -->
                <div class="flex flex-col items-start max-md:items-center max-md:text-center">
                    <?php if (!empty($data['heading'])) : ?>
                        <h1 class="font-second text-pri text-[54px] font-bold mb-2 max-sm:text-[8vw] max-md:text-[40px] leading-tight">
                            <?php echo wp_kses_post(preg_replace('/^<p>(.*)<\/p>$/s', '$1', trim($data['heading']))); ?>
                        </h1>
                    <?php endif; ?>
                    <?php if (!empty($data['desc'])) : ?>
                        <div class="text-[20px]">
                            <?php echo wp_kses_post($data['desc']); ?>
                        </div>
                    <?php endif; ?>
                    <!-- <a href="<?php echo esc_url($data['btn_url']); ?>"
                        class="btn btn-pri">
                        <?php echo esc_html($data['btn_text']); ?>
                    </a> -->
                </div>
                <!-- Search Booking -->
                <div class="absolute max-md:fixed max-md:top-[calc(var(--size-hd))] max-md:w-full max-md:left-0 md:left-1/2 md:-translate-x-1/2 md:bottom-0 md:translate-y-1/2 w-full ">
                    <?php get_template_part('partials/components/search-booking'); ?>
                </div>
                <!-- Col 2: Image -->
                <div class="flex items-center justify-center ">
                    <img src="<?php echo esc_url($data['image']['url']); ?>"
                        class="block w-full max-w-[560px] h-auto object-contain rounded-2xl overflow-hidden"
                        alt="<?php echo esc_attr($data['image']['alt']); ?>">
                </div>

            </div>


        </div>
    </div>
</section>