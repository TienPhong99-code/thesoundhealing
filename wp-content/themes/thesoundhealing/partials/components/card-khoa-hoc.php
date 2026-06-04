<?php
defined('ABSPATH') || exit;

$item     = $args['item'] ?? [];
if (empty($item)) return;

$img_url  = $item['image']['url'] ?? '';
$img_alt  = $item['image']['alt'] ?? '';
$card_url = $item['url']          ?? '#';
?>

<div class="relative flex flex-col rounded-[12px] overflow-hidden group h-full">

    <!-- Thumbnail -->
    <div class="relative overflow-hidden bg-[#e4e2dd] aspect-[4/3] shrink-0">
        <?php if (!empty($img_url)) : ?>
            <img src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php endif; ?>

        <?php if (!empty($item['term'])) : ?>
            <div class="absolute top-4 left-4">
                <span class="bg-[rgba(251,249,244,0.9)] backdrop-blur-[2px] text-pri text-[10px] font-semibold uppercase tracking-[1.2px] px-2 py-1 rounded-[2px]">
                    <?php echo esc_html($item['term']); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 justify-between py-6 max-md:py-4">
        <div class="flex flex-col gap-3 mb-6">
            <h3 class="font-title text-pri text-[24px] font-normal leading-[32px] max-md:text-[18px]">
                <?php echo esc_html($item['title']); ?>
            </h3>
            <?php if (!empty($item['desc'])) : ?>
                <p class="text-[#414847] text-[16px] leading-[24px]">
                    <?php echo esc_html($item['desc']); ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="flex sm:items-end sm:justify-between gap-4 max-sm:flex-col">
            <div class="flex flex-col gap-1">
                <span class="text-[#717977] text-[12px] font-semibold uppercase tracking-[1.2px]">HỌC PHÍ</span>
                <?php if (!empty($item['price'])) : ?>
                    <span class="text-pri text-[16px] font-semibold leading-[24px]">
                        <?php echo esc_html($item['price']); ?>
                    </span>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url($card_url); ?>"
                class="btn btn-pri relative z-[2] shrink-0">
                XEM CHI TIẾT
            </a>
        </div>
    </div>

    <a href="<?php echo esc_url($card_url); ?>"
        class="absolute inset-0 z-[1]"
        aria-label="<?php echo esc_attr($item['title']); ?>"></a>
</div>