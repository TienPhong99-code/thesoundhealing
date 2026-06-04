<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'label'  => 'Người Sáng Lập',
    'name'   => 'Elias Thorne',
    'quote'  => '"Âm thanh không chỉ đi qua tai; nó cộng hưởng qua từng tế bào. Nhiệm vụ của chúng ta không phải là thêm vào tiếng ồn của thế giới, mà là tạo ra những khoảng lặng nơi sự thật có thể lên tiếng."',
    'bio'    => 'Với hơn hai thập kỷ nghiên cứu về âm thanh học và y học phương Đông, Elias đã phát triển một phương pháp tiếp cận độc đáo kết hợp nghệ thuật chữa lành cổ xưa với thiết kế không gian hiện đại. Tầm nhìn của ông về Aetheria là tạo ra một nơi giao thoa giữa nghệ thuật, khoa học và tinh thần.',
    'image'  => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-founder-img.jpg', 'alt' => 'Elias Thorne'],
    'bg'     => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-visionary-bg.jpg', 'alt' => ''],
];

$raw_img = get_field('ab_visionary_image', $page_id);
$raw_bg  = get_field('ab_visionary_bg', $page_id);

$data = [
    'label' => get_field('ab_visionary_label', $page_id) ?: $sample['label'],
    'name'  => get_field('ab_visionary_name', $page_id)  ?: $sample['name'],
    'quote' => get_field('ab_visionary_quote', $page_id) ?: $sample['quote'],
    'bio'   => get_field('ab_visionary_bio', $page_id)   ?: $sample['bio'],
    'image' => $raw_img                                   ?: $sample['image'],
    'bg'    => $raw_bg                                    ?: $sample['bg'],
];
?>

<section class="sec-ab-visionary bg-pri relative overflow-hidden py-[120px]">
    <span class="absolute inset-0 opacity-20 pointer-events-none">
        <img src="<?php echo esc_url($data['bg']['url']); ?>"
            class="block w-full h-full object-cover"
            alt="">
    </span>

    <div class="container relative z-[1]">
        <div class="row items-center" style="--cg: 64px;">
            <div class="col col-5 max-md:!w-full">
                <div class="overflow-hidden rounded-[4px]">
                    <img src="<?php echo esc_url($data['image']['url']); ?>"
                        class="block w-full object-cover aspect-[3/4] grayscale mix-blend-luminosity opacity-90"
                        alt="<?php echo esc_attr($data['image']['alt']); ?>">
                </div>
            </div>

            <div class="col col-7 max-md:!w-full">
                <div class="flex flex-col gap-4 md:pl-12">
                    <p class="text-[#c2ebe4] text-[12px] font-semibold uppercase tracking-[1.2px]">
                        <?php echo esc_html($data['label']); ?>
                    </p>
                    <h2 class="font-title text-white text-[56px] font-normal tracking-[-1.12px] leading-[64px]">
                        <?php echo wp_kses_post($data['name']); ?>
                    </h2>
                    <blockquote class="font-title text-[rgba(194,235,228,0.9)] text-[24px] font-normal leading-[33px] pt-4">
                        <?php echo wp_kses_post($data['quote']); ?>
                    </blockquote>
                    <p class="text-[rgba(255,255,255,0.8)] text-[16px] leading-[24px] pt-4">
                        <?php echo wp_kses_post($data['bio']); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>