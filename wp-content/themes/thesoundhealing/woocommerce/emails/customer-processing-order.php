<?php
defined('ABSPATH') || exit;

$b_date       = $order->get_meta('_booking_date');
$b_time       = $order->get_meta('_booking_time');
$b_location   = $order->get_meta('_booking_location');
$b_guests     = $order->get_meta('_booking_guests');
$b_instructor = $order->get_meta('_booking_instructor');
$b_children   = $order->get_meta('_booking_children');

$order_id   = $order->get_id();
$name       = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
$phone      = $order->get_billing_phone();
$date_obj   = $order->get_date_created();
$order_date = $date_obj ? $date_obj->date_i18n('d/m/Y') : '';

$items = $order->get_items();
$first_item   = !empty($items) ? reset($items) : null;
$service_name = $first_item ? $first_item->get_name() : '';

do_action('woocommerce_email_header', $email_heading, $email);
?>

<!-- Greeting -->
<p style="margin:0 0 20px;font-size:15px;color:#444;line-height:1.6">
    <?php esc_html_e('Xin chào', 'monamedia'); ?> <strong style="color:#1b1c19"><?php echo esc_html($name ?: esc_html__('Quý khách', 'monamedia')); ?></strong>,
</p>
<p style="margin:0 0 28px;font-size:14px;color:#666;line-height:1.7">
    <?php esc_html_e('Chúng tôi đã nhận được lịch đặt dịch vụ của bạn và đang xử lý. Dưới đây là thông tin chi tiết đặt lịch của bạn.', 'monamedia'); ?>
</p>

<!-- Confirmation code box -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px">
    <tr>
        <td style="background:#faf8f4;border:1px solid #e8e4db;border-radius:8px;padding:16px 20px">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#aaa"><?php esc_html_e('Mã xác nhận', 'monamedia'); ?></p>
            <p style="margin:0;font-size:24px;font-weight:700;color:#c2a056;letter-spacing:1px">#<?php echo esc_html(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?></p>
        </td>
    </tr>
</table>

<?php if ($b_date || $b_time || $b_location) :
    // Ngày đặt | Khung giờ = 50/50 (giá trị ngắn, vừa mobile); Chi nhánh full 100%
    // xuống hàng dưới (giá trị dài). Không dùng media query → chuẩn mọi client.
    $q_inner = function (string $label, string $value): string {
        return '<p style="margin:0 0 3px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#bbb">' . esc_html($label) . '</p>'
            . '<p style="margin:0;font-size:14px;font-weight:600;color:#1b1c19">' . esc_html($value) . '</p>';
    };
    // Viền dưới hàng ngày/giờ chỉ khi còn hàng chi nhánh phía dưới.
    $dt_bb = $b_location ? 'border-bottom:1px solid #e8e4db;' : '';
?>
    <!-- Booking quick info: ngày/giờ 50-50, chi nhánh full width -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;border:1px solid #e8e4db;border-radius:8px;overflow:hidden">
        <?php if ($b_date && $b_time) : ?>
            <tr>
                <td width="50%" style="padding:12px 16px;border-right:1px solid #e8e4db;<?php echo $dt_bb; ?>vertical-align:top"><?php echo $q_inner(__('Ngày đặt', 'monamedia'), $b_date); ?></td>
                <td width="50%" style="padding:12px 16px;<?php echo $dt_bb; ?>vertical-align:top"><?php echo $q_inner(__('Khung giờ', 'monamedia'), $b_time); ?></td>
            </tr>
        <?php elseif ($b_date || $b_time) : ?>
            <tr>
                <td colspan="2" style="padding:12px 16px;<?php echo $dt_bb; ?>vertical-align:top"><?php echo $q_inner($b_date ? __('Ngày đặt', 'monamedia') : __('Khung giờ', 'monamedia'), $b_date ?: $b_time); ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($b_location) : ?>
            <tr>
                <td colspan="2" style="padding:12px 16px;vertical-align:top"><?php echo $q_inner(__('Chi nhánh', 'monamedia'), $b_location); ?></td>
            </tr>
        <?php endif; ?>
    </table>
<?php endif; ?>

<!-- Divider -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px">
    <tr>
        <td style="border-top:1px solid #edeae4;font-size:0;line-height:0">&nbsp;</td>
    </tr>
</table>

<!-- Detail rows -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px">

    <tr>
        <td colspan="2" style="padding-bottom:10px">
            <p style="margin:0;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#bbb"><?php esc_html_e('Thông tin người đặt', 'monamedia'); ?></p>
        </td>
    </tr>

    <?php if ($name) : ?>
        <tr>
            <td width="40%" style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Họ và tên', 'monamedia'); ?></td>
            <td width="60%" style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($name); ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($phone) : ?>
        <tr>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Số điện thoại', 'monamedia'); ?></td>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($phone); ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($order->get_billing_email()) : ?>
        <tr>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Email', 'monamedia'); ?></td>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($order->get_billing_email()); ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($b_guests) : ?>
        <tr>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Số người tham gia', 'monamedia'); ?></td>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_guests) . ' ' . esc_html__('người', 'monamedia'); ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($b_children) : ?>
        <tr>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Trẻ em tham gia', 'monamedia'); ?></td>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_children); ?></td>
        </tr>
    <?php endif; ?>

    <?php if ($b_instructor) : ?>
        <tr>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Người hướng dẫn', 'monamedia'); ?></td>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_instructor); ?></td>
        </tr>
    <?php endif; ?>

    <!-- Service + Payment -->
    <tr>
        <td colspan="2" style="padding:20px 0 10px">
            <p style="margin:0;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#bbb"><?php esc_html_e('Dịch vụ & Thanh toán', 'monamedia'); ?></p>
        </td>
    </tr>

    <?php if ($service_name) : ?>
        <tr>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Dịch vụ', 'monamedia'); ?></td>
            <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($service_name); ?></td>
        </tr>
    <?php endif; ?>

    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Tổng thanh toán', 'monamedia'); ?></td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:14px;font-weight:700;color:#c2a056"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
    </tr>

    <tr>
        <td style="padding:8px 0;font-size:12px;color:#aaa"><?php esc_html_e('Phương thức thanh toán', 'monamedia'); ?></td>
        <td style="padding:8px 0;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($order->get_payment_method_title()); ?></td>
    </tr>

</table>

<!-- Notice -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px">
    <tr>
        <td style="background:#fdf9f0;border-left:3px solid #c2a056;border-radius:4px;padding:12px 16px">
            <p style="margin:0;font-size:12px;color:#888;line-height:1.7">
                &#9432;&nbsp; <?php esc_html_e('Chúng tôi sẽ liên hệ xác nhận lịch hẹn sớm nhất. Quý khách vui lòng đến sớm 15 phút để được phục vụ tốt nhất.', 'monamedia'); ?>
            </p>
        </td>
    </tr>
</table>

<?php do_action('woocommerce_email_footer', $email); ?>