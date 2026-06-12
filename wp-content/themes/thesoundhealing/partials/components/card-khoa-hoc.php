<?php
defined('ABSPATH') || exit;

$item     = $args['item'] ?? [];
if (empty($item)) return;

$img_url    = $item['image']['url'] ?? '';
$img_alt    = $item['image']['alt'] ?? '';
$card_url   = $item['url']          ?? '#';
$format     = $item['format']       ?? 'Onsite';
$status_key = $item['status']       ?? 'open';
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

    <!-- Tags -->
    <div class="flex items-center gap-2 mt-3">
        <span class="text-[11px] font-medium uppercase tracking-[1px] px-3 py-1 rounded-full border border-[#c0c8c6] text-[#414847]">
            Khóa học
        </span>
        <?php if (!empty($format)) : ?>
            <span class="text-[11px] font-medium uppercase tracking-[1px] px-3 py-1 rounded-full bg-[#eae8e3] text-[#414847]">
                <?php echo esc_html($format); ?>
            </span>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 justify-between mt-2.5 gap-3">

        <div class="flex flex-col gap-1.5">
            <h3 class="text-[18px] font-normal line-clamp-2 leading-[1.4] max-md:text-[16px]">
                <?php echo esc_html($item['title']); ?>
            </h3>

            <?php if (!empty($item['time']) || !empty($item['start_date'])) : ?>
                <p class="text-[#414847] text-[13px] leading-[18px]">
                    <?php if (!empty($item['time'])) echo esc_html($item['time']); ?>
                    <?php if (!empty($item['time']) && !empty($item['start_date'])) echo ' · '; ?>
                    <?php if (!empty($item['start_date'])) echo esc_html($item['start_date']); ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-2 pt-3 border-t border-[rgba(192,200,198,0.3)]">
            <?php if (!empty($item['price'])) : ?>
                <span class="text-[#1b1c19] text-[15px] font-semibold">
                    <?php echo esc_html($item['price']); ?> / khách
                </span>
            <?php endif; ?>

            <?php if ($status_key !== 'closed') : ?>
                <a href="<?php echo esc_url($card_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    Đăng ký
                </a>
            <?php endif; ?>

        </div>

    </div>

    <a href="<?php echo esc_url($card_url); ?>"
        class="absolute inset-0 z-[1]"
        aria-label="<?php echo esc_attr($item['title']); ?>"></a>
</div>