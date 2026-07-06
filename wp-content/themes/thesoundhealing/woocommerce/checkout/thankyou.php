<?php
defined('ABSPATH') || exit;

$order = isset($order) ? $order : false;
?>

<div class="tsh-ty-wrap">
    <div class="container">

        <?php if (!$order) : ?>
        <div class="tsh-ty-empty">
            <p><?php esc_html_e('Không tìm thấy thông tin đơn hàng.', 'monamedia'); ?></p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="tsh-ty-btn tsh-ty-btn--pri">← <?php esc_html_e('Về trang chủ', 'monamedia'); ?></a>
        </div>
        <?php else :
            $order_id   = $order->get_id();
            $date_obj   = $order->get_date_created();
            $order_date = $date_obj ? $date_obj->date_i18n('d/m/Y') : '';
            $email      = $order->get_billing_email();
            $name       = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
            $phone      = $order->get_billing_phone();
            $total      = $order->get_formatted_order_total();

            // Cọc 50%: hiển thị nhãn + số còn lại ngay chỗ Tổng thanh toán
            $is_deposit = $order->get_meta('_tsh_payment_type') === 'deposit';
            $remaining  = (float) $order->get_meta('_tsh_remaining_amount');

            $b_date       = $order->get_meta('_booking_date');
            $b_time       = $order->get_meta('_booking_time');
            $b_location   = $order->get_meta('_booking_location');
            $b_guests     = $order->get_meta('_booking_guests');
            $b_instructor = $order->get_meta('_booking_instructor');
            $b_children   = $order->get_meta('_booking_children');

            $items = $order->get_items();
            $first_item = !empty($items) ? reset($items) : null;
            $service_name = $first_item ? $first_item->get_name() : '';
            $eticket_expiry = $order->get_meta('_tsh_eticket_expiry');
            $eticket_dbg2   = '';
            // Fallback: nếu chưa có (save_eticket_meta không chạy lúc tạo đơn do OPcache lệch worker),
            // tính ngay tại đây từ item của đơn rồi lưu lại — tự chữa, lần sau đọc thẳng.
            if (!$eticket_expiry) {
                $e_items = $order->get_items();
                $e_first = $e_items ? reset($e_items) : null;
                $e_pid   = $e_first ? (int) $e_first->get_product_id() : 0;
                if (!$e_pid) {
                    $eticket_dbg2 = 'no_product_in_order';
                } else {
                    $e_cpt = get_posts([
                        'post_type'      => ['khoa_hoc', 'workshop', 'dich_vu'],
                        'post_status'    => 'any',
                        'meta_key'       => '_wc_product_id',
                        'meta_value'     => $e_pid,
                        'posts_per_page' => 1,
                        'fields'         => 'ids',
                    ]);
                    if (empty($e_cpt)) {
                        $eticket_dbg2 = 'no_cpt:pid=' . $e_pid;
                    } else {
                        $e_days = (int) get_post_meta($e_cpt[0], 'eticket_days', true);
                        if ($e_days <= 0) {
                            $eticket_dbg2 = 'days0:cpt=' . $e_cpt[0] . ',pid=' . $e_pid;
                        } else {
                            $e_created = $order->get_date_created();
                            $e_base = $e_created
                                ? ($e_created->getTimestamp() + (int) round(((float) get_option('gmt_offset', 0)) * 3600))
                                : current_time('timestamp');
                            $eticket_expiry = gmdate('Y-m-d', $e_base + $e_days * DAY_IN_SECONDS);
                            $order->update_meta_data('_tsh_eticket_expiry', $eticket_expiry);
                            $order->update_meta_data('_tsh_eticket_days', $e_days);
                            $order->save();
                            $eticket_dbg2 = 'computed_ok:cpt=' . $e_cpt[0] . ',days=' . $e_days;
                        }
                    }
                }
            }
        ?>

        <!-- Grid: content trái + banner phải -->
        <div class="tsh-ty-body tsh-ty-body--banner">

        <!-- Cột trái: toàn bộ content -->
        <div class="tsh-ty-content">

        <?php if (current_user_can('manage_options') || isset($_GET['ticketdebug'])) : ?>
        <div style="margin:0 0 16px;padding:12px 14px;background:#fffbe6;border:1px solid #e0c060;border-radius:8px;font-size:13px;color:#333;line-height:1.6">
            <strong>DEBUG e-ticket</strong> (chỉ admin thấy)<br>
            _tsh_eticket_expiry: <strong><?php echo esc_html($order->get_meta('_tsh_eticket_expiry') ?: '(rỗng)'); ?></strong><br>
            _tsh_eticket_days: <strong><?php echo esc_html($order->get_meta('_tsh_eticket_days') ?: '(rỗng)'); ?></strong><br>
            _tsh_eticket_debug: <strong><?php echo esc_html($order->get_meta('_tsh_eticket_debug') ?: '(rỗng — save_eticket_meta không chạy lúc tạo đơn)'); ?></strong><br>
            fallback tại trang cảm ơn: <strong><?php echo esc_html($eticket_dbg2 ?: '(không cần — đã có sẵn)'); ?></strong>
        </div>
        <?php endif; ?>

        <!-- Checkmark header -->
        <div class="tsh-ty-header">
            <div class="tsh-ty-check">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="tsh-ty-title"><?php esc_html_e('Đặt lịch thành công!', 'monamedia'); ?></h1>
            <p class="tsh-ty-sub"><?php esc_html_e('Cảm ơn bạn đã tin tưởng lựa chọn dịch vụ của The Sound Healing.', 'monamedia'); ?><br><?php echo wp_kses_post(__('Chúng tôi đã nhận được yêu cầu đặt lịch của bạn và sẽ xác nhận trong vòng <strong>2 giờ</strong>.', 'monamedia')); ?></p>
        </div>

        <!-- Hỗ trợ / liên hệ -->
        <div class="tsh-ty-support">
            <p class="tsh-ty-support-lead"><?php esc_html_e('Nếu cần hỗ trợ hoặc có bất kỳ thắc mắc nào, vui lòng liên hệ qua số Zalo / Whatsapp', 'monamedia'); ?></p>
            <p class="tsh-ty-support-nums">
                <span>* <?php esc_html_e('English', 'monamedia'); ?>: <a href="tel:0939624684">0939 624 684</a></span>
                <span class="tsh-ty-support-sep">|</span>
                <span><?php esc_html_e('Tiếng Việt', 'monamedia'); ?>: <a href="tel:0906502582">0906 502 582</a></span>
            </p>
        </div>

        <!-- Card -->
        <div class="tsh-ty-card">

            <!-- Confirmation code -->
            <div class="tsh-ty-code-row">
                <div>
                    <span class="tsh-ty-code-label"><?php esc_html_e('Mã xác nhận', 'monamedia'); ?></span>
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

            <!-- Thanh toán: QR SePay / PayPal / cash — render_thankyou_payment() gắn vào woocommerce_thankyou.
                 CHỈ fire hook tổng (không fire woocommerce_thankyou_{method}) để renderer riêng của
                 các gateway không double-render. -->
            <?php do_action('woocommerce_thankyou', $order_id); ?>

            <!-- 3-col quick info -->
            <div class="tsh-ty-quick">
                <?php if ($b_date) : ?>
                <div class="tsh-ty-quick-item">
                    <span class="tsh-ty-quick-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <?php esc_html_e('Ngày đặt', 'monamedia'); ?>
                    </span>
                    <span class="tsh-ty-quick-val"><?php echo esc_html($b_date); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($b_time) : ?>
                <div class="tsh-ty-quick-item">
                    <span class="tsh-ty-quick-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <?php esc_html_e('Khung giờ', 'monamedia'); ?>
                    </span>
                    <span class="tsh-ty-quick-val"><?php echo esc_html($b_time); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($b_location) : ?>
                <div class="tsh-ty-quick-item">
                    <span class="tsh-ty-quick-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="1.6"/></svg>
                        <?php esc_html_e('Chi nhánh', 'monamedia'); ?>
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
                    <h4 class="tsh-ty-detail-title"><?php esc_html_e('Thông tin người đặt', 'monamedia'); ?></h4>
                    <div class="tsh-ty-detail-grid">
                        <?php if ($name) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Họ và tên', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($name); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($phone) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Số điện thoại', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($phone); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($email) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Email', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val tsh-ty-detail-val--ellipsis" title="<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($b_guests) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Số người tham gia', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($b_guests) . ' ' . esc_html__('người', 'monamedia'); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($b_children) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Trẻ em tham gia', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($b_children); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($b_instructor) : ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Người hướng dẫn', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($b_instructor); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Dịch vụ & Thanh toán -->
                <div class="tsh-ty-detail-group">
                    <h4 class="tsh-ty-detail-title"><?php esc_html_e('Dịch vụ & Thanh toán', 'monamedia'); ?></h4>
                    <div class="tsh-ty-detail-grid">
                        <?php if ($service_name) : ?>
                        <div class="tsh-ty-detail-item tsh-ty-detail-item--full">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Dịch vụ', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val"><?php echo esc_html($service_name); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Tổng thanh toán', 'monamedia'); ?></span>
                            <span class="tsh-ty-detail-val tsh-ty-detail-val--gold">
                                <?php echo wp_kses_post($total); ?>
                                <?php if ($is_deposit) : ?><span class="tsh-ty-deposit-tag"><?php esc_html_e('Đặt cọc 50%', 'monamedia'); ?></span><?php endif; ?>
                            </span>
                        </div>
                        <div class="tsh-ty-detail-item">
                            <span class="tsh-ty-detail-label"><?php esc_html_e('Phương thức', 'monamedia'); ?></span>
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
                <p><?php esc_html_e('Chúng tôi sẽ liên hệ xác nhận lịch hẹn sớm nhất. Quý khách vui lòng đến sớm 15 phút để được phục vụ tốt nhất.', 'monamedia'); ?></p>
            </div>

        </div><!-- /.tsh-ty-card -->

        <!-- Actions (trong cột content) -->
        <div class="tsh-ty-actions">
            <?php if ($eticket_expiry) : ?>
            <button type="button" id="tsh-eticket-btn" class="tsh-ty-btn tsh-ty-btn--ghost" data-order="<?php echo esc_attr(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?>"><?php esc_html_e('E-TICKET LÀM QUÀ TẶNG', 'monamedia'); ?></button>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="tsh-ty-btn tsh-ty-btn--pri"><?php esc_html_e('Về trang chủ', 'monamedia'); ?></a>
        </div>

        </div><!-- /.tsh-ty-content -->

        <!-- Cột phải: banner -->
        <div class="tsh-ty-banner">
            <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/banner-confirm.png'); ?>" alt="<?php esc_attr_e('Đặt lịch thành công', 'monamedia'); ?>" loading="lazy">
        </div>

        <?php if ($eticket_expiry) : ?>
        <!-- Voucher e-ticket (ẩn ngoài màn hình, html2canvas chụp) -->
        <div class="tsh-voucher" aria-hidden="true">
            <div class="tsh-voucher__content">
                <p class="tsh-voucher__brand">THE SOUND HEALING</p>
                <h2 class="tsh-voucher__title"><?php esc_html_e('E-TICKET QUÀ TẶNG', 'monamedia'); ?></h2>
                <div class="tsh-voucher__code">
                    <span><?php esc_html_e('Mã e-ticket', 'monamedia'); ?></span>
                    <strong>#<?php echo esc_html(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?></strong>
                </div>
                <div class="tsh-voucher__rows">
                    <div class="tsh-voucher__row"><span><?php esc_html_e('Dịch vụ', 'monamedia'); ?></span><strong><?php echo esc_html($service_name); ?></strong></div>
                    <div class="tsh-voucher__row"><span><?php esc_html_e('Số người', 'monamedia'); ?></span><strong><?php echo esc_html(($b_guests ?: '1') . ' ' . __('người', 'monamedia')); ?></strong></div>
                    <div class="tsh-voucher__row"><span><?php esc_html_e('Ngày hết hạn', 'monamedia'); ?></span><strong><?php echo esc_html(date_i18n('d/m/Y', strtotime($eticket_expiry))); ?></strong></div>
                </div>
                <div class="tsh-voucher__hotline">
                    <p><?php esc_html_e('Liên hệ hotline để đặt lịch:', 'monamedia'); ?></p>
                    <p><strong>English:</strong> 0939 624 684 &nbsp; | &nbsp; <strong>Tiếng Việt:</strong> 0906 502 582</p>
                </div>
                <p class="tsh-voucher__note"><?php esc_html_e('Vui lòng xuất trình mã e-ticket khi sử dụng dịch vụ.', 'monamedia'); ?></p>
            </div>
            <div class="tsh-voucher__banner">
                <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/banner-confirm.png'); ?>" alt="" crossorigin="anonymous">
            </div>
        </div>
        <?php endif; ?>

        </div><!-- /.tsh-ty-body -->

        <?php endif; ?>

    </div>
</div>
