<?php

defined('ABSPATH') || exit;

/**
 * Số liệu đặt cọc 50% của đơn. Trả null nếu đơn thanh toán đủ 100%
 * (hoặc đơn cọc nhưng không còn gì để thu → không có gì phải nhắc).
 *
 * Meta được ghi lúc tạo đơn: WooCommerceHook::save_payment_type_meta().
 * Đơn cũ có thể thiếu _tsh_full_amount → suy ra từ deposit + remaining.
 *
 * @return array{full: float, deposit: float, remaining: float}|null
 */
function tsh_deposit_info(\WC_Order $order): ?array
{
    if ($order->get_meta('_tsh_payment_type') !== 'deposit') return null;

    $deposit   = (float) $order->get_meta('_tsh_deposit_amount');
    $remaining = (float) $order->get_meta('_tsh_remaining_amount');
    if ($remaining <= 0) return null;

    $full = (float) $order->get_meta('_tsh_full_amount');
    if ($full <= 0) $full = $deposit + $remaining;

    return ['full' => $full, 'deposit' => $deposit, 'remaining' => $remaining];
}

/**
 * Các dòng thanh toán của đơn cọc, thay cho dòng "Tổng thanh toán" trong
 * bảng chi tiết email. Không echo gì nếu đơn thanh toán 100%.
 *
 * Style bám theo bảng sẵn có trong woocommerce/emails/*.php.
 */
function tsh_email_deposit_rows(\WC_Order $order): void
{
    $info = tsh_deposit_info($order);
    if (!$info) return;

    $label = 'padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa';
    $value = 'padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19';
    $money = 'padding:8px 0;border-bottom:1px solid #f0ede6;font-size:14px;font-weight:700;color:#c2a056';
?>
    <tr>
        <td style="<?php echo esc_attr($label); ?>"><?php esc_html_e('Hình thức thanh toán', 'monamedia'); ?></td>
        <td style="<?php echo esc_attr($value); ?>"><?php esc_html_e('Đặt cọc 50%', 'monamedia'); ?></td>
    </tr>
    <tr>
        <td style="<?php echo esc_attr($label); ?>"><?php esc_html_e('Tổng giá trị dịch vụ', 'monamedia'); ?></td>
        <td style="<?php echo esc_attr($value); ?>"><?php echo wp_kses_post(wc_price($info['full'])); ?></td>
    </tr>
    <tr>
        <td style="<?php echo esc_attr($label); ?>"><?php esc_html_e('Đã đặt cọc', 'monamedia'); ?></td>
        <td style="<?php echo esc_attr($money); ?>"><?php echo wp_kses_post(wc_price($info['deposit'])); ?></td>
    </tr>
    <tr>
        <td style="<?php echo esc_attr($label); ?>"><?php esc_html_e('Còn lại thu tại cơ sở', 'monamedia'); ?></td>
        <td style="<?php echo esc_attr($value); ?>"><?php echo wp_kses_post(wc_price($info['remaining'])); ?></td>
    </tr>
<?php
}

/**
 * Hộp lưu ý cọc trong email. Không echo gì nếu đơn thanh toán 100%.
 * $for_admin: đổi wording sang nhắc việc thu tiền thay vì thông báo cho khách.
 */
function tsh_email_deposit_box(\WC_Order $order, bool $for_admin = false): void
{
    $info = tsh_deposit_info($order);
    if (!$info) return;

    $deposit   = wc_price($info['deposit']);
    $remaining = wc_price($info['remaining']);

    // Số tiền chèn vào giữa câu → không dùng printf (bản dịch WPML từng làm hỏng %s
    // gây fatal ở PHP 8). Ghép chuỗi thẳng, phần dịch không chứa placeholder.
    if ($for_admin) {
        $msg = '<strong>' . esc_html__('Đơn đặt cọc', 'monamedia') . '</strong> — '
            . esc_html__('cần thu thêm', 'monamedia') . ' <strong>' . wp_kses_post($remaining) . '</strong> '
            . esc_html__('tại cơ sở khi khách tham gia.', 'monamedia');
    } else {
        $msg = esc_html__('Bạn đã đặt cọc', 'monamedia') . ' <strong>' . wp_kses_post($deposit) . '</strong>. '
            . esc_html__('Số tiền còn lại', 'monamedia') . ' <strong>' . wp_kses_post($remaining) . '</strong> '
            . esc_html__('sẽ được thu tại cơ sở khi bạn tham gia.', 'monamedia');
    }
?>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px">
        <tr>
            <td style="background:#fff8e1;border-left:3px solid #c2a056;border-radius:4px;padding:12px 16px">
                <p style="margin:0;font-size:12px;color:#6b5d3a;line-height:1.7">
                    &#9432;&nbsp; <?php echo wp_kses_post($msg); ?>
                </p>
            </td>
        </tr>
    </table>
<?php
}
