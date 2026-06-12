<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'NGƯỜI DẪN DẮT',
    'heading' => 'Đội Ngũ Chuyên Gia',
    'desc'    => 'Đội ngũ chuyên gia giàu kinh nghiệm, được đào tạo bài bản, đồng hành cùng bạn trên hành trình chữa lành và phát triển bản thân.',
    'items'   => [
        [
            'image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/teams-img-1.png', 'alt' => 'Elena Vu'],
            'name'  => 'Elena Vu',
            'role'  => 'MASTER SOUND HEALER',
            'desc'  => 'Hơn 10 năm kinh nghiệm trong việc điều hướng tần số âm thanh để cân bằng luân xa và giải tỏa căng thẳng.',
        ],
        [
            'image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/teams-img-2.png', 'alt' => 'Minh Pham'],
            'name'  => 'Minh Pham',
            'role'  => 'REIKI MASTER & MEDITATION GUIDE',
            'desc'  => 'Chuyên gia về năng lượng và thiền định, giúp học viên kết nối sâu hơn với bản thể nội tại qua các khóa học Reiki.',
        ],
        [
            'image' => ['url' => MONA_THEME_PATH_URI . '/assets/images/teams-img-3.png', 'alt' => 'Sophia Le'],
            'name'  => 'Sophia Le',
            'role'  => 'HOLISTIC WELLNESS CONSULTANT',
            'desc'  => 'Tư vấn lối sống toàn diện, kết hợp dinh dưỡng tự nhiên và tâm lý học hành vi cho sự phát triển bền vững.',
        ],
    ],
];

$raw_items = get_field('teams_items', $page_id);

$data = [
    'label'   => get_field('teams_label', $page_id)   ?: $sample['label'],
    'heading' => get_field('teams_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('teams_desc', $page_id)    ?: $sample['desc'],
    'items'   => $raw_items                           ?: $sample['items'],
];
?>

<section class="sec-teams relative py-(--pd-sc)">
    <span class="absolute inset-0 z-[-1]"></span>

    <div class="container">
        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-12 max-md:mb-8">
            <h2 class="font-title text-sec text-[32px] font-normal max-sm:text-[24px] mb-3">
                <?php echo esc_html($data['heading']); ?>
            </h2>
            <?php if (!empty($data['desc'])) : ?>
                <p class="text-[#414847] text-[15px] max-w-[560px]">
                    <?php echo wp_kses_post($data['desc']); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Experts Slider -->
        <div class="swiper-teams relative slideSw">
            <button class="swiper-prev absolute top-1/2 -translate-y-1/2 -left-5 max-xl:hidden z-10 flex items-center justify-center w-10 h-10 rounded-full border border-[#c2a056] text-[#c2a056] hover:bg-[#c2a056] hover:text-white transition-colors duration-300 disabled:opacity-30">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <button class="swiper-next absolute top-1/2 -translate-y-1/2 -right-5 max-xl:hidden z-10 flex items-center justify-center w-10 h-10 rounded-full border border-[#c2a056] text-[#c2a056] hover:bg-[#c2a056] hover:text-white transition-colors duration-300 disabled:opacity-30">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6" />
                </svg>
            </button>
            <div class="swiper-container">
                <div class="swiper row">
                    <div class="swiper-wrapper">
                        <?php foreach ($data['items'] as $item) :
                            $img_url = is_array($item['image']) ? ($item['image']['url'] ?? '') : ($item['image'] ?: '');
                            $img_alt = is_array($item['image']) ? ($item['image']['alt'] ?? '') : '';
                        ?>
                            <div class="swiper-slide col col-4 max-lg:!w-1/2 max-md:!w-3/4">
                                <div class="group relative overflow-hidden rounded-[4px] aspect-[3/4]">
                                    <img src="<?php echo esc_url($img_url); ?>"
                                        class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        alt="<?php echo esc_attr($img_alt ?: $item['name']); ?>">

                                    <div class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-[rgba(0,0,0,0.85)] to-transparent pointer-events-none"></div>

                                    <div class="absolute inset-x-0 bottom-0 p-6 max-md:p-4">
                                        <?php if (!empty($item['desc'])) : ?>
                                            <p class="text-white/80 text-[16px] leading-[20px] mb-3 max-h-0 overflow-hidden opacity-0 group-hover:max-h-[80px] group-hover:opacity-100 transition-all duration-400">
                                                <?php echo esc_html($item['desc']); ?>
                                            </p>
                                        <?php endif; ?>
                                        <h3 class="font-title text-white text-[22px] font-normal leading-snug max-md:text-[18px]">
                                            <?php echo esc_html($item['name']); ?>
                                        </h3>
                                        <p class="text-[#c2a056] text-[10px] font-semibold uppercase tracking-[1.2px] mt-1">
                                            <?php echo esc_html($item['role']); ?>
                                        </p>
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