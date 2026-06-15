<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$sample = [
    'label'   => 'ĐỐI TÁC ĐỒNG HÀNH',
    'heading' => 'Những Người Bạn Đồng Hành',
    'desc'    => 'Chúng tôi hợp tác cùng các thương hiệu và tổ chức uy tín để mang đến những trải nghiệm chữa lành toàn diện nhất.',
    'items'   => [
        ['logo' => 'https://placehold.co/160x48/e8f5f0/133a35?text=ZenFlow',     'name' => 'ZenFlow',     'url' => '#'],
        ['logo' => 'https://placehold.co/160x48/e8f5f0/133a35?text=OrganicSoul', 'name' => 'OrganicSoul', 'url' => '#'],
        ['logo' => 'https://placehold.co/160x48/e8f5f0/133a35?text=LUMINA',      'name' => 'LUMINA',      'url' => '#'],
        ['logo' => 'https://placehold.co/160x48/e8f5f0/133a35?text=Ethereal',    'name' => 'Ethereal',    'url' => '#'],
        ['logo' => 'https://placehold.co/160x48/e8f5f0/133a35?text=PureState',   'name' => 'PureState',   'url' => '#'],
    ],
];

$data = [
    'label'   => get_field('partner_label',   $page_id) ?: $sample['label'],
    'heading' => get_field('partner_heading', $page_id) ?: $sample['heading'],
    'desc'    => get_field('partner_desc',    $page_id) ?: $sample['desc'],
    'items'   => $sample['items'],
];
?>

<section class="sec-partner bg-white py-20">
    <div class="container flex flex-col gap-[48px]">

        <div class="w-full flex flex-col items-center gap-3 text-center">
            <h2 class="font-title text-pri text-[32px] font-bold max-md:text-[24px]">
                <?php echo esc_html($data['heading']); ?>
            </h2>
            <?php if (!empty($data['desc'])) : ?>
                <p class="text-[#414847] text-[15px] max-w-[560px]">
                    <?php echo wp_kses_post($data['desc']); ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="swiper-partner slideSw relative opacity-50">
            <div class="absolute inset-0 bg-white mix-blend-saturation pointer-events-none z-10" aria-hidden="true"></div>
            <div class="swiper row">
                <div class="swiper-wrapper items-center">
                    <?php foreach ($data['items'] as $item) :
                        if (empty($item['logo'])) continue;
                        $tag  = !empty($item['url']) && $item['url'] !== '#' ? 'a' : 'div';
                        $href = !empty($item['url']) && $item['url'] !== '#' ? ' href="' . esc_url($item['url']) . '" target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                        <div class="swiper-slide col !w-[calc(100%/5)] max-lg:!w-[calc(100%/3)] max-md:!w-1/2">
                            <<?php echo $tag . $href; ?> class="flex items-center h-[40px] justify-center">
                                <img src="<?php echo esc_url($item['logo']); ?>"
                                    alt="<?php echo esc_attr($item['name']); ?>"
                                    class="h-full w-auto object-contain">
                            </<?php echo $tag; ?>>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</section>