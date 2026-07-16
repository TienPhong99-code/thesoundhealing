<?php

defined('ABSPATH') || exit;

/**
 * Trả ngày hết hạn e-ticket (Y-m-d) cho đơn, tính on-demand nếu chưa lưu.
 * Trả '' nếu dịch vụ không phát hành e-ticket (eticket_days <= 0 / không tìm thấy).
 * $persist=false: chỉ tính, không lưu (dùng khi render admin để tránh save mid-render).
 */
function tsh_eticket_expiry(\WC_Order $order, bool $persist = true): string
{
    $existing = $order->get_meta('_tsh_eticket_expiry');
    if ($existing) return (string) $existing;

    $items = $order->get_items();
    $first = $items ? reset($items) : null;
    $pid   = $first ? (int) $first->get_product_id() : 0;
    if (!$pid) return '';

    $cpt = get_posts([
        'post_type'      => ['khoa_hoc', 'workshop', 'dich_vu'],
        'post_status'    => 'any',
        'meta_key'       => '_wc_product_id',
        'meta_value'     => $pid,
        'posts_per_page' => 1,
        'fields'         => 'ids',
        // Sự kiện ẩn vẫn bán vé được → tra ngược phải thấy cả bài đang ẩn (HiddenPostHook)
        'tsh_include_hidden' => true,
    ]);
    if (empty($cpt)) return '';

    $days = (int) get_post_meta($cpt[0], 'eticket_days', true);
    if ($days <= 0) return '';

    $created = $order->get_date_created();
    $base    = $created
        ? ($created->getTimestamp() + (int) round(((float) get_option('gmt_offset', 0)) * 3600))
        : current_time('timestamp');
    $expiry = gmdate('Y-m-d', $base + $days * DAY_IN_SECONDS);

    if ($persist) {
        $order->update_meta_data('_tsh_eticket_days', $days);
        $order->update_meta_data('_tsh_eticket_expiry', $expiry);
        $order->save();
    }
    return $expiry;
}
