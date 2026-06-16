<?php
defined('ABSPATH') || exit;

$item     = $args['item'] ?? [];
if (empty($item)) return;

$img_url     = $item['image']['url'] ?? '';
$img_alt     = $item['image']['alt'] ?? '';
$card_url    = $item['url']          ?? '#';
$format      = $item['format']       ?? 'Onsite';
$status_key  = $item['status']       ?? 'open';
$location    = $item['location']     ?? '';
$start_date  = $item['start_date']   ?? '';
$duration    = $item['duration']     ?? '';
$best_seller = $item['best_seller']  ?? false;
?>

<div class="relative flex flex-col overflow-hidden group h-full max-md:bg-white max-md:p-3 max-md:rounded-2xl max-md:shadow-[0_2px_12px_rgba(0,0,0,0.08)]">

    <!-- Thumbnail -->
    <div class="relative overflow-hidden rounded-2xl bg-[#e4e2dd] aspect-square shrink-0">
        <?php if (!empty($img_url)) : ?>
            <img src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php endif; ?>
        <?php if ($best_seller) : ?>
            <span class="absolute bottom-2 right-2 z-[2] flex items-center gap-1 text-[10px] font-semibold uppercase tracking-[0.8px] bg-[#c2a056] text-white px-2.5 py-1 rounded-full shadow-sm">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
                Best Seller
            </span>
        <?php endif; ?>
        <button type="button"
            class="absolute top-2 right-2 z-[2] w-9 h-9 flex items-center justify-center bg-white/85 rounded-full shadow-sm cursor-pointer hover:bg-white transition-colors"
            data-modal-open="share"
            data-share-url="<?php echo esc_url($card_url); ?>"
            data-share-title="<?php echo esc_attr($item['title']); ?>"
            aria-label="Chia sẻ">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="18" cy="5" r="3" />
                <circle cx="6" cy="12" r="3" />
                <circle cx="18" cy="19" r="3" />
                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
            </svg>
        </button>
    </div>

    <!-- Tags -->
    <div class="flex items-center gap-2 mt-3">
        <span class="text-[11px] font-medium uppercase tracking-[1px] text-[#414847]">
            Khóa học
        </span>
        <?php if (!empty($format)) : ?>
            <span class="ml-auto text-[11px] font-medium uppercase tracking-[1px] px-3 py-1 rounded-full border border-[#c0c8c6] text-[#414847]">
                <?php echo esc_html($format); ?>
            </span>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 justify-between mt-2.5 gap-1">

        <div class="flex flex-col gap-1.5">
            <h3 class="text-[18px] font-semibold line-clamp-2 leading-[1.4] max-md:text-[16px]">
                <?php echo esc_html($item['title']); ?>
            </h3>

            <?php if (!empty($start_date) || !empty($duration)) : ?>
                <p class="flex items-start gap-1.5 text-[#414847] text-[13px] leading-[18px]">
                    <svg class="shrink-0 mt-px" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    <span>
                        <?php if (!empty($start_date)) echo esc_html($start_date); ?>
                        <?php if (!empty($start_date) && !empty($duration)) echo ' · '; ?>
                        <?php if (!empty($duration)) echo esc_html($duration); ?>
                    </span>
                </p>
            <?php endif; ?>

            <?php if (!empty($location)) : ?>
                <p class="flex items-start gap-1.5 text-[#414847] text-[13px] leading-[18px]">
                    <svg class="shrink-0 mt-px" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span class="line-clamp-2"><?php echo esc_html($location); ?></span>
                </p>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-2">
            <?php if (!empty($item['price'])) : ?>
                <span class="text-[#1b1c19] text-[15px] font-semibold">
                    <?php echo esc_html($item['price']); ?><?php if (strtolower(trim($item['price'])) !== 'liên hệ') : ?> / khách<?php endif; ?>
                </span>
            <?php endif; ?>

            <?php if ($status_key !== 'closed') : ?>
                <a href="<?php echo esc_url($card_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    ĐẶT LỊCH
                </a>
            <?php endif; ?>

        </div>

    </div>

    <a href="<?php echo esc_url($card_url); ?>"
        class="absolute inset-0 z-[1]"
        aria-label="<?php echo esc_attr($item['title']); ?>"></a>
</div>