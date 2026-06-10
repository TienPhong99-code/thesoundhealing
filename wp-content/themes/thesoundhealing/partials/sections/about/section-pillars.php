<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'feat' => [
        'badge'      => 'Ưu điểm nổi bật',
        'heading'    => 'Trải Nghiệm Chữa Lành Toàn Diện',
        'desc'       => 'Chúng tôi kết hợp âm thanh học cổ đại với không gian thiết kế tối giản để mang lại trạng thái cân bằng sâu sắc cho cơ thể và tâm trí.',
        'list'       => "Phòng âm thanh được thiết kế chuyên biệt\nÂm thoa và bát hát Tây Tạng chất lượng cao\nHướng dẫn bởi chuyên gia có chứng chỉ quốc tế\nLịch học linh hoạt, phù hợp mọi lịch trình",
        'image'      => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-pillar-1-img.jpg', 'alt' => 'Sound Healing'],
        'img_badge'  => 'Hơn 500 học viên',
        'link_text'  => 'Khám phá các khóa học',
        'link_url'   => '#',
    ],
    'cards' => [
        [
            'title'      => 'Sự Tối Giản Có Chủ Đích',
            'desc'       => 'Loại bỏ những yếu tố phân tâm, tạo không gian cho sự tĩnh lặng nội tâm trỗi dậy.',
            'list_items' => "Thiết kế không gian tối giản\nÂm thanh môi trường được kiểm soát\nÁnh sáng tự nhiên tối ưu",
        ],
        [
            'title'      => 'Cộng Hưởng Chữa Lành',
            'desc'       => 'Sử dụng âm thanh học tinh tế để tái lập sự cân bằng hài hòa của cơ thể.',
            'list_items' => "Tần số Solfeggio\nBát hát Tây Tạng\nÂm thoa trị liệu",
        ],
        [
            'title'      => 'Thực Hành Có Hướng Dẫn',
            'desc'       => 'Mỗi buổi học được dẫn dắt bởi chuyên gia giàu kinh nghiệm trong lĩnh vực âm thanh trị liệu.',
            'list_items' => "Lớp học nhỏ, chú ý cá nhân\nTài liệu thực hành tại nhà\nHỗ trợ sau khóa học",
        ]
    ],
];

$raw_feat_img = get_field('ab_pillars_feat_image', $page_id);
$raw_cards    = get_field('ab_pillars_items', $page_id);

$feat = [
    'badge'     => get_field('ab_pillars_feat_badge', $page_id)      ?: $sample['feat']['badge'],
    'heading'   => get_field('ab_pillars_feat_heading', $page_id)    ?: $sample['feat']['heading'],
    'desc'      => get_field('ab_pillars_feat_desc', $page_id)       ?: $sample['feat']['desc'],
    'list'      => get_field('ab_pillars_feat_list', $page_id)       ?: $sample['feat']['list'],
    'image'     => $raw_feat_img                                      ?: $sample['feat']['image'],
    'img_badge' => get_field('ab_pillars_feat_img_badge', $page_id)  ?: $sample['feat']['img_badge'],
    'link_text' => get_field('ab_pillars_feat_link_text', $page_id)  ?: $sample['feat']['link_text'],
    'link_url'  => get_field('ab_pillars_feat_link_url', $page_id)   ?: $sample['feat']['link_url'],
];

$cards = $raw_cards ?: $sample['cards'];

$feat_list_items = array_filter(array_map('trim', explode("\n", $feat['list'])));
?>

<section class="sec-ab-pillars bg-[#f5f3ee] section-pd">
    <div class="container">
        <div class="flex flex-col gap-20 max-md:gap-12">

            <!-- Featured block -->
            <div class="row items-center" style="--cg: 64px;">

                <div class="col col-5 max-md:!w-full">
                    <div class="relative overflow-hidden rounded-[12px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.08)]">
                        <?php if ($feat['image']) : ?>
                            <img src="<?php echo esc_url($feat['image']['url']); ?>"
                                class="block w-full object-cover aspect-[4/5] max-md:aspect-video"
                                alt="<?php echo esc_attr($feat['image']['alt']); ?>">
                        <?php endif; ?>
                        <?php if ($feat['img_badge']) : ?>
                            <div class="absolute bottom-4 left-4 inline-flex items-center gap-2 bg-white rounded-full px-4 py-2 shadow-sm">
                                <span class="text-pri text-[16px] font-semibold leading-none"><?php echo esc_html($feat['img_badge']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col col-7 max-md:!w-full flex flex-col gap-5">
                    <?php if ($feat['badge']) : ?>
                        <div class="inline-flex items-center gap-2 self-start bg-pri text-white text-[11px] font-semibold uppercase tracking-[1px] px-4 py-2 rounded-full">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="7" cy="7" r="6" stroke="currentColor" stroke-width="1.2" />
                                <path d="M4.5 7L6.5 9L9.5 5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <?php echo esc_html($feat['badge']); ?>
                        </div>
                    <?php endif; ?>

                    <h2 class="font-title text-pri text-[40px] font-normal leading-[48px] max-md:text-[24px] max-md:leading-[32px]">
                        <?php echo wp_kses_post($feat['heading']); ?>
                    </h2>

                    <p class="text-[#414847] text-[16px] leading-[26px]">
                        <?php echo wp_kses_post($feat['desc']); ?>
                    </p>

                    <?php if ($feat_list_items) : ?>
                        <div class="bg-white rounded-[8px] p-6">
                            <ul class="flex flex-col gap-3">
                                <?php foreach ($feat_list_items as $item) : ?>
                                    <li class="flex items-start gap-3 text-[#414847] text-[16px] leading-[22px]">
                                        <svg class="shrink-0 mt-[2px]" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="8" cy="8" r="7" fill="#133a35" fill-opacity="0.1" />
                                            <path d="M5 8L7 10L11 6" stroke="#133a35" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span><?php echo esc_html($item); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- 3-column cards -->
            <?php if ($cards) : ?>
                <div class="row" style="--cg: 32px;">
                    <?php foreach ($cards as $card) :
                        $raw_list = $card['list_items'] ?? '';
                        $list     = array_filter(array_map('trim', explode("\n", $raw_list)));
                    ?>
                        <div class="col col-4 max-md:!w-full">
                            <div class="flex flex-col gap-4 bg-white rounded-[12px] p-6 h-full shadow-[0px_4px_20px_0px_rgba(44,81,76,0.05)]">
                                <h3 class="font-title text-pri text-[22px] font-normal leading-[30px] max-md:text-[18px]">
                                    <?php echo wp_kses_post($card['title']); ?>
                                </h3>
                                <?php if (!empty($card['desc'])) : ?>
                                    <p class="text-[#414847] text-[16px] leading-[22px]">
                                        <?php echo wp_kses_post($card['desc']); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if ($list) : ?>
                                    <ul class="flex flex-col gap-2 pt-1">
                                        <?php foreach ($list as $item) : ?>
                                            <li class="flex items-start gap-2 text-[#414847] text-[16px] leading-[20px]">
                                                <svg class="shrink-0 mt-[2px]" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 7L5.5 9.5L11 4" stroke="#133a35" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <span><?php echo esc_html($item); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>