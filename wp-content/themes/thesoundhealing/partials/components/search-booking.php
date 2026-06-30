<?php
defined('ABSPATH') || exit;

$search_url = home_url('/tim-kiem/');

$_img_base = get_template_directory_uri() . '/assets/images/';

$_sb_imgs = [
    'best-seller'   => get_field('sb_img_best_seller',   'option') ?: $_img_base . 'dv-exp-main.jpg',
    'sound-healing' => get_field('sb_img_sound_healing', 'option') ?: $_img_base . 'dv-tam-am-ngu-ngon-rieng-tu.jpg',
    'usui-reiki'    => get_field('sb_img_usui_reiki',    'option') ?: $_img_base . 'dv-chua-lanh-reiki-rieng-tu.jpg',
    'khoa-hoc'      => get_field('sb_img_khoa_hoc',      'option') ?: $_img_base . 'kh-hero.jpg',
    'workshop'      => get_field('sb_img_workshop',      'option') ?: $_img_base . 'gallery-img-1.jpg',
];

$loai_hinh_opts = [
    'best-seller'   => ['label' => 'Best Seller',   'desc' => 'Được yêu thích nhất',              'image' => $_sb_imgs['best-seller']],
    'sound-healing' => ['label' => 'Sound Healing', 'desc' => 'Liệu pháp âm thanh chữa lành',    'image' => $_sb_imgs['sound-healing']],
    'usui-reiki'    => ['label' => 'Usui Reiki',    'desc' => 'Năng lượng chữa lành Reiki',       'image' => $_sb_imgs['usui-reiki']],
    'khoa-hoc'      => ['label' => 'Khoá Học',      'desc' => 'Chương trình đào tạo chuyên sâu',  'image' => $_sb_imgs['khoa-hoc']],
    'workshop'      => ['label' => 'Workshop',      'desc' => 'Sự kiện trải nghiệm ngắn hạn',     'image' => $_sb_imgs['workshop']],
];

$_td  = new DateTime();
$_tm  = (new DateTime())->modify('+1 day');
$_dow = (int) $_td->format('N');
$_dts = ($_dow <= 6) ? (6 - $_dow) : 0;
$_sat = (clone $_td)->modify("+{$_dts} days");
$_sun = (clone $_sat)->modify('+1 day');

$_thg = __('thg', 'monamedia');
$time_opts = [
    'today'   => ['label' => __('Hôm nay', 'monamedia'),      'sub' => $_td->format('j') . ' ' . $_thg . ' ' . $_td->format('n')],
    'tomorrow' => ['label' => __('Ngày mai', 'monamedia'),     'sub' => $_tm->format('j') . ' ' . $_thg . ' ' . $_tm->format('n')],
    'weekend' => ['label' => __('Cuối tuần này', 'monamedia'), 'sub' => $_sat->format('j') . ' – ' . $_sun->format('j') . ' ' . $_thg . ' ' . $_sat->format('n')],
];

$guest_types = [
    'adult' => ['label' => __('Người lớn (13+)', 'monamedia'), 'desc' => ''],
    'child' => ['label' => __('Trẻ em (6–12)',   'monamedia'), 'desc' => ''],
];

// Pre-fill from GET if rendered on results page
$pre_loai_hinh  = sanitize_text_field($_GET['loai-hinh']  ?? '');
$pre_chuyen_mon = sanitize_text_field($_GET['chuyen-mon'] ?? '');
$pre_thoi_gian  = sanitize_text_field($_GET['thoi-gian']  ?? '');
$pre_ngay       = sanitize_text_field($_GET['ngay']       ?? '');
$pre_nguoi_lon  = (int) ($_GET['nguoi-lon'] ?? 0);
$pre_tre_em     = (int) ($_GET['tre-em']    ?? 0);

// Display value cho field Loại hình
$display_type = __('Chọn loại hình', 'monamedia');
if (!empty($pre_loai_hinh) && isset($loai_hinh_opts[$pre_loai_hinh])) {
    $display_type = $loai_hinh_opts[$pre_loai_hinh]['label'];
}

// Mobile summary sub-text
$mobile_parts = [];
if (!empty($pre_loai_hinh) && isset($loai_hinh_opts[$pre_loai_hinh])) {
    $mobile_parts[] = $loai_hinh_opts[$pre_loai_hinh]['label'];
}
if (!empty($pre_thoi_gian) && isset($time_opts[$pre_thoi_gian])) {
    $mobile_parts[] = $time_opts[$pre_thoi_gian]['label'];
} elseif (!empty($pre_ngay)) {
    $mobile_parts[] = date_i18n('j/m/Y', strtotime($pre_ngay));
}
$tong_guest = $pre_nguoi_lon + $pre_tre_em;
if ($tong_guest > 0) {
    $mobile_parts[] = $tong_guest . ' ' . __('khách', 'monamedia');
}
$mobile_summary = !empty($mobile_parts) ? implode(' · ', $mobile_parts) : __('Loại hình · Thời gian · Khách', 'monamedia');
?>

<div class="search-booking" id="search-booking">

    <!-- Mobile compact trigger (ẩn trên desktop) -->
    <button class="sb-mobile-trigger" id="sb-mobile-trigger" type="button">
        <span class="sb-mobile-trigger__icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2.2" />
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
            </svg>
        </span>
        <span class="sb-mobile-trigger__body">
            <span class="sb-mobile-trigger__title"><?php esc_html_e('Tìm kiếm', 'monamedia'); ?></span>
            <span class="sb-mobile-trigger__sub" id="sb-mobile-summary"><?php echo esc_html($mobile_summary); ?></span>
        </span>
    </button>

    <!-- Popup overlay (desktop: wrapper trong suốt; mobile: fullscreen slide-up) -->
    <div class="sb-popup-overlay" id="sb-popup-overlay" aria-hidden="true">

        <form class="sb-bar" method="GET" action="<?php echo esc_url($search_url); ?>" id="sb-form" novalidate>

            <!-- Row: Loại hình + Thời gian (50/50 trên mobile) -->
            <div class="sb-row-top">

                <!-- Field: Loại hình -->
                <div class="sb-field" id="sb-field-type">
                    <button type="button" class="sb-field__btn"
                        aria-expanded="false"
                        aria-controls="sb-panel-type"
                        data-sb-toggle="type">
                        <span class="sb-field__label"><?php esc_html_e('Loại hình', 'monamedia'); ?></span>
                        <span class="sb-field__value" id="sb-val-type"><?php echo esc_html($display_type); ?></span>
                    </button>
                    <input type="hidden" name="loai-hinh" id="sb-input-type" value="<?php echo esc_attr($pre_loai_hinh); ?>">
                    <input type="hidden" name="chuyen-mon" id="sb-input-subterm" value="<?php echo esc_attr($pre_chuyen_mon); ?>">

                    <div class="sb-panel sb-panel--type" id="sb-panel-type" aria-hidden="true">
                        <div class="sb-type-list">
                            <?php foreach ($loai_hinh_opts as $k => $opt) : ?>
                                <button type="button"
                                    class="sb-type-item<?php echo $pre_loai_hinh === $k ? ' is-active' : ''; ?>"
                                    data-value="<?php echo esc_attr($k); ?>"
                                    data-label="<?php echo esc_attr($opt['label']); ?>">
                                    <span class="sb-type-item__img">
                                        <img src="<?php echo esc_url($opt['image']); ?>" alt="<?php echo esc_attr($opt['label']); ?>" loading="lazy">
                                    </span>
                                    <span class="sb-type-item__text">
                                        <span class="sb-type-item__name"><?php echo esc_html($opt['label']); ?></span>
                                        <span class="sb-type-item__desc"><?php echo esc_html($opt['desc']); ?></span>
                                    </span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <span class="sb-sep" aria-hidden="true"></span>

                <!-- Field: Thời gian -->
                <div class="sb-field" id="sb-field-time">
                    <button type="button" class="sb-field__btn"
                        aria-expanded="false"
                        aria-controls="sb-panel-time"
                        data-sb-toggle="time">
                        <span class="sb-field__label"><?php esc_html_e('Thời gian', 'monamedia'); ?></span>
                        <span class="sb-field__value" id="sb-val-time">
                            <?php
                            if (!empty($pre_thoi_gian) && isset($time_opts[$pre_thoi_gian])) {
                                echo esc_html($time_opts[$pre_thoi_gian]['label']);
                            } elseif (!empty($pre_ngay)) {
                                echo esc_html(date_i18n('j/m/Y', strtotime($pre_ngay)));
                            } else {
                                esc_html_e('Ngày đặt lịch', 'monamedia');
                            }
                            ?>
                        </span>
                    </button>
                    <input type="hidden" name="thoi-gian" id="sb-input-time" value="<?php echo esc_attr($pre_thoi_gian); ?>">
                    <input type="hidden" name="ngay" id="sb-input-date" value="<?php echo esc_attr($pre_ngay); ?>">
                    <div class="sb-panel sb-panel--time max-sm:!flex-col" id="sb-panel-time" aria-hidden="true">

                        <!-- Quick options -->
                        <div class="sb-time-left">
                            <?php foreach ($time_opts as $val => $opt) : ?>
                                <button type="button"
                                    class="sb-time-pill<?php echo $pre_thoi_gian === $val ? ' is-active' : ''; ?>"
                                    data-value="<?php echo esc_attr($val); ?>"
                                    data-label="<?php echo esc_attr($opt['label']); ?>">
                                    <span class="sb-time-pill__label"><?php echo esc_html($opt['label']); ?></span>
                                    <span class="sb-time-pill__sub"><?php echo esc_html($opt['sub']); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Inline calendar -->
                        <div class="sb-time-right">
                            <input type="text" id="sb-flatpickr-trigger" class="sb-flatpickr-trigger" readonly>
                        </div>

                    </div>
                </div>

            </div><!-- /.sb-row-top -->

            <span class="sb-sep" aria-hidden="true"></span>

            <!-- Field: Khách -->
            <div class="sb-field sb-field--last" id="sb-field-guest">
                <button type="button" class="sb-field__btn"
                    aria-expanded="false"
                    aria-controls="sb-panel-guest"
                    data-sb-toggle="guest">
                    <span class="sb-field__label"><?php esc_html_e('Khách', 'monamedia'); ?></span>
                    <span class="sb-field__value" id="sb-val-guest">
                        <?php
                        $tong = $pre_nguoi_lon + $pre_tre_em;
                        if ($tong > 0) {
                            $parts = [];
                            if ($pre_nguoi_lon > 0) $parts[] = $pre_nguoi_lon . ' ' . __('người lớn', 'monamedia');
                            if ($pre_tre_em    > 0) $parts[] = $pre_tre_em    . ' ' . __('trẻ em', 'monamedia');
                            echo esc_html(implode(', ', $parts));
                        } else {
                            esc_html_e('Số lượng khách', 'monamedia');
                        }
                        ?>
                    </span>
                </button>
                <input type="hidden" name="nguoi-lon" id="sb-input-adult" value="<?php echo esc_attr($pre_nguoi_lon); ?>">
                <input type="hidden" name="tre-em" id="sb-input-child" value="<?php echo esc_attr($pre_tre_em); ?>">
                <div class="sb-panel sb-panel--guest" id="sb-panel-guest" aria-hidden="true">
                    <?php
                    $pre_counts = ['adult' => $pre_nguoi_lon, 'child' => $pre_tre_em];
                    foreach ($guest_types as $key => $g) :
                        $count = $pre_counts[$key] ?? 0;
                    ?>
                        <div class="sb-guest-row">
                            <div class="sb-guest-info">
                                <span class="sb-guest-name"><?php echo esc_html($g['label']); ?></span>
                                <span class="sb-guest-desc"><?php echo esc_html($g['desc']); ?></span>
                            </div>
                            <div class="sb-guest-counter">
                                <button type="button"
                                    class="sb-counter-btn sb-counter-minus"
                                    data-target="<?php echo esc_attr($key); ?>"
                                    aria-label="Giảm số <?php echo esc_attr(mb_strtolower($g['label'])); ?>"
                                    <?php echo $count === 0 ? 'disabled' : ''; ?>>−</button>
                                <span class="sb-counter-val" id="sb-count-<?php echo esc_attr($key); ?>"><?php echo $count; ?></span>
                                <button type="button"
                                    class="sb-counter-btn sb-counter-plus"
                                    data-target="<?php echo esc_attr($key); ?>"
                                    aria-label="Tăng số <?php echo esc_attr(mb_strtolower($g['label'])); ?>">+</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="sb-guest-footer">
                        <button type="button" class="sb-guest-clear" id="sb-guest-clear"><?php esc_html_e('Xóa tất cả', 'monamedia'); ?></button>
                        <button type="button" class="sb-guest-apply" id="sb-guest-apply"><?php esc_html_e('Áp dụng', 'monamedia'); ?></button>
                    </div>
                </div>
            </div>

            <!-- Submit (desktop only — mobile dùng sb-popup-footer) -->
            <button type="submit" class="sb-submit" aria-label="Tìm kiếm">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2.2" />
                    <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                </svg>
                <span class="sb-submit__text"><?php esc_html_e('Tìm kiếm', 'monamedia'); ?></span>
            </button>

        </form>

        <!-- Popup footer (chỉ hiện trên mobile) -->
        <div class="sb-popup-footer">
            <button class="sb-popup-close" id="sb-popup-close" type="button" aria-label="Đóng">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                </svg>
            </button>
            <button type="button" class="sb-popup-clear-all" id="sb-popup-clear-all"><?php esc_html_e('Xóa tất cả', 'monamedia'); ?></button>
            <button type="button" class="sb-popup-submit" id="sb-popup-submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2.2" />
                    <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                </svg>
                <?php esc_html_e('Tìm kiếm', 'monamedia'); ?>
            </button>
        </div>

    </div><!-- /.sb-popup-overlay -->

</div><!-- /.search-booking -->