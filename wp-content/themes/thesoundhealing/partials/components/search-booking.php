<?php
defined('ABSPATH') || exit;

$search_url = home_url('/tim-kiem/');

$loai_hinh_opts = [
    ''         => ['label' => 'Tất cả',   'desc' => 'Dịch vụ, Khóa học & Workshop'],
    'dich-vu'  => ['label' => 'Dịch vụ',  'desc' => 'Liệu pháp âm thanh cá nhân'],
    'khoa-hoc' => ['label' => 'Khóa học', 'desc' => 'Chương trình đào tạo chuyên sâu'],
    'workshop' => ['label' => 'Workshop', 'desc' => 'Sự kiện trải nghiệm ngắn hạn'],
];

$time_opts = [
    'today'   => 'Hôm nay',
    'tomorrow' => 'Ngày mai',
    'weekend' => 'Cuối tuần này',
];

$guest_types = [
    'adult'  => ['label' => 'Người lớn', 'desc' => 'Từ 18 tuổi trở lên'],
    'child'  => ['label' => 'Trẻ em',    'desc' => 'Từ 2 – 17 tuổi'],
    'infant' => ['label' => 'Em bé',      'desc' => 'Dưới 2 tuổi'],
];

// Pre-fill from GET if rendered on results page
$pre_loai_hinh = sanitize_text_field($_GET['loai-hinh'] ?? '');
$pre_thoi_gian = sanitize_text_field($_GET['thoi-gian'] ?? '');
$pre_ngay      = sanitize_text_field($_GET['ngay']      ?? '');
$pre_nguoi_lon = (int) ($_GET['nguoi-lon'] ?? 0);
$pre_tre_em    = (int) ($_GET['tre-em']    ?? 0);
$pre_em_be     = (int) ($_GET['em-be']     ?? 0);
?>

<div class="search-booking" id="search-booking">
    <form class="sb-bar" method="GET" action="<?php echo esc_url($search_url); ?>" id="sb-form" novalidate>

        <!-- Field: Loại hình -->
        <div class="sb-field" id="sb-field-type">
            <button type="button" class="sb-field__btn"
                aria-expanded="false"
                aria-controls="sb-panel-type"
                data-sb-toggle="type">
                <span class="sb-field__label">Loại hình</span>
                <span class="sb-field__value" id="sb-val-type">
                    <?php echo esc_html(!empty($pre_loai_hinh) ? ($loai_hinh_opts[$pre_loai_hinh]['label'] ?? 'Chọn loại hình') : 'Chọn loại hình'); ?>
                </span>
            </button>
            <input type="hidden" name="loai-hinh" id="sb-input-type" value="<?php echo esc_attr($pre_loai_hinh); ?>">
            <div class="sb-panel sb-panel--type" id="sb-panel-type" role="listbox" aria-hidden="true">
                <?php foreach ($loai_hinh_opts as $val => $opt) : ?>
                    <button type="button"
                        class="sb-option<?php echo $pre_loai_hinh === $val ? ' is-active' : ''; ?>"
                        data-value="<?php echo esc_attr($val); ?>"
                        data-label="<?php echo esc_attr($opt['label']); ?>"
                        data-target-input="sb-input-type"
                        data-target-val="sb-val-type"
                        role="option">
                        <span class="sb-option__label"><?php echo esc_html($opt['label']); ?></span>
                        <span class="sb-option__desc"><?php echo esc_html($opt['desc']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <span class="sb-sep" aria-hidden="true"></span>

        <!-- Field: Thời gian -->
        <div class="sb-field" id="sb-field-time">
            <button type="button" class="sb-field__btn"
                aria-expanded="false"
                aria-controls="sb-panel-time"
                data-sb-toggle="time">
                <span class="sb-field__label">Thời gian</span>
                <span class="sb-field__value" id="sb-val-time">
                    <?php
                    if (!empty($pre_thoi_gian) && isset($time_opts[$pre_thoi_gian])) {
                        echo esc_html($time_opts[$pre_thoi_gian]);
                    } elseif (!empty($pre_ngay)) {
                        echo esc_html(date_i18n('j/m/Y', strtotime($pre_ngay)));
                    } else {
                        echo 'Thêm ngày';
                    }
                    ?>
                </span>
            </button>
            <input type="hidden" name="thoi-gian" id="sb-input-time" value="<?php echo esc_attr($pre_thoi_gian); ?>">
            <input type="hidden" name="ngay" id="sb-input-date" value="<?php echo esc_attr($pre_ngay); ?>">
            <div class="sb-panel sb-panel--time" id="sb-panel-time" aria-hidden="true">
                <p class="sb-panel__heading">Chọn nhanh</p>
                <div class="sb-time-pills">
                    <?php foreach ($time_opts as $val => $label) : ?>
                        <button type="button"
                            class="sb-time-pill<?php echo $pre_thoi_gian === $val ? ' is-active' : ''; ?>"
                            data-value="<?php echo esc_attr($val); ?>"
                            data-label="<?php echo esc_attr($label); ?>">
                            <?php echo esc_html($label); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <div class="sb-time-custom">
                    <p class="sb-panel__heading">Hoặc chọn ngày</p>
                    <input type="text" id="sb-flatpickr-trigger" class="sb-flatpickr-trigger" readonly>
                </div>
            </div>
        </div>

        <span class="sb-sep" aria-hidden="true"></span>

        <!-- Field: Khách -->
        <div class="sb-field sb-field--last" id="sb-field-guest">
            <button type="button" class="sb-field__btn"
                aria-expanded="false"
                aria-controls="sb-panel-guest"
                data-sb-toggle="guest">
                <span class="sb-field__label">Khách</span>
                <span class="sb-field__value" id="sb-val-guest">
                    <?php
                    $tong = $pre_nguoi_lon + $pre_tre_em + $pre_em_be;
                    if ($tong > 0) {
                        $parts = [];
                        if ($pre_nguoi_lon > 0) $parts[] = $pre_nguoi_lon . ' người lớn';
                        if ($pre_tre_em    > 0) $parts[] = $pre_tre_em    . ' trẻ em';
                        if ($pre_em_be     > 0) $parts[] = $pre_em_be     . ' em bé';
                        echo esc_html(implode(', ', $parts));
                    } else {
                        echo 'Thêm khách';
                    }
                    ?>
                </span>
            </button>
            <input type="hidden" name="nguoi-lon" id="sb-input-adult" value="<?php echo esc_attr($pre_nguoi_lon); ?>">
            <input type="hidden" name="tre-em" id="sb-input-child" value="<?php echo esc_attr($pre_tre_em); ?>">
            <input type="hidden" name="em-be" id="sb-input-infant" value="<?php echo esc_attr($pre_em_be); ?>">
            <div class="sb-panel sb-panel--guest" id="sb-panel-guest" aria-hidden="true">
                <?php
                $pre_counts = ['adult' => $pre_nguoi_lon, 'child' => $pre_tre_em, 'infant' => $pre_em_be];
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
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="sb-submit" aria-label="Tìm kiếm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2.2" />
                <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
            </svg>
            <span class="sb-submit__text">Tìm kiếm</span>
        </button>

    </form>
</div>