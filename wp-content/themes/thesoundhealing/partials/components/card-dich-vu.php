<?php
defined('ABSPATH') || exit;

$item = $args['item'] ?? [];
if (empty($item)) return;

$img_url  = $item['image']['url'] ?? '';
$img_alt  = $item['image']['alt'] ?? '';
$card_url = $item['url']          ?? '#';
$category       = $item['category']        ?? '';
$available_days = $item['available_days'] ?? '';
$duration       = $item['duration']       ?? '';
$branch         = $item['branch']         ?? '';
$price          = $item['price']          ?? '';
?>

<div class="relative flex flex-col overflow-hidden group h-full">

    <!-- Thumbnail -->
    <div class="relative overflow-hidden rounded-2xl bg-[#e4e2dd] aspect-square shrink-0">
        <?php if (!empty($img_url)) : ?>
            <img src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 justify-between py-4 gap-3">

        <div class="flex flex-col gap-2">

            <?php /* category tag ẩn tạm
            <?php if (!empty($category)) : ?>
                <span class="text-[rgba(19,58,53,0.5)] text-[11px] font-semibold uppercase tracking-[1.2px]">
                    <?php echo esc_html($category); ?>
                </span>
            <?php endif; ?>
            */ ?>

            <h3 class="text-pri text-[18px] font-normal leading-[28px] line-clamp-2 max-md:text-[16px]">
                <?php echo esc_html($item['title']); ?>
            </h3>

            <div class="flex flex-col gap-1">
                <?php if (!empty($available_days)) : ?>
                    <p class="text-[#414847] text-[13px] leading-[18px]">
                        <?php echo esc_html($available_days); ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($duration)) : ?>
                    <p class="text-[rgba(19,58,53,0.45)] text-[12px] leading-[18px]">
                        <?php echo esc_html($duration); ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($branch)) : ?>
                    <p class="text-[rgba(19,58,53,0.45)] text-[12px] leading-[18px]">
                        <?php echo esc_html($branch); ?>
                    </p>
                <?php endif; ?>
            </div>

        </div>

        <div class="flex items-center justify-between gap-2 pt-2 border-t border-[rgba(192,200,198,0.3)]">
            <?php if (!empty($price)) : ?>
                <span class="font-title text-pri text-[18px] leading-[24px] font-normal">
                    <?php echo esc_html($price); ?>
                </span>
            <?php endif; ?>
            <span class="text-pri text-[11px] font-semibold uppercase tracking-[1.2px] ml-auto flex items-center gap-1">
                Đặt lịch
                <svg class="w-[8px] h-[8px] shrink-0" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 4h6M4 1l3 3-3 3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>

    </div>

    <a href="<?php echo esc_url($card_url); ?>"
        class="absolute inset-0 z-[1]"
        aria-label="<?php echo esc_attr($item['title']); ?>"></a>
</div>