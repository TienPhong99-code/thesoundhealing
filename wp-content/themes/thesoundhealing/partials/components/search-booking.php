<?php
defined('ABSPATH') || exit;

$search_url = home_url('/tim-kiem/');

$loai_hinh_opts = [
    '' => [
        'label' => 'Tất cả',
        'desc'  => 'Dịch vụ, Khóa học & Workshop',
        'icon'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="3" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8"/><rect x="3" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8"/><rect x="13" y="13" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.8"/></svg>',
    ],
    'dich-vu' => [
        'label' => 'Dịch vụ',
        'desc'  => 'Liệu pháp âm thanh cá nhân',
        'icon'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18V5l12-2v13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="6" cy="18" r="3" stroke="currentColor" stroke-width="1.8"/><circle cx="18" cy="16" r="3" stroke="currentColor" stroke-width="1.8"/></svg>',
    ],
    'khoa-hoc' => [
        'label' => 'Khóa học',
        'desc'  => 'Chương trình đào tạo chuyên sâu',
        'icon'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
    'workshop' => [
        'label' => 'Workshop',
        'desc'  => 'Sự kiện trải nghiệm ngắn hạn',
        'icon'  => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="16" r="2" stroke="currentColor" stroke-width="1.8"/></svg>',
    ],
];

$_td  = new DateTime();
$_tm  = (new DateTime())->modify('+1 day');
$_dow = (int) $_td->format('N');
$_dts = ($_dow <= 6) ? (6 - $_dow) : 0;
$_sat = (clone $_td)->modify("+{$_dts} days");
$_sun = (clone $_sat)->modify('+1 day');

$time_opts = [
    'today'   => ['label' => 'Hôm nay',       'sub' => $_td->format('j') . ' thg ' . $_td->format('n')],
    'tomorrow' => ['label' => 'Ngày mai',       'sub' => $_tm->format('j') . ' thg ' . $_tm->format('n')],
    'weekend' => ['label' => 'Cuối tuần này',  'sub' => $_sat->format('j') . ' – ' . $_sun->format('j') . ' thg ' . $_sat->format('n')],
];

$guest_types = [
    'adult'  => ['label' => 'Người lớn', 'desc' => 'Từ 18 tuổi trở lên'],
    'child'  => ['label' => 'Trẻ em',    'desc' => 'Từ 2 – 17 tuổi'],
    'infant' => ['label' => 'Em bé',      'desc' => 'Dưới 2 tuổi'],
];

// Pre-fill from GET if rendered on results page
$pre_loai_hinh  = sanitize_text_field($_GET['loai-hinh']  ?? '');
$pre_chuyen_mon = sanitize_text_field($_GET['chuyen-mon'] ?? '');
$pre_thoi_gian  = sanitize_text_field($_GET['thoi-gian']  ?? '');
$pre_ngay       = sanitize_text_field($_GET['ngay']       ?? '');
$pre_nguoi_lon  = (int) ($_GET['nguoi-lon'] ?? 0);
$pre_tre_em     = (int) ($_GET['tre-em']    ?? 0);
$pre_em_be      = (int) ($_GET['em-be']     ?? 0);

// Taxonomy terms grouped by post type
$terms_dich_vu  = get_terms(['taxonomy' => 'loai_dich_vu',    'hide_empty' => false]);
$terms_khoa_hoc = get_terms(['taxonomy' => 'bo_mon_khoa_hoc', 'hide_empty' => false]);
$terms_workshop = get_terms(['taxonomy' => 'loai_workshop',   'hide_empty' => false]);
if (is_wp_error($terms_dich_vu))  $terms_dich_vu  = [];
if (is_wp_error($terms_khoa_hoc)) $terms_khoa_hoc = [];
if (is_wp_error($terms_workshop)) $terms_workshop = [];

// Display value cho field Loại hình
$cat_label_map = ['dich-vu' => 'Dịch vụ', 'khoa-hoc' => 'Khóa học', 'workshop' => 'Workshop'];
$display_type  = 'Chọn loại hình';
if (!empty($pre_chuyen_mon)) {
    $found_term = null;
    foreach (['loai_dich_vu', 'bo_mon_khoa_hoc', 'loai_workshop'] as $_tax) {
        $t = get_term_by('slug', $pre_chuyen_mon, $_tax);
        if ($t && !is_wp_error($t)) {
            $found_term = $t;
            break;
        }
    }
    if ($found_term) {
        $cl = $cat_label_map[$pre_loai_hinh] ?? '';
        $display_type = $cl ? $cl . ' · ' . $found_term->name : $found_term->name;
    }
} elseif (!empty($pre_loai_hinh) && isset($cat_label_map[$pre_loai_hinh])) {
    $display_type = $cat_label_map[$pre_loai_hinh];
}
?>

<div class="search-booking" id="search-booking">
    <form class="sb-bar" method="GET" action="<?php echo esc_url($search_url); ?>" id="sb-form" novalidate>

        <!-- Row: Loại hình + Thời gian (50/50 trên mobile) -->
        <div class="sb-row-top">

            <!-- Field: Loại hình -->
            <div class="sb-field" id="sb-field-type">
                <button type="button" class="sb-field__btn"
                    aria-expanded="false"
                    aria-controls="sb-panel-type"
                    data-sb-toggle="type">
                    <span class="sb-field__label">Loại hình</span>
                    <span class="sb-field__value" id="sb-val-type"><?php echo esc_html($display_type); ?></span>
                </button>
                <input type="hidden" name="loai-hinh" id="sb-input-type" value="<?php echo esc_attr($pre_loai_hinh); ?>">
                <input type="hidden" name="chuyen-mon" id="sb-input-subterm" value="<?php echo esc_attr($pre_chuyen_mon); ?>">

                <div class="sb-panel sb-panel--type" id="sb-panel-type" aria-hidden="true">

                    <!-- Category pills -->
                    <div class="sb-type-cats">
                        <button type="button"
                            class="sb-type-cat<?php echo empty($pre_loai_hinh) ? ' is-active' : ''; ?>"
                            data-cat-filter=""
                            data-cat-label="Chọn loại hình">Tất cả</button>
                        <?php foreach ($cat_label_map as $k => $v) : ?>
                            <button type="button"
                                class="sb-type-cat<?php echo $pre_loai_hinh === $k ? ' is-active' : ''; ?>"
                                data-cat-filter="<?php echo esc_attr($k); ?>"
                                data-cat-label="<?php echo esc_attr($v); ?>">
                                <?php echo esc_html($v); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="sb-type-sep"></div>

                    <!-- Sub-terms scrollable list -->
                    <div class="sb-subterms-wrap">
                        <?php
                        $all_term_groups = [
                            'dich-vu'  => $terms_dich_vu,
                            'khoa-hoc' => $terms_khoa_hoc,
                            'workshop' => $terms_workshop,
                        ];
                        $has_terms = false;
                        foreach ($all_term_groups as $cat_key => $terms_group) :
                            foreach ($terms_group as $term) :
                                $has_terms  = true;
                                $is_active  = $pre_chuyen_mon === $term->slug;
                                $is_hidden  = !empty($pre_loai_hinh) && $pre_loai_hinh !== $cat_key;
                                $thumb_id   = get_term_meta($term->term_id, 'thumbnail_id', true);
                        ?>
                                <button type="button"
                                    class="sb-subterm<?php echo $is_active ? ' is-active' : '';
                                                        echo $is_hidden ? ' sb-subterm--hidden' : ''; ?>"
                                    data-cat="<?php echo esc_attr($cat_key); ?>"
                                    data-value="<?php echo esc_attr($term->slug); ?>"
                                    data-label="<?php echo esc_attr($term->name); ?>">
                                    <span class="sb-subterm__img">
                                        <?php if ($thumb_id) : ?>
                                            <?php echo wp_get_attachment_image($thumb_id, [48, 48], false, ['class' => 'sb-subterm__thumb']); ?>
                                        <?php else : ?>
                                            <span class="sb-subterm__placeholder"></span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="sb-subterm__text">
                                        <span class="sb-subterm__name"><?php echo esc_html($term->name); ?></span>
                                        <?php if (!empty($term->description)) : ?>
                                            <span class="sb-subterm__desc"><?php echo esc_html($term->description); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </button>
                            <?php
                            endforeach;
                        endforeach;
                        if (!$has_terms) :
                            ?>
                            <p class="sb-subterms-empty">Chưa có danh mục nào.</p>
                        <?php endif; ?>
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
                    <span class="sb-field__label">Thời gian</span>
                    <span class="sb-field__value" id="sb-val-time">
                        <?php
                        if (!empty($pre_thoi_gian) && isset($time_opts[$pre_thoi_gian])) {
                            echo esc_html($time_opts[$pre_thoi_gian]['label']);
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

                    <div class="sb-time-sep-v max-sm:!hidden"></div>

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