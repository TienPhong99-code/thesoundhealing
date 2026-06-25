<?php
defined('ABSPATH') || exit;

$order = isset($order) ? $order : false;
?>

<div class="tsh-ty-wrap">
    <div class="container">

        <?php if (!$order) : ?>
        <div class="tsh-ty-empty">
            <p>Không tìm thấy thông tin đơn hàng.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="tsh-ty-btn tsh-ty-btn--pri">← Về trang chủ</a>
        </div>
        <?php else :
            $order_id   = $order->get_id();
            $date_obj   = $order->get_date_created();
            $order_date = $date_obj ? $date_obj->date_i18n('d/m/Y') : '';
            $email      = $order->get_billing_email();
            $name       = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
            $phone      = $order->get_billing_phone();
            $total      = $order->get_formatted_order_total();

            $b_date       = $order->get_meta('_booking_date');
            $b_time       = $order->get_meta('_booking_time');
            $b_location   = $order->get_meta('_booking_location');
            $b_guests     = $order->get_meta('_booking_guests');
            $b_instructor = $order->get_meta('_booking_instructor');
            $b_children   = $order->get_meta('_booking_children');

            $items = $order->get_items();
            $first_item = !empty($items) ? reset($items) : null;
            $service_name = $first_item ? $first_item->get_name() : '';
        ?>

        <!-- Checkmark header -->
        <div class="tsh-ty-header">
            <div class="tsh-ty-check">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="tsh-ty-title">Đặt lịch thành công!</h1>
            <p class="tsh-ty-sub">Cảm ơn bạn đã tin tưởng lựa chọn dịch vụ của The Sound Healing.<br>Chúng tôi đã nhận được yêu cầu của bạn.</p>
        </div>

        <!-- Card -->
        <div class="tsh-ty-card">

            <!-- Confirmation code -->
            <div class="tsh-ty-code-row">
                <div>
                    <span class="tsh-ty-code-label">Mã xác nhận</span>
                    <span class="tsh-ty-code-val">#<?php echo esc_html(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?></span>
                </div>
                <div class="tsh-ty-code-icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                        <rect x="2" y="7" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M16 7V5a4 4 0 0 0-8 0v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                        <circle cx="12" cy="14" r="2" fill="currentColor"/>
                    </svg>
                </div>
            </div>

            <!-- 3-col quick info -->
            <div class="tsh-ty-quick">
                <?php if ($b_date) : ?>
                <div class="tsh-ty-quick-item">
                    <span class="tsh-ty-quick-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        Ngày đặt
                    </span>
                    <span class="tsh-ty-quick-val"><?php echo esc_html($b_date); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($b_time) : ?>
                <div class="tsh-ty-quick-item">
                    <span class="tsh-ty-quick-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        Khung giờ
                    </span>
                    <span class="tsh-ty-quick-val"><?php echo esc_html($b_time); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($b_location) : ?>
                <div class="tsh-ty-quick-item">
                    <span class="tsh-ty-quick-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="1.6"/></svg>
                        Chi nhánh
                    </span>
                    <span class="tsh-ty-quick-val"><?php echo esc_html($b_location); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Divider -->
            <div class="tsh-ty-divider"></div>

            <!-- Full booking + personal info -->
            <div class="tsh-ty-details">

                <!-- Thông tin người đặt -->
                <div class="tsh-ty-detail-group">
                    <h4 class="tsh-ty-detail-title">Thông tin người đặt</h4>
                    <div class="tsh-ty-detail-grid">
                        <?php if ($name) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Họ và tên</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($name); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($phone) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Số điện thoại</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($phone); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Email</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($email); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($b_guests) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Số người tham gia</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($b_guests . ' người'); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($b_children) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Trẻ em tham gia</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($b_children); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($b_instructor) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Người hướng dẫn</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($b_instructor); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Dịch vụ & Thanh toán -->
                <div class="tsh-ty-detail-group">
                    <h4 class="tsh-ty-detail-title">Dịch vụ & Thanh toán</h4>
                    <div class="tsh-ty-detail-grid">
                        <?php if ($service_name) : ?>
                        <div class="tsh-ty-detail-item tsh-ty-detail-item--full">
                            <span class="tsh-ty-detail-label">Dịch vụ</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($service_name); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Tổng thanh toán</span>
                            <span class="tsh-ty-detail-val tsh-ty-detail-val--gold"><?php echo wp_kses_post($total); ?></span>
                        </div>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label">Phương thức</span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($order->get_payment_method_title()); ?></span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Notice -->
            <div class="tsh-ty-notice">
                <svg class="tsh-ty-notice-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M12 8v4m0 4h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <p>Chúng tôi sẽ liên hệ xác nhận lịch hẹn sớm nhất. Quý khách vui lòng đến sớm 10 phút để được phục vụ tốt nhất.</p>
            </div>

        </div><!-- /.tsh-ty-card -->

        <!-- Actions -->
        <div class="tsh-ty-actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="tsh-ty-btn tsh-ty-btn--pri">Về trang chủ</a>
        </div>

        <?php endif; ?>

    </div>
</div>
