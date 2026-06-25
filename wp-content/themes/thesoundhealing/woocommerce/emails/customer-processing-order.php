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
    Xin chào <strong style="color:#1b1c19"><?php echo esc_html($name ?: 'Quý khách'); ?></strong>,
</p>
<p style="margin:0 0 28px;font-size:14px;color:#666;line-height:1.7">
    Chúng tôi đã nhận được lịch đặt dịch vụ của bạn và đang xử lý. Dưới đây là thông tin chi tiết đặt lịch của bạn.
</p>

<!-- Confirmation code box -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px">
    <tr>
        <td style="background:#faf8f4;border:1px solid #e8e4db;border-radius:8px;padding:16px 20px">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:#aaa">Mã xác nhận</p>
            <p style="margin:0;font-size:24px;font-weight:700;color:#c2a056;letter-spacing:1px">#<?php echo esc_html(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?></p>
        </td>
    </tr>
</table>

<?php if ($b_date || $b_time || $b_location) : ?>
<!-- Booking quick info -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;border:1px solid #e8e4db;border-radius:8px;overflow:hidden">
    <tr>
        <?php if ($b_date) : ?>
        <td width="33%" style="padding:14px 16px;border-right:1px solid #e8e4db;vertical-align:top">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#bbb">Ngày đặt</p>
            <p style="margin:0;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_date); ?></p>
        </td>
        <?php endif; ?>
        <?php if ($b_time) : ?>
        <td width="33%" style="padding:14px 16px;<?php echo $b_location ? 'border-right:1px solid #e8e4db;' : ''; ?>vertical-align:top">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#bbb">Khung giờ</p>
            <p style="margin:0;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_time); ?></p>
        </td>
        <?php endif; ?>
        <?php if ($b_location) : ?>
        <td width="34%" style="padding:14px 16px;vertical-align:top">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#bbb">Chi nhánh</p>
            <p style="margin:0;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_location); ?></p>
        </td>
        <?php endif; ?>
    </tr>
</table>
<?php endif; ?>

<!-- Divider -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px">
    <tr><td style="border-top:1px solid #edeae4;font-size:0;line-height:0">&nbsp;</td></tr>
</table>

<!-- Detail rows -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px">

    <tr>
        <td colspan="2" style="padding-bottom:10px">
            <p style="margin:0;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#bbb">Thông tin người đặt</p>
        </td>
    </tr>

    <?php if ($name) : ?>
    <tr>
        <td width="40%" style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Họ và tên</td>
        <td width="60%" style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($name); ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($phone) : ?>
    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Số điện thoại</td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($phone); ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($order->get_billing_email()) : ?>
    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Email</td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($order->get_billing_email()); ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($b_guests) : ?>
    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Số người tham gia</td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_guests . ' người'); ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($b_children) : ?>
    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Trẻ em tham gia</td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_children); ?></td>
    </tr>
    <?php endif; ?>

    <?php if ($b_instructor) : ?>
    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Người hướng dẫn</td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($b_instructor); ?></td>
    </tr>
    <?php endif; ?>

    <!-- Service + Payment -->
    <tr>
        <td colspan="2" style="padding:20px 0 10px">
            <p style="margin:0;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#bbb">Dịch vụ & Thanh toán</p>
        </td>
    </tr>

    <?php if ($service_name) : ?>
    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Dịch vụ</td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($service_name); ?></td>
    </tr>
    <?php endif; ?>

    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa">Tổng thanh toán</td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:14px;font-weight:700;color:#c2a056"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
    </tr>

    <tr>
        <td style="padding:8px 0;font-size:12px;color:#aaa">Phương thức thanh toán</td>
        <td style="padding:8px 0;font-size:13px;font-weight:600;color:#1b1c19"><?php echo esc_html($order->get_payment_method_title()); ?></td>
    </tr>

</table>

<!-- Notice -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px">
    <tr>
        <td style="background:#fdf9f0;border-left:3px solid #c2a056;border-radius:4px;padding:12px 16px">
            <p style="margin:0;font-size:12px;color:#888;line-height:1.7">
                &#9432;&nbsp; Chúng tôi sẽ liên hệ xác nhận lịch hẹn sớm nhất. Quý khách vui lòng đến sớm 10 phút để được phục vụ tốt nhất.
            </p>
        </td>
    </tr>
</table>

<?php do_action('woocommerce_email_footer', $email); ?>
