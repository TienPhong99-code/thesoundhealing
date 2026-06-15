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
$nguoi_lon = max(0, (int) ($_GET['nguoi-lon'] ?? 0));
$tre_em    = max(0, (int) ($_GET['tre-em']    ?? 0));
$em_be     = max(0, (int) ($_GET['em-be']     ?? 0));
$tong_khach = $nguoi_lon + $tre_em + $em_be;

// ── Map loai-hinh → post_type ─────────────────────────────────────────────
$pt_map = [
    'dich-vu'  => 'dich_vu',
    'khoa-hoc' => 'khoa_hoc',
    'workshop' => 'workshop',
];

$post_types = !empty($loai_hinh) && isset($pt_map[$loai_hinh])
    ? [$pt_map[$loai_hinh]]
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
    }
} elseif (!empty($ngay)) {
    $d = DateTime::createFromFormat('Y-m-d', $ngay);
    if ($d) {
        $date_from = $date_to = $d->format('Ymd');
    }
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
        'status'     => get_field('kh_status',       $post->ID) ?: '',
        'price'      => get_field('price',           $post->ID),
        'spots'      => get_field('kh_spots',        $post->ID),
        'url'        => get_permalink($post->ID),
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
        'status'   => get_field('ws_status',    $post->ID) ?: 'open',
        'date'     => get_field('ws_date',      $post->ID),
        'time'     => get_field('ws_time',      $post->ID),
        'location'   => get_field('ws_location',     $post->ID),
        'duration'   => get_field('ws_duration',     $post->ID),
        'instructor' => get_field('ws_instructor_name', $post->ID),
        'desc'       => get_field('ws_short_desc',   $post->ID),
        'price'      => get_field('ws_price',        $post->ID),
        'spots'      => get_field('ws_spots',        $post->ID),
        'url'      => get_permalink($post->ID),
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

    // Lọc theo ngày (ACF lưu dạng Ymd)
    if (!empty($date_from) && in_array($pt, ['workshop', 'khoa_hoc'], true)) {
        $date_field = ($pt === 'workshop') ? 'ws_date' : 'start_date';
        $query_args['meta_query'] = [
            [
                'key'     => $date_field,
                'value'   => [$date_from, $date_to],
                'compare' => 'BETWEEN',
                'type'    => 'CHAR',
            ],
        ];
    }

    // Lọc theo taxonomy term (chuyen-mon)
    if (!empty($chuyen_mon)) {
        $tax_map = [
            'dich_vu'  => 'loai_dich_vu',
            'khoa_hoc' => 'bo_mon_khoa_hoc',
            'workshop' => 'loai_workshop',
        ];
        if (isset($tax_map[$pt])) {
            $tax_clause = ['taxonomy' => $tax_map[$pt], 'field' => 'slug', 'terms' => $chuyen_mon];
            if (!empty($query_args['tax_query'])) {
                $query_args['tax_query'][] = $tax_clause;
            } else {
                $query_args['tax_query'] = [$tax_clause];
            }
        }
    }

    // Lọc theo số khách (dựa trên spots)
    if ($tong_khach > 0) {
        $spots_key = ($pt === 'dich_vu') ? 'dv_spots' : (($pt === 'khoa_hoc') ? 'kh_spots' : 'ws_spots');
        $spots_clause = [
            'key'     => $spots_key,
            'value'   => $tong_khach,
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
        if (!empty($query_args['meta_query'])) {
            $query_args['meta_query'][] = $spots_clause;
        } else {
            $query_args['meta_query'] = [$spots_clause];
        }
    }

    $q = new WP_Query($query_args);
    if ($q->have_posts()) {
        while ($q->have_posts()) {
            $q->the_post();
            $post = get_post();
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
$label_map      = ['dich-vu' => 'Dịch vụ', 'khoa-hoc' => 'Khóa học', 'workshop' => 'Workshop'];
$time_label_map = ['today'   => 'Hôm nay', 'tomorrow' => 'Ngày mai', 'weekend'  => 'Cuối tuần này'];

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
        if ($tong_khach > 0)
            $active_tags[] = $tong_khach . ' khách';
        ?>
        <div class="sr-meta">
            <p class="sr-count">
                <strong><?php echo count($results); ?></strong> kết quả<?php if (!empty($active_tags)) echo ' cho ' . implode(' · ', $active_tags); ?>
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
            <div class="sr-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="1.5" opacity="0.3" />
                    <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" opacity="0.3" />
                </svg>
                <p class="sr-empty__title">Không tìm thấy kết quả</p>
                <p class="sr-empty__desc">Hãy thử thay đổi bộ lọc hoặc xem tất cả dịch vụ của chúng tôi.</p>
                <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-pri">Xem tất cả</a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>