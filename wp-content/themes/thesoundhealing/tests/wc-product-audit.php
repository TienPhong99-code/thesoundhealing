<?php

/**
 * CÔNG CỤ KIỂM TRA SẢN PHẨM WOO CHO CPT (khoa_hoc / workshop / dich_vu)
 * ---------------------------------------------------------------------
 * Dùng khi: clone bài (khóa học / workshop / dịch vụ) nhưng chưa có sản phẩm
 * WooCommerce → nút Đặt lịch không add-to-cart được.
 *
 * Liên kết bài ↔ product qua post meta `_wc_product_id` (xem
 * inc/woocommerce/WcProductSync.php). Product chỉ được tạo khi Lưu bài trong
 * admin (hook acf/save_post). Bài clone thường KHÔNG copy meta → thiếu product.
 *
 * CÁCH DÙNG (chỉ admin đăng nhập mới chạy được):
 *   1. Upload file này lên host (đã nằm trong theme: tests/wc-product-audit.php).
 *   2. Đăng nhập wp-admin.
 *   3. Mở:  https://<domain>/wp-content/themes/thesoundhealing/tests/wc-product-audit.php
 *      - Mặc định: chỉ XEM báo cáo (không đổi gì).
 *      - Thêm ?run=create : tạo product cho các bài THIẾU/HỎNG product.
 *      - Thêm ?run=split  : tách product cho các bài đang DÙNG CHUNG product (do clone copy meta).
 *   4. XONG THÌ XOÁ FILE NÀY khỏi host (bảo mật).
 */

// --- Bootstrap WordPress ------------------------------------------------------
$wp_load = __DIR__ . '/../../../../wp-load.php'; // tests/ -> theme -> themes -> wp-content -> ROOT
if (!file_exists($wp_load)) {
    // Fallback: dò ngược tối đa 6 cấp tìm wp-load.php
    $dir = __DIR__;
    for ($i = 0; $i < 6; $i++) {
        $dir = dirname($dir);
        if (file_exists($dir . '/wp-load.php')) {
            $wp_load = $dir . '/wp-load.php';
            break;
        }
    }
}
require $wp_load;

// --- Chặn quyền: chỉ admin -----------------------------------------------------
if (!current_user_can('manage_options')) {
    wp_die('Bạn cần đăng nhập tài khoản quản trị (manage_options) để dùng công cụ này.');
}
if (!function_exists('wc_get_product')) {
    wp_die('WooCommerce chưa được kích hoạt.');
}

$POST_TYPES   = ['khoa_hoc', 'workshop', 'dich_vu'];
$PRICE_FIELDS = ['khoa_hoc' => 'price', 'workshop' => 'ws_price', 'dich_vu' => 'dv_price'];
$TYPE_LABEL   = ['khoa_hoc' => 'Khóa học', 'workshop' => 'Workshop', 'dich_vu' => 'Dịch vụ'];
$META_KEY     = '_wc_product_id';
$run          = isset($_GET['run']) ? sanitize_text_field($_GET['run']) : '';

// parse_price giống hệt theme (nếu class đã load thì dùng luôn)
$parse_price = function (string $raw): float {
    if (class_exists('TSH_WC_Product_Sync')) return TSH_WC_Product_Sync::parse_price($raw);
    if (empty($raw) || mb_stripos($raw, 'liên hệ') !== false) return 0.0;
    $d = preg_replace('/[^\d]/', '', $raw);
    return $d !== '' ? (float) $d : 0.0;
};

// --- Lấy toàn bộ bài ----------------------------------------------------------
$ids = get_posts([
    'post_type'      => $POST_TYPES,
    'post_status'    => ['publish', 'draft', 'pending', 'private', 'future'],
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'orderby'        => 'ID',
    'order'          => 'ASC',
    'suppress_filters' => true,
]);

// Map product_id -> danh sách post để phát hiện DÙNG CHUNG (clone copy meta)
$product_usage = [];
foreach ($ids as $id) {
    $wc = (int) get_post_meta($id, $META_KEY, true);
    if ($wc) $product_usage[$wc][] = $id;
}

$wpml_on = (bool) apply_filters('wpml_default_language', null);
$get_lang = function ($id, $ptype) use ($wpml_on) {
    if (!$wpml_on) return '';
    return (string) apply_filters('wpml_element_language_code', null, [
        'element_id'   => $id,
        'element_type' => 'post_' . $ptype,
    ]);
};

// --- Phân loại từng bài -------------------------------------------------------
// Trả về: status, note, wc_id, has_product, price
$rows = [];
foreach ($ids as $id) {
    $post   = get_post($id);
    $ptype  = $post->post_type;
    $field  = $PRICE_FIELDS[$ptype] ?? 'price';
    $price_raw = (string) (get_field($field, $id) ?: '');
    $price  = $parse_price($price_raw);
    $wc_id  = (int) get_post_meta($id, $META_KEY, true);

    $product     = $wc_id ? wc_get_product($wc_id) : null;
    $has_product = $product instanceof WC_Product;
    $shared      = $wc_id && isset($product_usage[$wc_id]) && count($product_usage[$wc_id]) > 1;

    if (!$wc_id) {
        $status = 'MISSING';   // chưa có meta -> chưa từng có product
        $note   = 'Chưa có sản phẩm Woo → KHÔNG đặt lịch được';
    } elseif (!$has_product) {
        $status = 'DANGLING';  // meta trỏ product đã bị xoá
        $note   = "Meta trỏ product #$wc_id nhưng product không tồn tại → KHÔNG đặt lịch được";
    } elseif ($shared) {
        $status = 'SHARED';    // nhiều bài dùng chung 1 product (clone copy meta)
        $others = array_diff($product_usage[$wc_id], [$id]);
        $note   = "Dùng CHUNG product #$wc_id với bài: " . implode(', ', $others) . ' → dễ ghi đè tên/giá lẫn nhau';
    } elseif ($price <= 0) {
        $status = 'CONTACT';   // có product nhưng giá 0/Liên hệ -> theme ẩn nút mua
        $note   = 'Có product nhưng giá = 0 / "Liên hệ" → theme ẩn nút mua (theo thiết kế)';
    } else {
        $prod_status = $product->get_status();
        if ($post->post_status === 'publish' && $prod_status !== 'publish') {
            $status = 'PROD_DRAFT';
            $note   = "Bài đã publish nhưng product #$wc_id đang '$prod_status' → checkout có thể lỗi";
        } else {
            $status = 'OK';
            $note   = "OK (product #$wc_id)";
        }
    }

    $rows[] = [
        'id' => $id,
        'title' => $post->post_title,
        'ptype' => $ptype,
        'post_status' => $post->post_status,
        'lang' => $get_lang($id, $ptype),
        'wc_id' => $wc_id,
        'price_raw' => $price_raw,
        'price' => $price,
        'status' => $status,
        'note' => $note,
        'shared' => $shared,
    ];
}

// --- HÀNH ĐỘNG FIX ------------------------------------------------------------
$log = [];
if ($run === 'create' || $run === 'split') {
    if (!class_exists('TSH_WC_Product_Sync')) {
        $log[] = ['err', 'Không tìm thấy class TSH_WC_Product_Sync — theme chưa load. Không thể fix.'];
    } else {
        $sync = new TSH_WC_Product_Sync();

        if ($run === 'create') {
            // Tạo product cho bài MISSING / DANGLING
            foreach ($rows as $r) {
                if (!in_array($r['status'], ['MISSING', 'DANGLING'], true)) continue;
                if ($r['status'] === 'DANGLING') delete_post_meta($r['id'], $META_KEY);
                $sync->sync($r['id']);
                $new = (int) get_post_meta($r['id'], $META_KEY, true);
                $log[] = $new
                    ? ['ok', "#{$r['id']} “{$r['title']}” → tạo product #$new"]
                    : ['err', "#{$r['id']} “{$r['title']}” → tạo product THẤT BẠI (kiểm tra giá?)"];
            }
        }

        if ($run === 'split') {
            // Tách product cho các nhóm dùng chung — AN TOÀN VỚI WPML.
            // Quy tắc GIỮ: product ở lại với bài CÙNG NGÔN NGỮ product (ID nhỏ nhất);
            // các bài còn lại xoá meta rồi sync để sync() tạo product đúng ngôn ngữ
            // (bản dịch được link vào trid của product bài gốc).
            $default_lang = $wpml_on ? (string) apply_filters('wpml_default_language', null) : '';
            $to_fix = []; // [post_id => post_lang] các bài cần tách

            foreach ($product_usage as $pid => $post_ids) {
                if (count($post_ids) < 2) continue;
                $prod_lang = $wpml_on
                    ? (string) apply_filters('wpml_element_language_code', null, ['element_id' => $pid, 'element_type' => 'post_product'])
                    : '';
                // Ứng viên giữ = bài cùng ngôn ngữ với product, ID nhỏ nhất.
                $match = [];
                foreach ($post_ids as $x) {
                    $xl = $wpml_on ? (string) apply_filters('wpml_element_language_code', null, ['element_id' => $x, 'element_type' => 'post_' . get_post_type($x)]) : '';
                    if (!$wpml_on || $xl === $prod_lang) $match[] = $x;
                }
                sort($match);
                sort($post_ids);
                $keep = $match ? $match[0] : $post_ids[0];
                $others = array_values(array_diff($post_ids, [$keep]));
                $log[] = ['ok', "Product #$pid (" . ($prod_lang ?: 'x') . "): giữ cho bài #$keep, tách " . count($others) . " bài"];
                foreach ($others as $o) {
                    $to_fix[$o] = $wpml_on ? (string) apply_filters('wpml_element_language_code', null, ['element_id' => $o, 'element_type' => 'post_' . get_post_type($o)]) : '';
                }
            }

            // Xử lý bài ngôn ngữ MẶC ĐỊNH trước để bản dịch link đúng product gốc.
            uksort($to_fix, function ($a, $b) use ($to_fix, $default_lang) {
                $pa = ($to_fix[$a] === $default_lang || $to_fix[$a] === '') ? 0 : 1;
                $pb = ($to_fix[$b] === $default_lang || $to_fix[$b] === '') ? 0 : 1;
                return $pa <=> $pb ?: $a <=> $b;
            });
            foreach ($to_fix as $o => $ol) {
                delete_post_meta($o, $META_KEY);
                $sync->sync($o);
                $new = (int) get_post_meta($o, $META_KEY, true);
                $log[] = $new
                    ? ['ok', "  #$o (" . ($ol ?: 'x') . ") → product riêng #$new"]
                    : ['err', "  #$o → tách THẤT BẠI (kiểm tra giá?)"];
            }
            if (empty($to_fix)) $log[] = ['ok', 'Không có bài nào dùng chung product.'];
        }
    }
}

// --- Thống kê -----------------------------------------------------------------
$counts = [];
foreach ($rows as $r) $counts[$r['status']] = ($counts[$r['status']] ?? 0) + 1;

$badge = [
    'OK'         => ['#e6f4ea', '#137333', 'OK'],
    'MISSING'    => ['#fce8e6', '#c5221f', 'THIẾU PRODUCT'],
    'DANGLING'   => ['#fce8e6', '#c5221f', 'PRODUCT ĐÃ XOÁ'],
    'SHARED'     => ['#fef7e0', '#b06000', 'DÙNG CHUNG'],
    'PROD_DRAFT' => ['#fef7e0', '#b06000', 'PRODUCT NHÁP'],
    'CONTACT'    => ['#e8f0fe', '#1967d2', 'GIÁ LIÊN HỆ'],
];
$esc = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kiểm tra sản phẩm Woo — CPT</title>
    <style>
        body {
            font: 14px/1.5 -apple-system, Segoe UI, Roboto, sans-serif;
            margin: 0;
            background: #f6f7f9;
            color: #202124
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px
        }

        h1 {
            font-size: 20px;
            margin: 0 0 4px
        }

        .sub {
            color: #5f6368;
            margin: 0 0 20px
        }

        .cards {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px
        }

        .card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 16px;
            min-width: 120px
        }

        .card b {
            font-size: 22px;
            display: block
        }

        .actions {
            margin: 16px 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap
        }

        a.btn {
            display: inline-block;
            padding: 9px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600
        }

        .btn-primary {
            background: #1a73e8;
            color: #fff
        }

        .btn-warn {
            background: #b06000;
            color: #fff
        }

        .btn-ghost {
            background: #fff;
            border: 1px solid #dadce0;
            color: #202124
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .06)
        }

        th,
        td {
            padding: 9px 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: top
        }

        th {
            background: #f1f3f4;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #5f6368
        }

        tr:hover td {
            background: #fafbfc
        }

        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap
        }

        .note {
            color: #5f6368;
            font-size: 13px
        }

        .log {
            background: #202124;
            color: #e8eaed;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            font: 13px/1.6 SFMono-Regular, Menlo, monospace;
            white-space: pre-wrap
        }

        .log .ok::before {
            content: "✔ ";
            color: #81c995
        }

        .log .err::before {
            content: "✖ ";
            color: #f28b82
        }

        code {
            background: #eef;
            padding: 1px 5px;
            border-radius: 4px
        }

        .muted {
            color: #9aa0a6
        }
    </style>
</head>

<body>
    <div class="wrap">
        <h1>Kiểm tra sản phẩm WooCommerce cho Khóa học / Workshop / Dịch vụ</h1>
        <p class="sub">Liên kết qua meta <code>_wc_product_id</code>. Bài THIẾU/PRODUCT ĐÃ XOÁ = nút Đặt lịch không add-to-cart được.</p>

        <?php if ($log): ?>
            <div class="log"><?php foreach ($log as $l) echo '<div class="' . $l[0] . '">' . $esc($l[1]) . "</div>"; ?></div>
        <?php endif; ?>

        <div class="cards">
            <div class="card"><b><?= count($rows) ?></b>Tổng bài</div>
            <div class="card" style="border-color:#137333"><b style="color:#137333"><?= $counts['OK'] ?? 0 ?></b>OK</div>
            <div class="card" style="border-color:#c5221f"><b style="color:#c5221f"><?= ($counts['MISSING'] ?? 0) + ($counts['DANGLING'] ?? 0) ?></b>Thiếu product</div>
            <div class="card" style="border-color:#b06000"><b style="color:#b06000"><?= $counts['SHARED'] ?? 0 ?></b>Dùng chung</div>
            <div class="card" style="border-color:#b06000"><b style="color:#b06000"><?= $counts['PROD_DRAFT'] ?? 0 ?></b>Product nháp</div>
            <div class="card" style="border-color:#1967d2"><b style="color:#1967d2"><?= $counts['CONTACT'] ?? 0 ?></b>Giá liên hệ</div>
        </div>

        <div class="actions">
            <a class="btn btn-ghost" href="?">↻ Chỉ xem lại (không sửa)</a>
            <?php if (($counts['MISSING'] ?? 0) + ($counts['DANGLING'] ?? 0) > 0): ?>
                <a class="btn btn-primary" href="?run=create" onclick="return confirm('Tạo sản phẩm Woo cho các bài THIẾU/PRODUCT ĐÃ XOÁ?')">➕ Tạo product cho bài thiếu (<?= ($counts['MISSING'] ?? 0) + ($counts['DANGLING'] ?? 0) ?>)</a>
            <?php endif; ?>
            <?php if (($counts['SHARED'] ?? 0) > 0): ?>
                <a class="btn btn-warn" href="?run=split" onclick="return confirm('Tách product riêng cho các bài đang DÙNG CHUNG?\n\nGiữ product cho bài cùng ngôn ngữ (ID nhỏ nhất), các bài còn lại tạo product riêng đúng ngôn ngữ. Không mất cấu hình eticket_days (nằm trên bài).')">✂ Tách product dùng chung (<?= $counts['SHARED'] ?>)</a>
            <?php endif; ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Loại</th><?php if ($wpml_on): ?><th>Lang</th><?php endif; ?><th>Tiêu đề</th>
                    <th>Product</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r):
                    $b = $badge[$r['status']] ?? ['#eee', '#333', $r['status']]; ?>
                    <tr>
                        <td><a href="<?= $esc(get_edit_post_link($r['id'])) ?>" target="_blank">#<?= $r['id'] ?></a></td>
                        <td><?= $esc($TYPE_LABEL[$r['ptype']] ?? $r['ptype']) ?></td>
                        <?php if ($wpml_on): ?><td><?= $esc($r['lang'] ?: '—') ?></td><?php endif; ?>
                        <td><?= $esc($r['title']) ?><?php if ($r['post_status'] !== 'publish'): ?> <span class="muted">(<?= $esc($r['post_status']) ?>)</span><?php endif; ?></td>
                        <td><?php if ($r['wc_id']): ?><a href="<?= $esc(get_edit_post_link($r['wc_id'])) ?>" target="_blank">#<?= $r['wc_id'] ?></a><?php else: ?><span class="muted">—</span><?php endif; ?></td>
                        <td><?= $esc($r['price_raw'] ?: '—') ?></td>
                        <td><span class="tag" style="background:<?= $b[0] ?>;color:<?= $b[1] ?>"><?= $esc($b[2]) ?></span></td>
                        <td class="note"><?= $esc($r['note']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="sub" style="margin-top:20px">⚠️ <b>Xoá file này khỏi host sau khi dùng xong</b> để tránh lộ công cụ. Nếu host bật OPcache (validate_timestamps=0) thì reset OPcache sau khi xoá.</p>
    </div>
</body>

</html>