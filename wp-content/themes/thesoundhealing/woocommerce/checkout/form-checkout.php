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

// ── Nội dung "Lưu ý" (chỉnh trong Giao diện → Theme Settings → Lưu ý – Trang thanh toán).
// Để trống ở admin sẽ dùng nội dung mặc định bên dưới.
$_notes_intro     = get_field('co_notes_intro', 'option') ?: __('Để có trải nghiệm thoải mái và trọn vẹn nhất, vui lòng đọc qua một số lưu ý dưới đây:', 'monamedia');
$_notes_title     = get_field('co_notes_title', 'option') ?: __('Lưu ý', 'monamedia');
$_notes_sub_title = get_field('co_notes_sub_title', 'option') ?: __('Để có trải nghiệm tốt hơn', 'monamedia');

$_notes_main = array_filter(array_map(
    static fn($row) => trim($row['co_notes_main_text'] ?? ''),
    (array) get_field('co_notes_main', 'option')
));
if (! $_notes_main) {
    $_notes_main = [
        __('Vui lòng mặc trang phục thoải mái, phù hợp cho việc nằm thiền và thư giãn.', 'monamedia'),
        __('Có mặt trước giờ bắt đầu <strong>15 phút</strong> để check-in và ổn định vị trí. Khách đến muộn quá 10 phút mà không báo trước sẽ được xem là vắng mặt.', 'monamedia'),
        __('Quý khách được đổi lịch <strong>01 lần</strong>, vui lòng thông báo ít nhất <strong>02 giờ</strong> trước khi phiên diễn ra.', 'monamedia'),
        __('Vé đã mua được phép chuyển nhượng, nhưng <strong>không hoàn tiền</strong> dưới bất kỳ hình thức nào.', 'monamedia'),
        __('Sound Healing và các bộ môn Năng lượng là liệu pháp hỗ trợ thư giãn và cân bằng, không thay thế cho chẩn đoán hoặc điều trị y khoa.', 'monamedia'),
    ];
}

$_notes_sub = array_filter(array_map(
    static fn($row) => trim($row['co_notes_sub_text'] ?? ''),
    (array) get_field('co_notes_sub', 'option')
));
if (! $_notes_sub) {
    $_notes_sub = [
        __('Hạn chế sử dụng rượu bia hoặc chất kích thích trước phiên.', 'monamedia'),
        __('Uống đủ nước trước và sau khi tham gia.', 'monamedia'),
        __('Đến với một tâm thế cởi mở, thư giãn và không kỳ vọng vào một trải nghiệm cụ thể. Mỗi người sẽ có hành trình cảm nhận riêng.', 'monamedia'),
    ];
}
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

                            <?php
                            // Ô Lời nhắn (lưu vào customer note → hiển thị ở mục NOTES trên E-ticket).
                            // Dịch qua WPML String Translation (context "monamedia") — client sửa bản EN
                            // trong WPML → String Translation, KHÔNG dùng .po/.mo.
                            $_note_label = mona_wpml_string('Lời nhắn (tuỳ chọn)', 'Checkout – nhãn ô Lời nhắn');
                            $_note_ph    = mona_wpml_string('Lời chúc, ghi chú riêng hoặc thông tin E-ticket (ví dụ: #00XX)…', 'Checkout – placeholder ô Lời nhắn');

                            // Fallback EN tạm thời khi chưa dịch trong WPML (mona_wpml_string trả về chuỗi gốc VI).
                            // Khi client dịch trong WPML → giá trị khác chuỗi gốc → bản WPML được giữ nguyên.
                            $_is_en = function_exists('determine_locale') && strpos(determine_locale(), 'en') === 0;
                            if ($_is_en) {
                                if ($_note_label === 'Lời nhắn (tuỳ chọn)') {
                                    $_note_label = 'Message (optional)';
                                }
                                if ($_note_ph === 'Lời chúc, ghi chú riêng hoặc thông tin E-ticket (ví dụ: #00XX)…') {
                                    $_note_ph = 'A gift message, private note, or E-ticket info (e.g. #00XX)…';
                                }
                            }
                            ?>
                            <!-- Lời nhắn (tuỳ chọn) — lưu vào customer note, hiển thị ở mục NOTES trên E-ticket -->
                            <div class="tsh-co-note-field">
                                <label class="tsh-co-note-field__label" for="order_comments"><?php echo esc_html($_note_label); ?></label>
                                <textarea class="tsh-co-note-field__input" id="order_comments" name="order_comments" rows="3" placeholder="<?php echo esc_attr($_note_ph); ?>"></textarea>
                            </div>

                            <!-- Lưu ý -->
                            <div class="tsh-co-notes">
                                <h4 class="tsh-co-notes__title"><?php echo esc_html($_notes_title); ?></h4>
                                <?php if ($_notes_intro) : ?>
                                    <p class="tsh-co-notes__intro"><?php echo esc_html($_notes_intro); ?></p>
                                <?php endif; ?>
                                <ul class="tsh-co-notes__list">
                                    <?php foreach ($_notes_main as $_note) : ?>
                                        <li><?php echo wp_kses_post($_note); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php if ($_notes_sub_title || $_notes_sub) : ?>
                                    <h4 class="tsh-co-notes__title tsh-co-notes__title--sub"><?php echo esc_html($_notes_sub_title); ?></h4>
                                    <ul class="tsh-co-notes__list">
                                        <?php foreach ($_notes_sub as $_note) : ?>
                                            <li><?php echo wp_kses_post($_note); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
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