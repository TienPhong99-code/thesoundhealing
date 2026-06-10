<?php
defined('ABSPATH') || exit;

$item     = $args['item'] ?? [];
if (empty($item)) return;

$img_url  = $item['image']['url'] ?? '';
$img_alt  = $item['image']['alt'] ?? '';
$card_url = $item['url']          ?? '#';
?>

<div class="relative flex flex-col overflow-hidden group h-full">

    <!-- Thumbnail -->
    <div class="relative overflow-hidden rounded-2xl bg-[#e4e2dd] aspect-square shrink-0">
        <?php if (!empty($img_url)) : ?>
            <img src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php endif; ?>

        <?php /* level badge ẩn tạm
        <?php if (!empty($item['level'])) : ?>
            <span class="absolute top-3 right-3 bg-[#eae8e3] text-pri text-[10px] font-semibold uppercase tracking-[1.2px] px-2 py-1 rounded-[2px]">
                <?php echo esc_html($item['level']); ?>
            </span>
        <?php endif; ?>
        */ ?>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 justify-between py-4 gap-3">

        <div class="flex flex-col gap-2">

            <span class="text-[12px] uppercase tracking-[1.2px]">
                Khóa học
            </span>

            <h3 class="text-[18px] font-normal line-clamp-2 leading-[28px] max-md:text-[16px]">
                <?php echo esc_html($item['title']); ?>
            </h3>

            <div class="flex flex-col gap-1">
                <?php if (!empty($item['start_date'])) : ?>
                    <p class="text-[#414847] text-[13px] leading-[18px]">
                        Khai giảng: <strong class="font-semibold"><?php echo esc_html($item['start_date']); ?></strong>
                    </p>
                <?php endif; ?>
                <?php if (!empty($item['duration'])) : ?>
                    <p class="text-[14px] leading-[18px]">
                        <?php echo esc_html($item['duration']); ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($item['instructor'])) : ?>
                    <p class="text-[14px] leading-[18px]">
                        <?php echo esc_html($item['instructor']); ?>
                    </p>
                <?php endif; ?>
            </div>

        </div>

        <div class="flex items-center justify-between gap-2 pt-2 border-t border-[rgba(192,200,198,0.3)]">
            <?php if (!empty($item['price'])) : ?>
                <span class="font-title text-pri text-[18px] leading-[24px] font-normal">
                    <?php echo esc_html($item['price']); ?>
                </span>
            <?php endif; ?>
            <!-- <span class="text-pri text-[11px] font-semibold uppercase tracking-[1.2px] ml-auto flex items-center gap-1">
                Tìm hiểu
                <svg class="w-[8px] h-[8px] shrink-0" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 4h6M4 1l3 3-3 3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span> -->
        </div>

    </div>

    <a href="<?php echo esc_url($card_url); ?>"
        class="absolute inset-0 z-[1]"
        aria-label="<?php echo esc_attr($item['title']); ?>"></a>
</div>