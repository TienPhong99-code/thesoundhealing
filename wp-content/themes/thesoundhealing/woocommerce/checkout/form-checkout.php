<?php
defined('ABSPATH') || exit;

$_token   = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
$_booking = $_token ? (array) get_transient('tsh_booking_' . $_token) : [];

$_name       = $_booking['fullname']   ?? '';
$_email      = $_booking['email']      ?? '';
$_phone      = $_booking['phone']      ?? '';
$_date_raw   = $_booking['date'] ?? '';
$_date       = $_date_raw ? (DateTime::createFromFormat('Y-m-d', $_date_raw) ?: DateTime::createFromFormat('d-m-Y', $_date_raw))?->format('d-m-Y') ?? $_date_raw : '';
$_time       = $_booking['time']       ?? '';
$_location   = $_booking['location']   ?? '';
$_guests     = $_booking['guests']     ?? '';
$_instructor = $_booking['instructor'] ?? '';
$_children   = $_booking['children']   ?? '';
$_source_url = $_booking['source_url'] ?? '';

// Tách họ tên: từ đầu → first, phần còn lại → last. Tên chỉ 1 từ thì last để RỖNG
// (billing_last_name đã set required=false). Trước đây last fallback về first nên tên
// 1 từ bị lặp thành "Phong Phong" ở đơn hàng + email (first . ' ' . last).
$_parts     = explode(' ', trim($_name), 2);
$_first     = $_parts[0] ?? $_name;
$_last      = $_parts[1] ?? '';

// Danh sách field hiển thị (chỉ render dòng nào có giá trị)
$_info_rows = [
    ['label' => __('Họ và tên', 'monamedia'),         'value' => $_name],
    ['label' => __('Số điện thoại', 'monamedia'),     'value' => $_phone],
    ['label' => __('Email', 'monamedia'),             'value' => $_email],
    ['label' => __('Ngày đặt hẹn', 'monamedia'),      'value' => $_date],
    ['label' => __('Khung giờ', 'monamedia'),         'value' => $_time],
    ['label' => __('Chi nhánh', 'monamedia'),         'value' => $_location],
    ['label' => __('Số người tham gia', 'monamedia'), 'value' => $_guests ? $_guests . ' ' . __('người', 'monamedia') : ''],
    ['label' => __('Người hướng dẫn', 'monamedia'),   'value' => $_instructor],
    ['label' => __('Trẻ em tham gia', 'monamedia'),   'value' => $_children],
];
?>

<div class="tsh-checkout-wrap">
    <div class="container">

        <?php if (have_posts()) : while (have_posts()) : the_post();
            endwhile;
        endif; ?>

        <?php if (empty($_booking)) : ?>
            <div class="tsh-co-empty">
                <p><?php esc_html_e('Không tìm thấy thông tin đặt lịch. Vui lòng quay lại và điền form đặt lịch.', 'monamedia'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="tsh-co-back-btn">← <?php esc_html_e('Về trang chủ', 'monamedia'); ?></a>
            </div>
        <?php else : ?>

            <form name="checkout" method="post"
                class="checkout woocommerce-checkout tsh-co-form"
                action="<?php echo esc_url(wc_get_checkout_url()); ?>"
                enctype="multipart/form-data">

                <div class="tsh-co-grid">

                    <!-- ── CỘT TRÁI: Thông tin đặt lịch ── -->
                    <div class="tsh-co-box">
                        <div class="tsh-co-section">
                            <div class="tsh-co-section__hd">
                                <h3 class="tsh-co-section__title"><?php esc_html_e('Thông tin đặt lịch', 'monamedia'); ?></h3>
                                <a href="<?php echo esc_url($_source_url ?: 'javascript:history.back()'); ?>" class="tsh-co-edit-link"><?php esc_html_e('Chỉnh sửa', 'monamedia'); ?></a>
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

                            <!-- Lưu ý -->
                            <div class="tsh-co-notes">
                                <h4 class="tsh-co-notes__title"><?php esc_html_e('Lưu ý', 'monamedia'); ?></h4>
                                <ul class="tsh-co-notes__list">
                                    <li><?php esc_html_e('Vui lòng mặc trang phục thoải mái, phù hợp cho việc nằm thiền và thư giãn.', 'monamedia'); ?></li>
                                    <li><?php echo wp_kses_post(__('Có mặt trước giờ bắt đầu <strong>15 phút</strong> để check-in và ổn định vị trí. Khách đến muộn quá 10 phút mà không báo trước sẽ được xem là vắng mặt.', 'monamedia')); ?></li>
                                    <li><?php echo wp_kses_post(__('Quý khách được đổi lịch <strong>01 lần</strong>, vui lòng thông báo ít nhất <strong>02 giờ</strong> trước khi phiên diễn ra.', 'monamedia')); ?></li>
                                    <li><?php echo wp_kses_post(__('Vé đã mua được phép chuyển nhượng, nhưng <strong>không hoàn tiền</strong> dưới bất kỳ hình thức nào.', 'monamedia')); ?></li>
                                    <li><?php esc_html_e('Sound Healing và các bộ môn Năng lượng là liệu pháp hỗ trợ thư giãn và cân bằng, không thay thế cho chẩn đoán hoặc điều trị y khoa.', 'monamedia'); ?></li>
                                </ul>
                                <h4 class="tsh-co-notes__title tsh-co-notes__title--sub"><?php esc_html_e('Để có trải nghiệm tốt hơn', 'monamedia'); ?></h4>
                                <ul class="tsh-co-notes__list">
                                    <li><?php esc_html_e('Hạn chế sử dụng rượu bia hoặc chất kích thích trước phiên.', 'monamedia'); ?></li>
                                    <li><?php esc_html_e('Uống đủ nước trước và sau khi tham gia.', 'monamedia'); ?></li>
                                    <li><?php esc_html_e('Đến với một tâm thế cởi mở, thư giãn và không kỳ vọng vào một trải nghiệm cụ thể. Mỗi người sẽ có hành trình cảm nhận riêng.', 'monamedia'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- /.tsh-co-box left -->

                    <!-- ── CỘT PHẢI: Đơn hàng + Thanh toán ── -->
                    <div class="tsh-co-box">
                        <div class="tsh-co-section">
                            <div class="tsh-co-section__hd">
                                <h3 class="tsh-co-section__title"><?php esc_html_e('Đơn hàng của bạn', 'monamedia'); ?></h3>
                            </div>
                            <?php do_action('woocommerce_checkout_order_review'); ?>
                        </div>
                    </div><!-- /.tsh-co-box right -->

                </div><!-- .tsh-co-grid -->

                <!-- Hidden billing fields -->
                <input type="hidden" name="billing_first_name" value="<?php echo esc_attr($_first); ?>">
                <input type="hidden" name="billing_last_name" value="<?php echo esc_attr($_last); ?>">
                <input type="hidden" name="billing_email" value="<?php echo esc_attr($_email); ?>">
                <input type="hidden" name="billing_phone" value="<?php echo esc_attr($_phone); ?>">
                <input type="hidden" name="billing_country" value="VN">
                <input type="hidden" name="billing_address_1" value="<?php echo esc_attr($_location ?: 'N/A'); ?>">
                <input type="hidden" name="billing_city" value="Ho Chi Minh">

                <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>

            </form>

        <?php endif; ?>

    </div>
</div>