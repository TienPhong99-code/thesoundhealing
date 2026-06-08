<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'NGƯỜI DẪN DẮT',
    'heading' => 'Đội Ngũ Chuyên Gia',
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
    'items'   => $raw_items                           ?: $sample['items'],
];
?>

<section class="sec-teams relative py-(--pd-sc)">
    <span class="absolute inset-0 z-[-1]"></span>

    <div class="container">
        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-12 max-md:mb-8">
            <p class="text-pri text-[12px] font-semibold uppercase tracking-[1.2px] mb-4">
                <?php echo esc_html($data['label']); ?>
            </p>
            <h2 class="font-title text-sec text-[32px] font-normal max-sm:text-[24px]">
                <?php echo esc_html($data['heading']); ?>
            </h2>
        </div>

        <!-- Experts Slider -->
        <div class="swiper-teams relative slideSw">
            <div class="swiper-container">
                <div class="swiper row">
                    <div class="swiper-wrapper">
                        <?php foreach ($data['items'] as $item) :
                            $img_url = is_array($item['image']) ? ($item['image']['url'] ?? '') : ($item['image'] ?: '');
                            $img_alt = is_array($item['image']) ? ($item['image']['alt'] ?? '') : '';
                        ?>
                            <div class="swiper-slide col col-4 max-lg:!w-1/2 max-md:!w-3/4">
                                <div class="flex flex-col items-center text-center">
                                    <!-- Photo -->
                                    <div class="w-[192px] h-[192px] rounded-[12px] border border-[#e4e2dd] overflow-hidden mb-6 shrink-0">
                                        <?php if ($img_url) : ?>
                                            <img src="<?php echo esc_url($img_url); ?>"
                                                class="block w-full h-full object-cover"
                                                alt="<?php echo esc_attr($img_alt ?: $item['name']); ?>">
                                        <?php endif; ?>
                                    </div>

                                    <!-- Name -->
                                    <h3 class="font-title text-sec text-[24px] font-normal mb-2 max-md:text-[20px]">
                                        <?php echo esc_html($item['name']); ?>
                                    </h3>

                                    <!-- Role -->
                                    <p class="text-pri text-[11px] font-semibold uppercase tracking-[1.2px] mb-4">
                                        <?php echo esc_html($item['role']); ?>
                                    </p>

                                    <!-- Description -->
                                    <p class="text-[#414847] text-[14px] leading-[1.6]">
                                        <?php echo esc_html($item['desc']); ?>
                                    </p>
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