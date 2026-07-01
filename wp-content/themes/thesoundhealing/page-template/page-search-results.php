<?php

/**
 * Template Name: Kết quả tìm kiếm
 * Template Post Type: page
 *
 * Slug trang gợi ý: tim-kiem
 * URL: yoursite.com/tim-kiem
 */

defined('ABSPATH') || exit;

// ── Đọc params ────────────────────────────────────────────────────────────
$loai_hinh  = sanitize_text_field($_GET['loai-hinh']  ?? '');
$chuyen_mon = sanitize_text_field($_GET['chuyen-mon'] ?? '');
$thoi_gian  = sanitize_text_field($_GET['thoi-gian']  ?? '');
$ngay       = sanitize_text_field($_GET['ngay']       ?? '');
$muc_gia   = sanitize_text_field($_GET['muc-gia'] ?? '');

// Mức giá (VNĐ) — đơn vị nghìn cho 2 mốc đầu, mốc cuối 3 triệu
$price_ranges = [
    'r1' => ['label' => 'Từ 0 - 499.000',           'min' => 0,       'max' => 499999],
    'r2' => ['label' => 'Từ 500.000 - 2.999.000',   'min' => 500000,  'max' => 2999999],
    'r3' => ['label' => 'Từ 3.000.000 trở lên',     'min' => 3000000, 'max' => PHP_INT_MAX],
];

// ── Map loai-hinh → post_type(s) ─────────────────────────────────────────
$pt_map = [
    'best-seller'   => ['dich_vu', 'khoa_hoc', 'workshop'],
    'sound-healing' => ['dich_vu'],
    'usui-reiki'    => ['dich_vu'],
    'khoa-hoc'      => ['khoa_hoc'],
    'workshop'      => ['workshop'],
];

$post_types = !empty($loai_hinh) && isset($pt_map[$loai_hinh])
    ? $pt_map[$loai_hinh]
    : ['dich_vu', 'khoa_hoc', 'workshop'];

// ── Tính khoảng ngày từ thoi-gian ────────────────────────────────────────
$date_from = '';
$date_to   = '';

if (!empty($thoi_gian)) {
    switch ($thoi_gian) {
        case 'today':
            $date_from = $date_to = date('Ymd');
            break;
        case 'tomorrow':
            $date_from = $date_to = date('Ymd', strtotime('+1 day'));
            break;
        case 'weekend':
            $now = new DateTime();
            $dow = (int) $now->format('N'); // 1=Mon ... 7=Sun
            $days_to_sat = ($dow <= 6) ? (6 - $dow) : 0;
            $sat = (clone $now)->modify("+{$days_to_sat} days");
            $sun = (clone $sat)->modify('+1 day');
            $date_from = $sat->format('Ymd');
            $date_to   = $sun->format('Ymd');
            break;
        case 'month':
            $now = new DateTime();
            $date_from = $now->format('Ymd');
            $date_to   = (clone $now)->modify('last day of this month')->format('Ymd');
            break;
    }
} elseif (!empty($ngay)) {
    $d = DateTime::createFromFormat('Y-m-d', $ngay);
    if ($d) {
        $date_from = $date_to = $d->format('Ymd');
    }
}

// ── Parse bất kỳ định dạng ngày nào về timestamp ─────────────────────────
function sr_parse_date_ts(?string $raw): ?int
{
    if (!$raw) return null;
    $s = trim($raw);
    // DD-MM-YYYY (định dạng admin nhập)
    if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $s, $m)) {
        $ts = mktime(0, 0, 0, (int)$m[2], (int)$m[1], (int)$m[3]);
        return $ts ?: null;
    }
    // Ymd không dấu (20260720)
    if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $s, $m)) {
        $ts = mktime(0, 0, 0, (int)$m[2], (int)$m[3], (int)$m[1]);
        return $ts ?: null;
    }
    // YYYY-MM-DD
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s)) {
        $ts = strtotime($s);
        return ($ts && $ts > 0) ? $ts : null;
    }
    // "20 tháng 7, 2026"
    if (preg_match('/(\d{1,2})\s+[Tt]háng\s+(\d{1,2})[,\s]+(\d{4})/u', $s, $m)) {
        $ts = mktime(0, 0, 0, (int)$m[2], (int)$m[1], (int)$m[3]);
        return $ts ?: null;
    }
    $ts = strtotime($s);
    return ($ts && $ts > 0) ? $ts : null;
}

// Chuyển $date_from/$date_to (Ymd) sang timestamp để so sánh phía PHP
$date_from_ts = null;
$date_to_ts   = null;
if (!empty($date_from) && preg_match('/^(\d{4})(\d{2})(\d{2})$/', $date_from, $_dm)) {
    $date_from_ts = mktime(0,  0,  0,  (int)$_dm[2], (int)$_dm[3], (int)$_dm[1]);
    $date_to_ts   = mktime(23, 59, 59, (int)substr($date_to, 4, 2), (int)substr($date_to, 6, 2), (int)substr($date_to, 0, 4));
}

// ── Hàm build item data từ WP_Post ───────────────────────────────────────
function sr_build_dich_vu(WP_Post $post): array
{
    $thumb = get_the_post_thumbnail_url($post->ID, 'full');
    $terms = get_the_terms($post->ID, 'loai_dich_vu');
    return [
        'image'          => ['url' => $thumb ?: '', 'alt' => $post->post_title],
        'title'          => $post->post_title,
        'category'       => (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '',
        'available_days' => get_field('dv_available_days',  $post->ID),
        'duration'       => get_field('dv_duration',        $post->ID),
        'branch'         => get_field('dv_branch',          $post->ID),
        'location'       => get_field('dv_location',        $post->ID),
        'instructor'     => get_field('dv_instructor_name', $post->ID),
        'status'         => get_field('dv_status',          $post->ID) ?: '',
        'price'          => get_field('dv_price',           $post->ID),
        'spots'          => get_field('dv_spots',           $post->ID),
        'best_seller'    => get_field('dv_best_seller',     $post->ID),
        'url'            => get_permalink($post->ID),
    ];
}

function sr_build_khoa_hoc(WP_Post $post): array
{
    $thumb = get_the_post_thumbnail_url($post->ID, 'full');
    $terms = get_the_terms($post->ID, 'bo_mon_khoa_hoc');
    return [
        'image'      => ['url' => $thumb ?: '', 'alt' => $post->post_title],
        'title'      => $post->post_title,
        'level'      => get_field('level',           $post->ID),
        'start_date' => get_field('start_date',      $post->ID),
        'duration'   => get_field('duration',        $post->ID),
        'instructor' => get_field('instructor_name', $post->ID),
        'location'   => get_field('location',        $post->ID),
        'branch'     => get_field('kh_branch',       $post->ID),
        'status'      => get_field('kh_status',       $post->ID) ?: '',
        'price'       => get_field('price',           $post->ID),
        'spots'       => get_field('kh_spots',        $post->ID),
        'best_seller' => get_field('kh_best_seller',  $post->ID),
        'url'         => get_permalink($post->ID),
    ];
}

function sr_build_workshop(WP_Post $post): array
{
    $thumb = get_the_post_thumbnail_url($post->ID, 'full');
    $terms = get_the_terms($post->ID, 'loai_workshop');
    return [
        'image'    => ['url' => $thumb ?: '', 'alt' => $post->post_title],
        'title'    => $post->post_title,
        'type'     => (!is_wp_error($terms) && !empty($terms)) ? $terms[0]->name : '',
        'status'      => get_field('ws_status',           $post->ID) ?: 'open',
        'date'        => get_field('ws_date',             $post->ID),
        'time'        => get_field('ws_time',             $post->ID),
        'location'    => get_field('ws_location',         $post->ID),
        'duration'    => get_field('ws_duration',         $post->ID),
        'instructor'  => get_field('ws_instructor_name',  $post->ID),
        'desc'        => get_field('ws_short_desc',       $post->ID),
        'price'       => get_field('ws_price',            $post->ID),
        'spots'       => get_field('ws_spots',            $post->ID),
        'best_seller' => get_field('ws_best_seller',      $post->ID),
        'url'         => get_permalink($post->ID),
    ];
}

// ── Query từng post type ──────────────────────────────────────────────────
$results = []; // [['type' => 'dich_vu', 'item' => [...]], ...]

foreach ($post_types as $pt) {
    $query_args = [
        'post_type'      => $pt,
        'post_status'    => 'publish',
        'posts_per_page' => 24,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',
    ];

    // Lọc theo loai-hinh đặc biệt
    $tax_map_pt = [
        'dich_vu'  => 'loai_dich_vu',
        'khoa_hoc' => 'bo_mon_khoa_hoc',
        'workshop' => 'loai_workshop',
    ];
    if ($loai_hinh === 'best-seller') {
        $bs_key = ($pt === 'dich_vu') ? 'dv_best_seller' : (($pt === 'khoa_hoc') ? 'kh_best_seller' : 'ws_best_seller');
        $bs_clause = ['key' => $bs_key, 'value' => '1', 'compare' => '='];
        if (!empty($query_args['meta_query'])) {
            $query_args['meta_query'][] = $bs_clause;
        } else {
            $query_args['meta_query'] = [$bs_clause];
        }
    } elseif (in_array($loai_hinh, ['sound-healing', 'usui-reiki'], true) && isset($tax_map_pt[$pt])) {
        $tax_clause = ['taxonomy' => $tax_map_pt[$pt], 'field' => 'slug', 'terms' => $loai_hinh];
        if (!empty($query_args['tax_query'])) {
            $query_args['tax_query'][] = $tax_clause;
        } else {
            $query_args['tax_query'] = [$tax_clause];
        }
    }

    // Lọc theo taxonomy term (chuyen-mon)
    if (!empty($chuyen_mon)) {
        if (isset($tax_map_pt[$pt])) {
            $tax_clause = ['taxonomy' => $tax_map_pt[$pt], 'field' => 'slug', 'terms' => $chuyen_mon];
            if (!empty($query_args['tax_query'])) {
                $query_args['tax_query'][] = $tax_clause;
            } else {
                $query_args['tax_query'] = [$tax_clause];
            }
        }
    }

    $q = new WP_Query($query_args);
    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $post = get_post();

            // Lọc theo ngày (PHP-side, hỗ trợ DD-MM-YYYY, Ymd, YYYY-MM-DD)
            if ($date_from_ts !== null && in_array($pt, ['workshop', 'khoa_hoc'], true)) {
                $date_field  = ($pt === 'workshop') ? 'ws_date' : 'start_date';
                $post_date_ts = sr_parse_date_ts(get_field($date_field, $post->ID));
                if ($post_date_ts === null || $post_date_ts < $date_from_ts || $post_date_ts > $date_to_ts) {
                    continue;
                }
            }

            // Lọc theo mức giá (PHP-side, giá lưu dạng chuỗi "800.000 VNĐ")
            if ($muc_gia !== '' && isset($price_ranges[$muc_gia])) {
                $price_field = ($pt === 'dich_vu') ? 'dv_price' : (($pt === 'khoa_hoc') ? 'price' : 'ws_price');
                $raw_price   = (string) get_field($price_field, $post->ID);
                $price_val   = class_exists('TSH_WC_Product_Sync')
                    ? TSH_WC_Product_Sync::parse_price($raw_price)
                    : (float) preg_replace('/[^\d]/', '', $raw_price);
                $rng = $price_ranges[$muc_gia];
                // Bỏ qua item không có giá rõ ràng (0 = "liên hệ"/trống) hoặc ngoài khoảng
                if ($price_val <= 0 || $price_val < $rng['min'] || $price_val > $rng['max']) {
                    continue;
                }
            }

            $item = match ($pt) {
                'dich_vu'  => sr_build_dich_vu($post),
                'khoa_hoc' => sr_build_khoa_hoc($post),
                'workshop' => sr_build_workshop($post),
            };
            $results[] = ['type' => $pt, 'item' => $item];
        }
        wp_reset_postdata();
    }
}

// ── Label hiển thị filter đã chọn ────────────────────────────────────────
$label_map = [
    'best-seller'   => 'Best Seller',
    'sound-healing' => 'Sound Healing',
    'usui-reiki'    => 'Usui Reiki',
    'khoa-hoc'      => 'Khoá Học',
    'workshop'      => 'Workshop',
];
$time_label_map = ['today'   => 'Hôm nay', 'tomorrow' => 'Ngày mai', 'weekend'  => 'Cuối tuần này', 'month' => 'Trong tháng này'];

// Lấy tên sub-term để hiển thị trong active tags
$chuyen_mon_label = '';
if (!empty($chuyen_mon)) {
    foreach (['loai_dich_vu', 'bo_mon_khoa_hoc', 'loai_workshop'] as $_tax) {
        $t = get_term_by('slug', $chuyen_mon, $_tax);
        if ($t && !is_wp_error($t)) {
            $chuyen_mon_label = $t->name;
            break;
        }
    }
}

get_header();
?>

<section class="sec-search-results section-pd">
    <div class="container">

        <!-- Search bar (pre-filled) -->
        <div class="sr-search-wrap">
            <?php get_template_part('partials/components/search-booking'); ?>
        </div>

        <!-- Active filters -->
        <?php
        $active_tags = [];
        if (!empty($loai_hinh) && isset($label_map[$loai_hinh]))
            $active_tags[] = $label_map[$loai_hinh];
        if (!empty($chuyen_mon_label))
            $active_tags[] = $chuyen_mon_label;
        if (!empty($thoi_gian) && isset($time_label_map[$thoi_gian]))
            $active_tags[] = $time_label_map[$thoi_gian];
        elseif (!empty($ngay))
            $active_tags[] = date_i18n('j/m/Y', strtotime($ngay));
        if ($muc_gia !== '' && isset($price_ranges[$muc_gia]))
            $active_tags[] = $price_ranges[$muc_gia]['label'];
        ?>
        <div class="sr-meta">
            <p class="sr-count">
                <strong><?php echo count($results); ?></strong> <?php esc_html_e('kết quả', 'monamedia'); ?><?php if (!empty($active_tags)) echo ' ' . __('cho', 'monamedia') . ' ' . implode(' · ', $active_tags); ?>
            </p>
        </div>

        <!-- Results -->
        <?php if (!empty($results)) : ?>
            <div class="row">
                <?php foreach ($results as $r) : ?>
                    <div class="col col-5i max-lg:!w-1/2 max-sm:!w-full">
                        <?php
                        $card = match ($r['type']) {
                            'dich_vu'  => 'card-dich-vu',
                            'khoa_hoc' => 'card-khoa-hoc',
                            'workshop' => 'card-workshop',
                        };
                        get_template_part('partials/components/' . $card, null, ['item' => $r['item']]);
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="sr-empty justify-center min-h-[60vh]">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.5" opacity="0.3" />
                    <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.3" />
                </svg>
                <p class="sr-empty__title"><?php esc_html_e('Không tìm thấy kết quả', 'monamedia'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>