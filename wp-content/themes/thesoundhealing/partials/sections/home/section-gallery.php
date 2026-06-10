<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'HỌC VIÊN & TRẢI NGHIỆM',
    'heading' => 'Khoảnh Khắc Tại Aetheria',
    'desc'    => 'Lắng nghe những chia sẻ và cảm nhận từ các học viên đã trải qua hành trình chuyển hoá tâm thức cùng chúng tôi.',
    'items'   => [
        [
            'image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-1.jpg', 'alt' => 'Sound healing workshop'],
            'quote' => '"Một hành trình tìm lại chính mình tuyệt vời."',
            'name'  => '— MAI LAN',
        ],
        [
            'image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-2.jpg', 'alt' => 'Sound bath session'],
            'quote' => '',
            'name'  => '',
        ],
        [
            'image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-3.jpg', 'alt' => 'Himalayan singing bowl education'],
            'quote' => '',
            'name'  => '',
        ],
        [
            'image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/gallery-img-4.jpg', 'alt' => 'Radiant woman after reiki'],
            'quote' => '"Không gian chữa lành đích thực."',
            'name'  => '— HOÀNG NAM',
        ],
    ],
];

$raw_items = get_field('gallery_items', $page_id);

$data = [
    'label'   => get_field('gallery_label', $page_id)   ?: $sample['label'],
    'heading' => get_field('gallery_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('gallery_desc', $page_id)    ?: $sample['desc'],
    'items'   => $raw_items                             ?: $sample['items'],
];
?>

<section class="sec-gallery relative py-20">
    <div class="container">

        <div class="flex flex-col items-center gap-3 text-center max-w-[768px] mx-auto mb-16">
            <h2 class="font-title text-sec text-[32px] font-normal max-md:text-[24px]">
                <?php echo esc_html($data['heading']); ?>
            </h2>

            <p class="text-[#414847] text-[16px] leading-[24px]">
                <?php echo wp_kses_post($data['desc']); ?>
            </p>
        </div>

        <div class="swiper-gallery relative slideSw">
            <div class="swiper-container">
                <div class="swiper row overflow-hidden">
                    <div class="swiper-wrapper">
                        <?php foreach ($data['items'] as $item) :
                            $img_url = is_array($item['image']) ? ($item['image']['url'] ?? '') : ($item['image'] ?: '');
                            $img_alt = is_array($item['image']) ? ($item['image']['alt'] ?? '') : '';
                            $has_overlay = !empty($item['quote']);
                        ?>
                            <div class="swiper-slide col col-3 max-lg:!w-1/2 max-md:!w-3/4">
                                <div class="group relative overflow-hidden rounded-[2px] bg-[#f0eee9]">
                                    <div class="aspect-[3/4] w-full overflow-hidden">
                                        <img src="<?php echo esc_url($img_url); ?>"
                                            class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            alt="<?php echo esc_attr($img_alt); ?>">
                                    </div>

                                    <?php if ($has_overlay) : ?>
                                        <div class="absolute inset-0 bg-[rgba(19,58,53,0.2)] flex items-end p-6 max-md:p-2
                                                md:opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="backdrop-blur-[6px] bg-[rgba(251,249,244,0.9)] rounded-[2px] px-4 py-4 flex w-full flex-col gap-2">
                                                <p class="text-sec text-[14px] leading-[20px]">
                                                    <?php echo esc_html($item['quote']); ?>
                                                </p>
                                                <p class="text-pri text-[10px] uppercase tracking-[0.5px] leading-[24px]">
                                                    <?php echo esc_html($item['name']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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