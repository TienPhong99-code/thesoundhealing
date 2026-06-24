<?php
defined('ABSPATH') || exit;

// Booking data từ transient (lưu khi CF7 submit)
$_token   = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
$_booking = $_token ? (array) get_transient('tsh_booking_' . $_token) : [];

$_name       = $_booking['fullname']    ?? '';
$_email      = $_booking['email']       ?? '';
$_phone      = $_booking['phone']       ?? '';
$_date       = $_booking['date']        ?? '';
$_time       = $_booking['time']        ?? '';
$_location   = $_booking['location']    ?? '';
$_guests     = $_booking['guests']      ?? '';
$_instructor = $_booking['instructor']  ?? '';
$_children   = $_booking['children']    ?? '';

// Tách first/last name (họ = từ đầu tiên, tên = phần còn lại)
$_name_parts  = explode(' ', trim($_name), 2);
$_first_name  = $_name_parts[0] ?? $_name;
$_last_name   = $_name_parts[1] ?? '';
?>

<div class="tsh-checkout-wrap">
    <div class="container">

        <?php if (have_posts()) : while (have_posts()) : the_post(); endwhile; endif; ?>

        <form name="checkout" method="post"
              class="checkout woocommerce-checkout"
              action="<?php echo esc_url(wc_get_checkout_url()); ?>"
              enctype="multipart/form-data">

            <div class="tsh-co-grid">

                <!-- ── Cột trái: Thông tin đặt lịch ── -->
                <div class="tsh-co-left">

                    <?php if (empty($_booking)) : ?>
                        <div class="tsh-co-empty">
                            <p>Không tìm thấy thông tin đặt lịch. Vui lòng quay lại trang dịch vụ và điền form.</p>
                            <a href="<?php echo esc_url(home_url('/dich-vu-trai-nghiem/')); ?>" class="tsh-co-back-btn">← Quay lại</a>
                        </div>
                    <?php else : ?>

                    <div class="tsh-co-card">
                        <h3 class="tsh-co-card__title">Thông tin đặt lịch</h3>

                        <div class="tsh-co-info-grid">
                            <?php if ($_name) : ?>
                            <div class="tsh-co-info-row">
                                <span class="tsh-co-info-label">Họ và tên</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_name); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_phone) : ?>
                            <div class="tsh-co-info-row">
                                <span class="tsh-co-info-label">Số điện thoại</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_phone); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_email) : ?>
                            <div class="tsh-co-info-row tsh-co-info-row--wide">
                                <span class="tsh-co-info-label">Email</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_email); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_date) : ?>
                            <div class="tsh-co-info-row">
                                <span class="tsh-co-info-label">Ngày đặt</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_date); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_time) : ?>
                            <div class="tsh-co-info-row">
                                <span class="tsh-co-info-label">Khung giờ</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_time); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_location) : ?>
                            <div class="tsh-co-info-row tsh-co-info-row--wide">
                                <span class="tsh-co-info-label">Chi nhánh</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_location); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_guests) : ?>
                            <div class="tsh-co-info-row">
                                <span class="tsh-co-info-label">Số người</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_guests); ?> người</span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_instructor) : ?>
                            <div class="tsh-co-info-row">
                                <span class="tsh-co-info-label">Người hướng dẫn</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_instructor); ?></span>
                            </div>
                            <?php endif; ?>

                            <?php if ($_children) : ?>
                            <div class="tsh-co-info-row tsh-co-info-row--wide">
                                <span class="tsh-co-info-label">Có trẻ em tham gia</span>
                                <span class="tsh-co-info-val"><?php echo esc_html($_children); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <a href="javascript:history.back()" class="tsh-co-edit-link">← Chỉnh sửa thông tin</a>
                    </div>

                    <?php endif; ?>

                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                </div>

                <!-- ── Cột phải: Đơn hàng + Thanh toán ── -->
                <div class="tsh-co-right">
                    <div class="tsh-co-card">
                        <h3 class="tsh-co-card__title">Đơn hàng của bạn</h3>
                        <?php do_action('woocommerce_checkout_order_review'); ?>
                    </div>
                </div>

            </div><!-- .tsh-co-grid -->

            <!-- Hidden billing fields — WC cần để tạo order -->
            <input type="hidden" name="billing_first_name" value="<?php echo esc_attr($_first_name); ?>">
            <input type="hidden" name="billing_last_name"  value="<?php echo esc_attr($_last_name); ?>">
            <input type="hidden" name="billing_email"      value="<?php echo esc_attr($_email); ?>">
            <input type="hidden" name="billing_phone"      value="<?php echo esc_attr($_phone); ?>">
            <input type="hidden" name="billing_country"    value="VN">
            <input type="hidden" name="billing_address_1"  value="<?php echo esc_attr($_location ?: 'N/A'); ?>">
            <input type="hidden" name="billing_city"       value="Ho Chi Minh">

            <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>

        </form>

    </div>
</div>
