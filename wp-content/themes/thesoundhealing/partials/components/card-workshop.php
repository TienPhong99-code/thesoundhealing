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
?>

<div class="relative flex flex-col rounded-[12px] overflow-hidden group h-full">

    <!-- Thumbnail -->
    <div class="relative overflow-hidden bg-[#e4e2dd] aspect-[4/3] shrink-0">
        <?php if (!empty($img_url)) : ?>
            <img src="<?php echo esc_url($img_url); ?>"
                alt="<?php echo esc_attr($img_alt); ?>"
                class="block w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php endif; ?>

        <!-- Loại workshop badge (top-left) -->
        <?php if (!empty($item['type'])) : ?>
            <div class="absolute top-4 left-4">
                <span class="bg-[rgba(251,249,244,0.9)] backdrop-blur-[2px] text-pri text-[10px] font-semibold uppercase tracking-[1.2px] px-2 py-1 rounded-[2px]">
                    <?php echo esc_html($item['type']); ?>
                </span>
            </div>
        <?php endif; ?>

    </div>

    <!-- Content -->
    <div class="flex flex-col flex-1 justify-between py-6 max-md:py-4">
        <div class="flex flex-col gap-3 mb-6">

            <!-- Date -->
            <?php if (!empty($item['date'])) : ?>
                <p class="text-pri text-[12px] font-semibold uppercase tracking-[1.2px]">
                    <?php echo esc_html($item['date']); ?>
                    <?php if (!empty($item['time'])) : ?>
                        <span class="text-[#717977] font-normal normal-case tracking-normal ml-1">· <?php echo esc_html($item['time']); ?></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <!-- Title -->
            <h3 class="font-title text-pri text-[24px] font-normal leading-[32px] max-md:text-[18px]">
                <?php echo esc_html($item['title']); ?>
            </h3>

            <!-- Location -->
            <?php if (!empty($item['location'])) : ?>
                <p class="text-[#717977] text-[13px] leading-[20px]">
                    📍 <?php echo esc_html($item['location']); ?>
                </p>
            <?php endif; ?>

            <!-- Description -->
            <?php if (!empty($item['desc'])) : ?>
                <p class="text-[#414847] text-[16px] leading-[24px]">
                    <?php echo esc_html($item['desc']); ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="flex max-md:flex-col md:items-end md:justify-between gap-4">
            <div class="flex flex-col gap-1 w-full">
                <span class="text-[#717977] text-[12px] font-semibold uppercase tracking-[1.2px]">HỌC PHÍ</span>
                <?php if (!empty($item['price'])) : ?>
                    <span class="text-pri text-[16px] font-semibold leading-[24px]">
                        <?php echo esc_html($item['price']); ?>
                    </span>
                <?php endif; ?>
            </div>
            <a href="<?php echo esc_url($card_url); ?>"
                class="btn btn-pri relative z-[2] shrink-0 <?php echo $status_key === 'closed' ? 'opacity-50 pointer-events-none' : ''; ?>">
                <?php echo $status_key === 'closed' ? 'HẾT CHỖ' : 'XEM CHI TIẾT'; ?>
            </a>
        </div>
    </div>

    <?php if ($status_key !== 'closed') : ?>
        <a href="<?php echo esc_url($card_url); ?>"
            class="absolute inset-0 z-[1]"
            aria-label="<?php echo esc_attr($item['title']); ?>"></a>
    <?php endif; ?>
</div>