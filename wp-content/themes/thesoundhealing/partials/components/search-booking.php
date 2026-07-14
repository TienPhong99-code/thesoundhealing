<?php
defined('ABSPATH') || exit;

$search_url = home_url('/tim-kiem/');

$_img_base = get_template_directory_uri() . '/assets/images/';

// Key của mỗi danh mục là logic tìm kiếm (map post type + slug taxonomy trong
// page-search-results.php) nên cố định ở đây. Admin chỉ sửa được chữ + hình, tại
// Theme Settings → tab "Search Booking – Danh mục"; bỏ trống thì rơi về mặc định dưới đây.
$_sb_defaults = [
    'best-seller'   => ['label' => __('Best Seller', 'monamedia'),   'desc' => __('Được yêu thích nhất', 'monamedia'),             'image' => $_img_base . 'dv-exp-main.jpg'],
    'sound-healing' => ['label' => __('Sound Healing', 'monamedia'), 'desc' => __('Liệu pháp âm thanh chữa lành', 'monamedia'),    'image' => $_img_base . 'dv-tam-am-ngu-ngon-rieng-tu.jpg'],
    'usui-reiki'    => ['label' => __('Usui Reiki', 'monamedia'),    'desc' => __('Năng lượng chữa lành Reiki', 'monamedia'),      'image' => $_img_base . 'dv-chua-lanh-reiki-rieng-tu.jpg'],
    'khoa-hoc'      => ['label' => __('Khoá Học', 'monamedia'),      'desc' => __('Chương trình đào tạo chuyên sâu', 'monamedia'), 'image' => $_img_base . 'kh-hero.jpg'],
    'workshop'      => ['label' => __('Workshop', 'monamedia'),      'desc' => __('Sự kiện trải nghiệm ngắn hạn', 'monamedia'),    'image' => $_img_base . 'gallery-img-1.jpg'],
];

$loai_hinh_opts = [];
foreach ($_sb_defaults as $_key => $_def) {
    $_slug = str_replace('-', '_', $_key); // best-seller → sb_label_best_seller

    // Chữ admin nhập đi qua mona_wpml_string để dịch được ở WPML → String Translation
    // (khác với text mặc định: text đó nằm trong code nên dịch bằng .po/.mo).
    $_label = trim((string) get_field("sb_label_{$_slug}", 'option'));
    $_desc  = trim((string) get_field("sb_desc_{$_slug}",  'option'));

    $loai_hinh_opts[$_key] = [
        'label' => $_label !== '' ? mona_wpml_string($_label, "Search Booking - {$_key} label") : $_def['label'],
        'desc'  => $_desc  !== '' ? mona_wpml_string($_desc,  "Search Booking - {$_key} desc")  : $_def['desc'],
        'image' => get_field("sb_img_{$_slug}", 'option') ?: $_def['image'],
    ];
}

$_td  = new DateTime();
$_tm  = (new DateTime())->modify('+1 day');
$_dow = (int) $_td->format('N');
$_dts = ($_dow <= 6) ? (6 - $_dow) : 0;
$_sat = (clone $_td)->modify("+{$_dts} days");
$_sun = (clone $_sat)->modify('+1 day');
$_eom = (clone $_td)->modify('last day of this month');

$_thg = __('thg', 'monamedia');
$time_opts = [
    'today'   => ['label' => __('Hôm nay', 'monamedia'),      'sub' => $_td->format('j') . ' ' . $_thg . ' ' . $_td->format('n')],
    'tomorrow' => ['label' => __('Ngày mai', 'monamedia'),     'sub' => $_tm->format('j') . ' ' . $_thg . ' ' . $_tm->format('n')],
    'weekend' => ['label' => __('Cuối tuần này', 'monamedia'), 'sub' => $_sat->format('j') . ' – ' . $_sun->format('j') . ' ' . $_thg . ' ' . $_sat->format('n')],
    'month'   => ['label' => __('Trong tháng này', 'monamedia'), 'sub' => $_td->format('j') . ' – ' . $_eom->format('j') . ' ' . $_thg . ' ' . $_td->format('n')],
];

// Mức giá (VNĐ) — đơn vị nghìn cho 2 mốc đầu, mốc cuối 3 triệu
$price_opts = [
    'r1' => ['label' => __('Từ 0 - 499.000', 'monamedia'),          'min' => 0,       'max' => 499999],
    'r2' => ['label' => __('Từ 500.000 - 2.999.000', 'monamedia'),  'min' => 500000,  'max' => 2999999],
    'r3' => ['label' => __('Từ 3.000.000 trở lên', 'monamedia'),    'min' => 3000000, 'max' => PHP_INT_MAX],
];

// Pre-fill from GET if rendered on results page
$pre_loai_hinh  = sanitize_text_field($_GET['loai-hinh']  ?? '');
$pre_chuyen_mon = sanitize_text_field($_GET['chuyen-mon'] ?? '');
$pre_thoi_gian  = sanitize_text_field($_GET['thoi-gian']  ?? '');
$pre_ngay       = sanitize_text_field($_GET['ngay']       ?? '');
$pre_muc_gia    = sanitize_text_field($_GET['muc-gia'] ?? '');

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
if (!empty($pre_muc_gia) && isset($price_opts[$pre_muc_gia])) {
    $mobile_parts[] = $price_opts[$pre_muc_gia]['label'];
}
$mobile_summary = !empty($mobile_parts) ? implode(' · ', $mobile_parts) : __('Loại hình · Thời gian · Mức giá', 'monamedia');
?>

<div class="search-booking" id="search-booking"
    data-i18n-type="<?php echo esc_attr__('Chọn loại hình', 'monamedia'); ?>"
    data-i18n-time="<?php echo esc_attr__('Ngày đặt lịch', 'monamedia'); ?>"
    data-i18n-price="<?php echo esc_attr__('Chọn mức giá', 'monamedia'); ?>"
    data-i18n-summary="<?php echo esc_attr__('Loại hình · Thời gian · Mức giá', 'monamedia'); ?>"
    data-i18n-toast="<?php echo esc_attr__('Vui lòng chọn ít nhất một tiêu chí tìm kiếm', 'monamedia'); ?>">

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

            <!-- Field: Mức giá -->
            <div class="sb-field sb-field--last" id="sb-field-price">
                <button type="button" class="sb-field__btn"
                    aria-expanded="false"
                    aria-controls="sb-panel-price"
                    data-sb-toggle="price">
                    <span class="sb-field__label"><?php esc_html_e('Mức giá (VNĐ)', 'monamedia'); ?></span>
                    <span class="sb-field__value" id="sb-val-price">
                        <?php
                        if (!empty($pre_muc_gia) && isset($price_opts[$pre_muc_gia])) {
                            echo esc_html($price_opts[$pre_muc_gia]['label']);
                        } else {
                            esc_html_e('Chọn mức giá', 'monamedia');
                        }
                        ?>
                    </span>
                </button>
                <input type="hidden" name="muc-gia" id="sb-input-price" value="<?php echo esc_attr($pre_muc_gia); ?>">
                <div class="sb-panel sb-panel--price" id="sb-panel-price" aria-hidden="true">
                    <div class="sb-price-list">
                        <?php foreach ($price_opts as $key => $opt) : ?>
                            <button type="button"
                                class="sb-price-item<?php echo $pre_muc_gia === $key ? ' is-active' : ''; ?>"
                                data-value="<?php echo esc_attr($key); ?>"
                                data-label="<?php echo esc_attr($opt['label']); ?>">
                                <span class="sb-price-item__label"><?php echo esc_html($opt['label']); ?></span>
                                <span class="sb-price-item__check" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="sb-guest-footer">
                        <button type="button" class="sb-guest-clear" id="sb-price-clear"><?php esc_html_e('Xóa', 'monamedia'); ?></button>
                        <button type="button" class="sb-guest-apply" id="sb-price-apply"><?php esc_html_e('Áp dụng', 'monamedia'); ?></button>
                    </div>
                </div>
            </div>

            <!-- Submit (desktop only — mobile dùng sb-popup-footer) -->
            <button type="submit" class="sb-submit" aria-label="<?php echo esc_attr__('Tìm kiếm', 'monamedia'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2.2" />
                    <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                </svg>
                <span class="sb-submit__text"><?php esc_html_e('Tìm kiếm', 'monamedia'); ?></span>
            </button>

        </form>

        <!-- Popup footer (chỉ hiện trên mobile) -->
        <div class="sb-popup-footer">
            <button class="sb-popup-close" id="sb-popup-close" type="button" aria-label="<?php echo esc_attr__('Đóng', 'monamedia'); ?>">
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