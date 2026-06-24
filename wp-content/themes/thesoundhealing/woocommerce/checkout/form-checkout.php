<?php
defined('ABSPATH') || exit;

$_token   = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
$_booking = $_token ? (array) get_transient('tsh_booking_' . $_token) : [];

$_name       = $_booking['fullname']   ?? '';
$_email      = $_booking['email']      ?? '';
$_phone      = $_booking['phone']      ?? '';
$_date       = $_booking['date']       ?? '';
$_time       = $_booking['time']       ?? '';
$_location   = $_booking['location']   ?? '';
$_guests     = $_booking['guests']     ?? '';
$_instructor = $_booking['instructor'] ?? '';
$_children   = $_booking['children']   ?? '';

$_parts     = explode(' ', trim($_name), 2);
$_first     = $_parts[0] ?? $_name;
$_last      = $_parts[1] ?? '';

// Danh sách field hiển thị (chỉ render dòng nào có giá trị)
$_info_rows = [
    ['label' => 'Họ và tên',           'value' => $_name],
    ['label' => 'Số điện thoại',       'value' => $_phone],
    ['label' => 'Email',               'value' => $_email],
    ['label' => 'Ngày đặt',            'value' => $_date],
    ['label' => 'Khung giờ',           'value' => $_time],
    ['label' => 'Chi nhánh',           'value' => $_location],
    ['label' => 'Số người tham gia',   'value' => $_guests ? $_guests . ' người' : ''],
    ['label' => 'Người hướng dẫn',     'value' => $_instructor],
    ['label' => 'Trẻ em tham gia',     'value' => $_children],
];
?>

<div class="tsh-checkout-wrap">
    <div class="container">

        <?php if (have_posts()) : while (have_posts()) : the_post(); endwhile; endif; ?>

        <?php if (empty($_booking)) : ?>
        <div class="tsh-co-empty">
            <p>Không tìm thấy thông tin đặt lịch. Vui lòng quay lại và điền form đặt lịch.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="tsh-co-back-btn">← Về trang chủ</a>
        </div>
        <?php else : ?>

        <form name="checkout" method="post"
              class="checkout woocommerce-checkout tsh-co-form"
              action="<?php echo esc_url(wc_get_checkout_url()); ?>"
              enctype="multipart/form-data">

            <div class="tsh-co-box">

                <!-- ── PHẦN 1: Thông tin đặt lịch ── -->
                <div class="tsh-co-section tsh-co-section--booking">
                    <div class="tsh-co-section__hd">
                        <h3 class="tsh-co-section__title">Thông tin đặt lịch</h3>
                        <a href="javascript:history.back()" class="tsh-co-edit-link">Chỉnh sửa</a>
                    </div>
                    <div class="tsh-co-info-grid">
                        <?php foreach ($_info_rows as $row) : ?>
                            <?php if (trim($row['value'])) : ?>
                            <div class="tsh-co-info-row">
                                <span class="tsh-co-info-label"><?php echo esc_html($row['label']); ?></span>
                                <span class="tsh-co-info-val"><?php echo esc_html($row['value']); ?></span>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- ── PHẦN 2: Đơn hàng + Thanh toán ── -->
                <div class="tsh-co-section tsh-co-section--order">
                    <div class="tsh-co-section__hd">
                        <h3 class="tsh-co-section__title">Đơn hàng của bạn</h3>
                    </div>
                    <?php do_action('woocommerce_checkout_order_review'); ?>
                </div>

            </div><!-- .tsh-co-box -->

            <!-- Hidden billing fields -->
            <input type="hidden" name="billing_first_name" value="<?php echo esc_attr($_first); ?>">
            <input type="hidden" name="billing_last_name"  value="<?php echo esc_attr($_last); ?>">
            <input type="hidden" name="billing_email"      value="<?php echo esc_attr($_email); ?>">
            <input type="hidden" name="billing_phone"      value="<?php echo esc_attr($_phone); ?>">
            <input type="hidden" name="billing_country"    value="VN">
            <input type="hidden" name="billing_address_1"  value="<?php echo esc_attr($_location ?: 'N/A'); ?>">
            <input type="hidden" name="billing_city"       value="Ho Chi Minh">

            <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>

        </form>

        <?php endif; ?>

    </div>
</div>
