<?php
defined('ABSPATH') || exit;

$page_id = get_queried_object_id();

$sample = [
    'heading' => 'Bản Chất Của Sự Tĩnh Lặng',
    'desc'    => 'Nơi âm thanh hòa quyện cùng không gian, tạo nên một hành trình chữa lành sâu sắc từ bên trong. Chúng tôi tin vào sức mạnh của sự im lặng có chủ đích.',
    'image'   => ['url' => MONA_THEME_PATH_URI . '/assets/images/about-hero-bg.jpg', 'alt' => ''],
    'note'    => '',
];

$raw_img = get_field('ab_hero_image', $page_id);

$data = [
    'heading' => get_field('ab_hero_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('ab_hero_desc', $page_id)    ?: $sample['desc'],
    'image'   => $raw_img                               ?: $sample['image'],
    'note'    => get_field('ab_hero_note', $page_id)    ?: $sample['note'],
];
?>

<section class="sec-ab-hero bg-[#fbf9f4] py-20 max-md:py-12">
    <div class="container">
        <div class="ralative">
            <div class="row">

                <div class="col col-7 max-md:!w-full flex flex-col gap-6">
                    <h1 class="font-title text-pri text-[56px] font-bold max-lg:text-[44px] max-md:text-[24px]">
                        <?php echo wp_kses_post($data['heading']); ?>
                    </h1>
                    <p class="text-[#414847] text-[18px] leading-[28px] max-w-[540px] max-md:text-[16px] max-md:leading-[26px]">
                        <?php echo wp_kses_post($data['desc']); ?>
                    </p>

                    <?php if ($data['note']) : ?>
                        <p class="text-[#6b7280] text-[16px] leading-[24px]">
                            <?php echo wp_kses_post($data['note']); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ($data['image']) : ?>
                    <div class="col col-5 max-md:!w-full">
                        <img src="<?php echo esc_url($data['image']['url']); ?>"
                            class="block w-full h-auto rounded-2xl object-cover"
                            alt="<?php echo esc_attr($data['image']['alt']); ?>">
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>