<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading'  => "Trải Nghiệm Chữa Lành\nToàn Diện",
    'image'    => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-pillar-1-img.jpg', 'alt' => 'Sound Healing'],
    'items'    => [
        [
            'title' => 'Phòng Âm Thanh Chuyên Biệt',
            'desc'  => 'Không gian tối giản được thiết kế riêng để tối ưu hóa âm học, mang lại trải nghiệm âm thanh thuần khiết và sâu sắc nhất.',
        ],
        [
            'title' => 'Chuyên Gia Được Chứng Nhận Quốc Tế',
            'desc'  => 'Đội ngũ giảng viên có chứng chỉ quốc tế về âm thanh trị liệu, thiền định và y học tích hợp với nhiều năm kinh nghiệm thực hành.',
        ],
        [
            'title' => 'Công Cụ Âm Thanh Chất Lượng Cao',
            'desc'  => 'Bát hát Tây Tạng, âm thoa trị liệu và nhạc cụ truyền thống được tuyển chọn kỹ lưỡng từ các nghệ nhân uy tín trên thế giới.',
        ],
        [
            'title' => 'Chương Trình Cá Nhân Hóa',
            'desc'  => 'Mỗi học viên nhận được lộ trình học tập được thiết kế riêng dựa trên nhu cầu, mục tiêu và tình trạng sức khỏe cá nhân.',
        ],
        [
            'title' => 'Cộng Đồng Hỗ Trợ Lâu Dài',
            'desc'  => 'Hành trình chữa lành không kết thúc sau buổi học. Học viên tham gia cộng đồng và được hỗ trợ liên tục qua các buổi chia sẻ định kỳ.',
        ],
    ],
];

$raw_image = get_field('ab_features_image', $page_id);
$raw_items = get_field('ab_features_items', $page_id);

$data = [
    'heading' => get_field('ab_features_heading', $page_id) ?: $sample['heading'],
    'image'   => $raw_image ?: $sample['image'],
    'items'   => $raw_items ?: $sample['items'],
];

$heading_lines = array_filter(array_map('trim', explode("\n", $data['heading'])));
?>

<section class="sec-ab-features section-pd bg-white">
    <div class="container">
        <div class="row items-start" style="--cg: 64px;">

            <!-- Left: Heading + Image -->
            <div class="col col-5 max-md:!w-full md:sticky top-24">
                <div class="flex flex-col gap-8">
                    <h2 class="font-title text-pri text-[44px] font-normal leading-[54px] max-md:text-[28px] max-md:leading-[36px]">
                        <?php foreach ($heading_lines as $i => $line) : ?>
                            <?php if ($i === 0) : ?>
                                <strong class="font-semibold"><?php echo wp_kses_post($line); ?></strong><br>
                            <?php else : ?>
                                <?php echo wp_kses_post($line); ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </h2>

                    <?php if ($data['image']) : ?>
                        <div class="overflow-hidden rounded-[20px] shadow-[0px_10px_40px_0px_rgba(44,81,76,0.10)]">
                            <img src="<?php echo esc_url($data['image']['url']); ?>"
                                class="block w-full object-cover aspect-[4/3]"
                                alt="<?php echo esc_attr($data['image']['alt']); ?>">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Feature items list -->
            <div class="col col-7 max-md:!w-full">
                <div class="flex flex-col divide-y divide-[#e8e6e1]">
                    <?php foreach ($data['items'] as $item) : ?>
                        <div class="flex gap-5 py-8 group">

                            <!-- Icon -->
                            <div class="shrink-0 w-10 h-10 rounded-full bg-[#f0efea] flex items-center justify-center mt-[2px]">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 2C5.13 2 2 5.13 2 9s3.13 7 7 7 7-3.13 7-7-3.13-7-7-7zm-1 10.5L4.5 9l1.06-1.06L8 10.38l4.44-4.44L13.5 7l-5.5 5.5z" fill="#133a35" fill-opacity="0.7" />
                                </svg>
                            </div>

                            <!-- Content -->
                            <div class="flex flex-col gap-2 flex-1">
                                <h3 class="font-title text-pri text-[20px] font-normal leading-[28px] max-md:text-[17px]">
                                    <?php echo wp_kses_post($item['title']); ?>
                                </h3>
                                <?php if (!empty($item['desc'])) : ?>
                                    <p class="text-[#414847] text-[15px] leading-[24px]">
                                        <?php echo wp_kses_post($item['desc']); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</section>