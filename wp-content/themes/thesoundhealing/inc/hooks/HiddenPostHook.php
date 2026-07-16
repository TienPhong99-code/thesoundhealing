<?php

defined('ABSPATH') || exit;

/**
 * Ẩn bài khỏi danh sách (unlisted) — bật bằng field ACF `tsh_hidden` (inc/acf/HiddenPostACF.php).
 *
 * Bài ẩn biến mất khỏi mọi nơi liệt kê ở front-end nhưng link trực tiếp vẫn mở được,
 * dùng cho sự kiện riêng tư chỉ gửi link cho người được mời.
 *
 * Chặn tập trung tại pre_get_posts: cả WP_Query lẫn get_posts() đều chạy qua hook này,
 * nên một chỗ là phủ hết trang chủ, các section list, load-more AJAX, tìm kiếm, archive.
 *
 * Query nội bộ cần THẤY bài ẩn (vd tra ngược bài từ product WooCommerce lúc checkout)
 * thì truyền 'tsh_include_hidden' => true vào args.
 */

/** Các post type có công tắc ẩn. */
function tsh_hidden_post_types(): array
{
    return ['khoa_hoc', 'workshop', 'dich_vu'];
}

/** Bài này có đang bật ẩn không. */
function tsh_is_hidden(int $post_id): bool
{
    return (int) get_post_meta($post_id, 'tsh_hidden', true) === 1;
}

/** Đang ở trang chi tiết của một bài đang ẩn. */
function tsh_is_hidden_singular(): bool
{
    return is_singular(tsh_hidden_post_types()) && tsh_is_hidden(get_queried_object_id());
}

// ── Loại bài ẩn khỏi mọi query liệt kê ở front-end ────────────────────────
add_action('pre_get_posts', function (WP_Query $query) {

    // Admin phải thấy đủ bài để quản lý.
    if (is_admin()) return;

    // Trang chi tiết phải mở được — đây chính là cái làm nên "unlisted".
    if ($query->is_singular()) return;

    // Cờ thoát cho query nội bộ (xem WcProductSync / EticketFunction / WooCommerceHook).
    if ($query->get('tsh_include_hidden')) return;

    // Post type rõ ràng và không dính 3 CPT (product, shop_order, nav_menu_item…) → bỏ qua.
    // Nếu post_type rỗng/'any' thì vẫn lọc: tìm kiếm và archive taxonomy chưa gán post_type
    // tại thời điểm này, mà bỏ sót là bài ẩn lọt ra. Với post type khác thì meta_query bên
    // dưới vô hại — chúng không bao giờ có meta tsh_hidden nên rơi vào nhánh NOT EXISTS.
    $post_type = $query->get('post_type');
    if (!empty($post_type) && $post_type !== 'any') {
        if (empty(array_intersect((array) $post_type, tsh_hidden_post_types()))) return;
    }

    // Nhánh NOT EXISTS là bắt buộc: bài cũ chưa từng lưu meta này vẫn phải hiện.
    $meta_query   = $query->get('meta_query') ?: [];
    $meta_query[] = [
        'relation' => 'OR',
        ['key' => 'tsh_hidden', 'compare' => 'NOT EXISTS'],
        ['key' => 'tsh_hidden', 'value' => '1', 'compare' => '!='],
    ];
    $query->set('meta_query', $meta_query);
}, 10, 1);

// ── Chặn Google index bài ẩn ──────────────────────────────────────────────
// In thẳng thẻ robots vào <head>. Không dựa vào wp_robots/Yoast: site này thực tế KHÔNG
// xuất thẻ robots nào (đã kiểm tra trên production 14/07/2026 — trang chủ, dịch vụ,
// workshop đều trống), nên nếu chỉ lọc qua filter thì noindex sẽ chẳng bao giờ được in.
// Google lấy chỉ thị NGHIÊM NGẶT NHẤT khi gặp nhiều thẻ robots, nên kể cả sau này Yoast
// có in thêm "index" thì "noindex" ở đây vẫn thắng.
add_action('wp_head', function (): void {
    if (!tsh_is_hidden_singular()) return;

    echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
}, 1);

// Vẫn giữ 2 filter dưới đây để phòng trường hợp Yoast được bật lại thẻ robots — khi đó
// chúng sửa luôn thẻ của Yoast thay vì để nó mâu thuẫn với thẻ mình in ở trên.
add_filter('wp_robots', function (array $robots): array {
    if (!tsh_is_hidden_singular()) return $robots;

    unset($robots['index'], $robots['follow']);
    $robots['noindex']  = true;
    $robots['nofollow'] = true;

    return $robots;
}, 100);

add_filter('wpseo_robots_array', function ($robots) {
    if (!is_array($robots) || !tsh_is_hidden_singular()) return $robots;

    $robots['index']  = 'noindex';
    $robots['follow'] = 'nofollow';

    return $robots;
}, 100);

// ── Loại bài ẩn khỏi sitemap Yoast ────────────────────────────────────────
add_filter('wpseo_exclude_from_sitemap_by_post_ids', function ($excluded) {
    static $hidden_ids = null;

    if ($hidden_ids === null) {
        $hidden_ids = get_posts([
            'post_type'         => tsh_hidden_post_types(),
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            'fields'            => 'ids',
            'no_found_rows'     => true,
            'tsh_include_hidden' => true, // nếu không có cờ này thì chính query dưới đây bị lọc sạch
            'meta_query'        => [
                ['key' => 'tsh_hidden', 'value' => '1', 'compare' => '='],
            ],
        ]);
    }

    return array_merge((array) $excluded, $hidden_ids);
});

// ── Đánh dấu "Ẩn" trong danh sách bài ở admin ─────────────────────────────
add_filter('display_post_states', function (array $states, $post): array {
    if ($post instanceof WP_Post && in_array($post->post_type, tsh_hidden_post_types(), true) && tsh_is_hidden($post->ID)) {
        $states['tsh_hidden'] = __('Ẩn', 'monamedia');
    }

    return $states;
}, 10, 2);
