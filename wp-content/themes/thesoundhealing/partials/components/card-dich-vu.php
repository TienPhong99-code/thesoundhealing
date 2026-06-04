<?php
defined('ABSPATH') || exit;

$item = $args['item'] ?? [];
if (empty($item)) return;

$img_url  = $item['image']['url'] ?? '';
$img_alt  = $item['image']['alt'] ?? '';
$card_url = $item['url']          ?? '#';
$category = $item['category']     ?? '';
?>

<div class="relative flex flex-col group h-full">

    <!-- Thumbnail -->
    <div class="relative overflow-hidden bg-[#e4e2dd] aspect-video rounded-[4px] shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] shrink-0">
        <?php if (!empty($img_url)) : ?>
            <img src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 pt-4 gap-3 pb-8">

        <?php if (!empty($category)) : ?>
            <p class="text-[rgba(19,58,53,0.6)] text-[10px] font-normal uppercase tracking-[1px]">
                <?php echo esc_html($category); ?>
            </p>
        <?php endif; ?>

        <h3 class="font-title text-[#133a35] text-[24px] font-normal leading-[32px] max-md:text-[20px]">
            <?php echo esc_html($item['title']); ?>
        </h3>

        <?php if (!empty($item['desc'])) : ?>
            <p class="text-[#414847] text-[14px] leading-[20px] line-clamp-2">
                <?php echo esc_html($item['desc']); ?>
            </p>
        <?php endif; ?>

    </div>

    <!-- Link -->
    <div class="flex items-center gap-2">
        <span class="text-[#133a35] text-[11px] font-normal uppercase tracking-[1px]">TÌM HIỂU THÊM</span>
        <div class="size-[8px] shrink-0">
            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/ic-arrow-right.svg"
                class="block w-full h-full object-contain" alt="">
        </div>
    </div>

    <a href="<?php echo esc_url($card_url); ?>"
        class="absolute inset-0 z-[1]"
        aria-label="<?php echo esc_attr($item['title']); ?>"></a>
</div>