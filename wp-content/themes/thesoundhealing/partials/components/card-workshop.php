<?php
defined('ABSPATH') || exit;

$item = $args['item'] ?? [];
if (empty($item)) return;

$img_url  = $item['image']['url'] ?? '';
$img_alt  = $item['image']['alt'] ?? '';
$card_url = $item['url']          ?? '#';

$status_map = [
    'open'     => ['label' => 'Còn chỗ',       'class' => 'bg-[#d4edda] text-[#1a6630]'],
    'limited'  => ['label' => 'Sắp hết chỗ',   'class' => 'bg-[#fff3cd] text-[#856404]'],
    'closed'   => ['label' => 'Hết chỗ',        'class' => 'bg-[#f8d7da] text-[#842029]'],
    'upcoming' => ['label' => 'Sắp diễn ra',    'class' => 'bg-[#eae8e3] text-pri'],
];
$status_key  = $item['status'] ?? 'open';
$status_info = $status_map[$status_key] ?? $status_map['open'];
$spots       = isset($item['spots']) && $item['spots'] !== '' && $item['spots'] !== null ? (int) $item['spots'] : null;
$instructor  = $item['instructor'] ?? '';
$duration    = $item['duration']   ?? '';
?>

<div class="relative flex flex-col overflow-hidden group h-full">

    <!-- Thumbnail -->
    <div class="relative overflow-hidden rounded-2xl bg-[#e4e2dd] aspect-square shrink-0">
        <?php if (!empty($img_url)) : ?>
            <img src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php endif; ?>

        <?php if ($spots !== null) : ?>
            <?php if ($spots === 0) : ?>
                <span class="absolute top-3 left-3 z-[2] bg-[#1b1c19] text-white text-[11px] font-semibold uppercase tracking-[1px] px-2.5 py-1 rounded-full">
                    Hết chỗ
                </span>
            <?php else : ?>
                <span class="absolute top-3 left-3 z-[2] bg-[#4e635a] text-white text-[11px] font-semibold uppercase tracking-[1px] px-2.5 py-1 rounded-full">
                    Còn <?php echo $spots; ?> chỗ
                </span>
            <?php endif; ?>
        <?php endif; ?>

        <?php /* status badge ẩn tạm
        <span class="absolute top-3 right-3 text-[10px] font-semibold uppercase tracking-[1.2px] px-2 py-1 rounded-[2px] <?php echo esc_attr($status_info['class']); ?>">
            <?php echo esc_html($status_info['label']); ?>
        </span>
        */ ?>
    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 justify-between py-4 gap-3">

        <div class="flex flex-col gap-2">

            <span class="text-[12px] uppercase tracking-[1.2px]">
                Workshop
            </span>

            <h3 class="text-[20px] font-normal line-clamp-2 max-md:text-[16px]">
                <?php echo esc_html($item['title']); ?>
            </h3>

            <div class="flex flex-col gap-1">
                <?php if (!empty($item['date'])) : ?>
                    <p class="text-[#414847] text-[13px] leading-[18px]">
                        <?php echo esc_html($item['date']); ?>
                        <?php if (!empty($item['time'])) : ?>
                            <span class=""> · <?php echo esc_html($item['time']); ?></span>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($item['location'])) : ?>
                    <p class="text-[14px] leading-[18px] line-clamp-1">
                        <?php echo esc_html($item['location']); ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($duration)) : ?>
                    <p class="text-[14px] leading-[18px]">
                        <?php echo esc_html($duration); ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($instructor)) : ?>
                    <p class="text-[14px] leading-[18px]">
                        <?php echo esc_html($instructor); ?>
                    </p>
                <?php endif; ?>
            </div>

        </div>

        <div class="flex items-center justify-between gap-2 pt-2 border-t border-[rgba(192,200,198,0.3)]">
            <?php if (!empty($item['price'])) : ?>
                <span class="font-title text-pri text-[18px] font-normal">
                    <?php echo esc_html($item['price']); ?>
                </span>
            <?php endif; ?>
            <!-- <?php if ($status_key !== 'closed') : ?>
                <span class="text-pri text-[11px] font-semibold uppercase tracking-[1.2px] ml-auto flex items-center gap-1">
                    Đăng ký
                    <svg class="w-[8px] h-[8px] shrink-0" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 4h6M4 1l3 3-3 3" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            <?php else : ?>
                <span class="text-[#842029] text-[11px] font-semibold uppercase tracking-[1.2px] ml-auto">Hết chỗ</span>
            <?php endif; ?> -->
        </div>

    </div>

    <?php if ($status_key !== 'closed') : ?>
        <a href="<?php echo esc_url($card_url); ?>"
            class="absolute inset-0 z-[1]"
            aria-label="<?php echo esc_attr($item['title']); ?>"></a>
    <?php endif; ?>
</div>