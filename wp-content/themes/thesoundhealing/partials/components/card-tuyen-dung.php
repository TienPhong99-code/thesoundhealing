<?php
defined('ABSPATH') || exit;

$job = $args ?? [];
if (empty($job)) return;
?>

<div class="relative bg-white p-4 flex flex-col gap-3 rounded-xl h-full item-td">
    <a href="<?php echo esc_url($job['url']); ?>" class="absolute inset-0 z-0 rounded-xl" aria-hidden="true"></a>

    <?php if (!empty($job['status'])) :
        $is_open = $job['status'] === 'open'; ?>
        <span class="inline-block self-start px-3 py-1 rounded-sm text-[14px] font-bold text-white leading-normal <?php echo $is_open ? 'bg-[#f14950]' : 'bg-[#808080]'; ?>">
            <?php echo $is_open ? esc_html__('ĐANG TUYỂN', 'monamedia') : esc_html__('ĐÃ TUYỂN', 'monamedia'); ?>
        </span>
    <?php endif; ?>

    <h3 class="text-[16px] font-bold text-pri uppercase leading-normal tracking-[-0.04em] line-clamp-3">
        <?php echo esc_html($job['title']); ?>
    </h3>

    <div class="flex flex-col gap-2">
        <div class="flex items-start gap-2">
            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-pin.svg"
                class="w-4 h-4 mt-0.5 shrink-0" alt="">
            <span class="text-[14px] text-pri leading-normal tracking-[-0.04em]">
                <?php echo esc_html($job['location']); ?>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-coin.svg"
                class="w-4 h-4 shrink-0" alt="">
            <span class="text-[14px] text-pri leading-normal tracking-[-0.04em]">
                <?php echo esc_html($job['salary']); ?>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-time.svg"
                class="w-4 h-4 shrink-0" alt="">
            <span class="text-[14px] text-pri leading-normal tracking-[-0.04em]">
                <?php echo esc_html($job['type']); ?>
            </span>
        </div>
    </div>

    <hr class="border-t border-[#d9d9d9] m-0">

    <div class="flex items-center justify-between gap-2 mt-auto flex-wrap">
        <span class="text-[14px] text-[#818181] leading-normal">
            <?php echo esc_html($job['posted']); ?>
        </span>
        <a href="<?php echo esc_url($job['url']); ?>"
            class="relative z-10 flex items-center gap-2 text-[16px] font-bold text-pri hover:text-sec transition-colors shrink-0">
            <?php esc_html_e('Chi tiết tuyển dụng', 'monamedia'); ?>
            <img src="<?php echo MONA_THEME_PATH_URI; ?>/assets/images/icons/ic-chevron-right2.svg"
                class="w-4 h-4" alt="">
        </a>
    </div>

</div>