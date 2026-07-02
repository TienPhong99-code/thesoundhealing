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
$email_addr = $order->get_billing_email();

$items        = $order->get_items();
$first_item   = !empty($items) ? reset($items) : null;
$service_name = $first_item ? $first_item->get_name() : '';

$admin_url = admin_url('post.php?post=' . $order_id . '&action=edit');

do_action('woocommerce_email_header', $email_heading, $email);
?>

<!-- Intro -->
<p style="margin:0 0 24px;font-size:14px;color:#444;line-height:1.7">
    <?php esc_html_e('Bạn có một lịch đặt mới từ', 'monamedia'); ?> <strong style="color:#1b1c19"><?php echo esc_html($name ?: esc_html__('Khách', 'monamedia')); ?></strong>.
    <a href="<?php echo esc_url($admin_url); ?>" style="color:#c2a056;font-weight:600;text-decoration:none"><?php esc_html_e('Xem đơn hàng', 'monamedia'); ?> #<?php echo esc_html(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?> &rarr;</a>
</p>

<?php if ($b_date || $b_time || $b_location) : ?>
<!-- Quick booking info -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;border:1px solid #e8e4db;border-radius:8px;overflow:hidden">
    <tr>
        <?php if ($b_date) : ?>
        <td width="33%" style="padding:14px 16px;border-right:1px solid #e8e4db;vertical-align:top">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#bbb"><?php esc_html_e('Ngày đặt', 'monamedia'); ?></p>
            <p style="margin:0;font-size:13px;font-weight:700;color:#1b1c19"><?php echo esc_html($b_date); ?></p>
        </td>
        <?php endif; ?>
        <?php if ($b_time) : ?>
        <td width="33%" style="padding:14px 16px;<?php echo $b_location ? 'border-right:1px solid #e8e4db;' : ''; ?>vertical-align:top">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#bbb"><?php esc_html_e('Khung giờ', 'monamedia'); ?></p>
            <p style="margin:0;font-size:13px;font-weight:700;color:#1b1c19"><?php echo esc_html($b_time); ?></p>
        </td>
        <?php endif; ?>
        <?php if ($b_location) : ?>
        <td width="34%" style="padding:14px 16px;vertical-align:top">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#bbb"><?php esc_html_e('Chi nhánh', 'monamedia'); ?></p>
            <p style="margin:0;font-size:13px;font-weight:700;color:#1b1c19"><?php echo esc_html($b_location); ?></p>
        </td>
        <?php endif; ?>
    </tr>
</table>
<?php endif; ?>

<!-- Detail rows -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px">

    <tr>
        <td colspan="2" style="padding-bottom:10px">
            <p style="margin:0;font-size:10px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#bbb"><?php esc_html_e('Thông tin khách', 'monamedia'); ?></p>
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
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19">
            <a href="tel:<?php echo esc_attr($phone); ?>" style="color:#1b1c19;text-decoration:none"><?php echo esc_html($phone); ?></a>
        </td>
    </tr>
    <?php endif; ?>

    <?php if ($email_addr) : ?>
    <tr>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:12px;color:#aaa"><?php esc_html_e('Email', 'monamedia'); ?></td>
        <td style="padding:8px 0;border-bottom:1px solid #f0ede6;font-size:13px;font-weight:600;color:#1b1c19">
            <a href="mailto:<?php echo esc_attr($email_addr); ?>" style="color:#c2a056;text-decoration:none"><?php echo esc_html($email_addr); ?></a>
        </td>
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

    <!-- Service & Payment -->
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

<?php do_action('woocommerce_email_footer', $email); ?>
