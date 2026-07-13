<?php
defined('ABSPATH') || exit;

$page_id = MONA_PAGE_HOME;

$partner_items = get_field('partner_items', $page_id);
$items = [];
if (is_array($partner_items)) {
    foreach ($partner_items as $row) {
        if (empty($row['logo'])) continue;
        $items[] = [
            'logo' => $row['logo'],
            'name' => $row['name'] ?? '',
            'url'  => $row['url']  ?? '',
        ];
    }
}

$data = [
    'label'   => get_field('partner_label',   $page_id),
    'heading' => get_field('partner_heading', $page_id),
    'desc'    => get_field('partner_desc',    $page_id),
    'items'   => $items,
];
?>

<section class="sec-partner pt-20 pb-30">
    <div class="container flex flex-col gap-[48px]">

        <div class="w-full flex flex-col items-center gap-3 text-center">
            <?php if (!empty($data['heading'])) : ?>
                <h2 class="font-title text-pri text-[32px] font-bold max-md:text-[24px]">
                    <?php echo esc_html($data['heading']); ?>
                </h2>
            <?php endif; ?>
            <!-- <?php if (!empty($data['desc'])) : ?>
                <p class="text-[#414847] text-[15px] max-w-[560px]">
                    <?php echo wp_kses_post($data['desc']); ?>
                </p>
            <?php endif; ?> -->
        </div>

        <?php if (!empty($data['items'])) : ?>
            <div class="swiper-partner slideSw w-[1000px] max-w-full mx-auto relative">
                <div class="swiper row">
                    <div class="swiper-wrapper  items-center">
                        <?php foreach ($data['items'] as $item) :
                            if (empty($item['logo'])) continue;
                            $tag  = !empty($item['url']) && $item['url'] !== '#' ? 'a' : 'div';
                            $href = !empty($item['url']) && $item['url'] !== '#' ? ' href="' . esc_url($item['url']) . '" target="_blank" rel="noopener noreferrer"' : '';
                        ?>
                            <div class="swiper-slide col !w-[calc(100%/3)] max-md:!w-1/2">
                                <<?php echo $tag . $href; ?> class="flex items-center h-[100px] justify-center">
                                    <img src="<?php echo esc_url($item['logo']); ?>"
                                        alt="<?php echo esc_attr($item['name']); ?>"
                                        class="h-full w-auto object-contain">
                                </<?php echo $tag; ?>>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>