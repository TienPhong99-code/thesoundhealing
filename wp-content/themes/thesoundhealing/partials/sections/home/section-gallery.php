<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'HỌC VIÊN & TRẢI NGHIỆM',
    'heading' => 'Khoảnh Khắc Tại Aetheria',
    'desc'    => 'Lắng nghe những chia sẻ và cảm nhận từ các học viên đã trải qua hành trình chuyển hoá tâm thức cùng chúng tôi.',
    'items'   => [
        ['image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-1.jpg', 'alt' => 'Sound healing workshop']],
        ['image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-2.jpg', 'alt' => 'Sound bath session']],
        ['image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-3.jpg', 'alt' => 'Himalayan singing bowl education']],
        ['image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-4.jpg', 'alt' => 'Radiant woman after reiki']],
    ],
];

$raw_items = get_field('gallery_items', $page_id);

$data = [
    'label'   => get_field('gallery_label', $page_id),
    'heading' => get_field('gallery_heading', $page_id),
    'desc'    => get_field('gallery_desc', $page_id),
    'items'   => $raw_items ?: $sample['items'],
];
?>

<section class="sec-gallery relative py-20">
    <div class="container">

        <div class="flex flex-col items-center gap-3 text-center max-w-[768px] mx-auto mb-8">
            <?php if (!empty($data['heading'])) : ?>
                <h2 class="font-title text-pri text-[32px] font-bold max-md:text-[24px]">
                    <?php echo esc_html($data['heading']); ?>
                </h2>
            <?php endif; ?>

            <!-- <p class="text-[#414847] text-[16px] leading-[24px]">
                <?php echo wp_kses_post($data['desc']); ?>
            </p> -->
        </div>

        <div class="swiper-gallery relative slideSw">
            <!-- <div class="flex justify-center gap-4 mb-4">
                <button class="swiper-prev w-[36px] h-[36px] bg-pri/90 rounded-full flex items-center justify-center shadow-sm cursor-pointer hover:bg-pri transition-colors">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </button>
                <button class="swiper-next w-[36px] h-[36px] bg-pri/90 rounded-full flex items-center justify-center shadow-sm cursor-pointer hover:bg-pri transition-colors">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </button>
            </div> -->
            <div class="swiper-container">
                <div class="swiper row overflow-hidden">
                    <div class="swiper-wrapper">
                        <?php foreach ($data['items'] as $item) :
                            $img_url = is_array($item['image']) ? ($item['image']['url'] ?? '') : ($item['image'] ?: '');
                            $img_alt = is_array($item['image']) ? ($item['image']['alt'] ?? '') : '';
                        ?>
                            <div class="swiper-slide col col-3 max-lg:!w-1/2 max-md:!w-3/4">
                                <div class="group relative overflow-hidden rounded-[2px] bg-[#f0eee9]">
                                    <div class="aspect-[3/4] w-full overflow-hidden">
                                        <img src="<?php echo esc_url($img_url); ?>"
                                            class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            alt="<?php echo esc_attr($img_alt); ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination mt-6"></div>
        </div>

    </div>
</section>