<?php
defined('ABSPATH') || exit;

class TSH_WooCommerce_Hook
{

    public function __construct()
    {
        add_action('template_redirect',   [$this, 'redirect_cart_to_home']);
        add_action('after_setup_theme',   [$this, 'declare_support']);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        add_action('init',                [$this, 'register_endpoint']);
        add_action('template_redirect',   [$this, 'handle_buy_now']);
        add_filter('woocommerce_checkout_get_value', [$this, 'prefill_checkout'], 10, 2);
        add_action('woocommerce_checkout_order_processed', [$this, 'save_booking_meta']);
        add_action('woocommerce_checkout_create_order', [$this, 'save_payment_type_meta'], 10, 2);
        add_action('woocommerce_checkout_create_order', [$this, 'save_eticket_meta'], 10, 2);
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'display_booking_meta']);
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'display_deposit_admin']);
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'display_eticket_admin']);
        add_action('woocommerce_email_after_order_table',                [$this, 'email_deposit_notice'], 10, 4);
        add_filter('woocommerce_checkout_fields', [$this, 'simplify_checkout_fields']);
        add_filter('woocommerce_order_button_text', fn() => __('Đặt lịch ngay', 'monamedia'));
        add_filter('woocommerce_email_heading_customer_processing_order', fn() => __('Cảm ơn bạn đã đặt lịch hẹn', 'monamedia'));
        add_filter('woocommerce_email_heading_customer_on_hold_order',    fn() => __('Cảm ơn bạn đã đặt lịch hẹn', 'monamedia'));
        add_filter('woocommerce_email_subject_customer_processing_order', [$this, 'customer_email_subject'], 10, 2);
        add_filter('woocommerce_email_subject_customer_on_hold_order',    [$this, 'customer_email_subject'], 10, 2);
        add_filter('woocommerce_gateway_description',          [$this, 'add_bacs_qr_checkout'], 10, 2);
        add_action('woocommerce_thankyou',                   [$this, 'render_thankyou_payment'], 5);
        // Ẩn bảng "Chi tiết đơn hàng" + địa chỉ mặc định của WooCommerce trên trang cảm ơn
        // (card tuỳ chỉnh đã hiển thị đủ). Cũng gỡ luôn hộp "đã đặt cọc..." hiện sớm khi chưa trả.
        remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
        add_action('wp_footer',                              [$this, 'checkout_bacs_js']);
        add_action('wp_footer', [$this, 'payment_type_js']);
        add_action('wp_footer',                              [$this, 'thankyou_polling_js']);
        add_action('woocommerce_email_after_order_table',    [$this, 'email_bacs_ref'], 10, 4);
        add_action('wp_ajax_nopriv_tsh_confirm_transfer',    [$this, 'ajax_confirm_transfer']);
        add_action('wp_ajax_tsh_confirm_transfer',           [$this, 'ajax_confirm_transfer']);
        add_filter('woocommerce_email_enabled_new_order',   [$this, 'prevent_duplicate_new_order_email'], 10, 3);
        add_filter('woocommerce_email_enabled_customer_on_hold_order', [$this, 'disable_customer_onhold_email'], 10, 2);
        add_filter('woocommerce_email_heading_new_order',   [$this, 'new_order_email_heading'], 10, 2);
        add_filter('woocommerce_email_subject_new_order',   [$this, 'new_order_email_subject'], 10, 2);
        add_filter('woocommerce_get_cart_item_from_session', [$this, 'restore_guests_cart_item'], 10, 2);
        add_action('woocommerce_before_calculate_totals',    [$this, 'apply_guests_price']);
        add_action('woocommerce_cart_calculate_fees', [$this, 'apply_deposit_fee']);
        add_action('woocommerce_checkout_update_order_review', [$this, 'sync_payment_type_from_post']);
        add_action('woocommerce_checkout_process',             [$this, 'sync_payment_type_on_process']);
        add_filter('woocommerce_get_item_data',              [$this, 'display_guests_in_cart'], 10, 2);
        add_filter('woocommerce_available_payment_gateways', [$this, 'set_bacs_first']);
        // Không ép chọn sẵn cổng nào — khách phải tự click chọn (xử lý ở checkout_bacs_js)
        // Mỗi lần mở checkout: reset loại thanh toán về 100% (mặc định) — cọc chỉ áp khi khách tự chọn.
        add_action('woocommerce_checkout_init', function () {
            if (WC()->session) {
                WC()->session->set('chosen_payment_method', '');
                WC()->session->set('tsh_payment_type', 'full');
            }
        });
        add_filter('woocommerce_gateway_icon', [$this, 'payment_method_icon'], 20, 2);
        add_action('woocommerce_review_order_before_payment', [$this, 'payment_type_options'], 5);
        add_action('woocommerce_review_order_before_payment', [$this, 'payment_section_title']);
        // Dịch tiêu đề cổng + label bảng đơn hàng WooCommerce sang EN theo ngôn ngữ hiện tại
        add_filter('woocommerce_gateway_title', [$this, 'i18n_gateway_title'], 20, 2);
        add_filter('gettext_woocommerce', [$this, 'i18n_wc_core_labels'], 20, 2);
        // Dịch tên phương thức thanh toán trong đơn/email (title đã lưu lúc đặt là tiếng Việt)
        add_filter('woocommerce_order_get_payment_method_title', [$this, 'i18n_order_payment_title'], 20, 2);
    }

    /**
     * Đổi tên phương thức thanh toán của đơn (dùng ở email/thankyou) sang EN khi locale EN.
     */
    public function i18n_order_payment_title($title, $order)
    {
        if (!$this->is_en_locale() || !is_object($order)) return $title;
        $en = [
            'sepay'         => 'Bank Transfer (QR)',
            'tsh_paypal_qr' => 'Pay with PayPal',
            'tsh_cash'      => 'Other Payment',
        ];
        $id = $order->get_payment_method();
        return $en[$id] ?? $title;
    }

    private function is_en_locale(): bool
    {
        return function_exists('determine_locale') && strpos(determine_locale(), 'en') === 0;
    }

    /**
     * Đổi tiêu đề cổng thanh toán sang tiếng Anh khi đang xem bản EN.
     */
    public function i18n_gateway_title($title, $gateway_id)
    {
        if (!$this->is_en_locale()) return $title;
        $en = [
            'sepay'         => 'Bank Transfer (QR)',
            'tsh_paypal_qr' => 'Pay with PayPal',
            'tsh_cash'      => 'Other Payment',
        ];
        return $en[$gateway_id] ?? $title;
    }

    /**
     * Ép chuỗi lõi WooCommerce (Product/Subtotal/Total) về tiếng Anh gốc khi xem bản EN
     * (tránh bị kẹt bản dịch tiếng Việt trên trang tiếng Anh).
     */
    public function i18n_wc_core_labels($translation, $text)
    {
        if (!$this->is_en_locale()) return $translation;
        static $keys = ['Product' => 1, 'Subtotal' => 1, 'Total' => 1];
        return isset($keys[$text]) ? $text : $translation;
    }

    /**
     * Tiêu đề phía trên khối chọn phương thức thanh toán ở checkout.
     */
    public function payment_section_title(): void
    {
        echo '<h3 class="tsh-co-payment-title">' . esc_html__('Lựa chọn phương thức thanh toán', 'monamedia') . '</h3>';
    }

    /**
     * 2 lựa chọn: thanh toán 100% (mặc định) hoặc đặt cọc 50%.
     * Chỉ hiện khi giỏ có tổng > 0. Nằm trong fragment order review nên tự
     * re-render (và giữ đúng lựa chọn từ session) mỗi lần update_checkout.
     */
    public function payment_type_options(): void
    {
        $cart = WC()->cart;
        if (!$cart) return;
        $full = (float) $cart->get_subtotal();
        if ($full <= 0) return;

        $remaining = round($full * 0.5);
        $deposit   = $full - $remaining;
        $type      = $this->get_payment_type();
?>
        <div class="tsh-paytype">
            <h3 class="tsh-co-payment-title"><?php esc_html_e('Lựa chọn thanh toán', 'monamedia'); ?></h3>
            <label class="tsh-paytype__opt<?= $type === 'full' ? ' is-active' : '' ?>">
                <input type="radio" name="tsh_paytype" value="full" <?php checked($type, 'full'); ?>>
                <span class="tsh-paytype__main"><?php esc_html_e('Thanh toán 100%', 'monamedia'); ?></span>
                <span class="tsh-paytype__amt"><?= wc_price($full) ?></span>
            </label>
            <label class="tsh-paytype__opt<?= $type === 'deposit' ? ' is-active' : '' ?>">
                <input type="radio" name="tsh_paytype" value="deposit" <?php checked($type, 'deposit'); ?>>
                <span class="tsh-paytype__main"><?php esc_html_e('Đặt cọc 50%', 'monamedia'); ?></span>
                <span class="tsh-paytype__amt"><?= wc_price($deposit) ?></span>
                <span class="tsh-paytype__note"><?php printf(esc_html__('Còn lại %s thu tại cơ sở', 'monamedia'), wp_kses_post(wc_price($remaining))); ?></span>
            </label>
        </div>
        <?php
    }

    /**
     * Icon mô tả cạnh mỗi phương thức thanh toán ở checkout.
     */
    public function payment_method_icon($icon, $gateway_id): string
    {
        $icons = [
            // Chuyển khoản ngân hàng — icon ngân hàng
            'bacs' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V10"/><path d="M9 21V10"/><path d="M15 21V10"/><path d="M19 21V10"/><path d="M12 3 3 8h18z"/></svg>',
            // QR chuyển khoản (SePay) — icon mã QR
            'sepay' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3M21 14v7M14 21h3"/></svg>',
            // PayPal — icon thẻ trực tuyến
            'tsh_paypal_qr' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/></svg>',
            // Thanh toán khác — icon ví
            'tsh_cash' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V8H6a2 2 0 0 1 0-4h13v4"/><path d="M3 6v12a2 2 0 0 0 2 2h16v-6"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/></svg>',
            // SePay Credit Card — icon thẻ
            'tsh_sepay_credit' => '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/><path d="M6 15h4"/></svg>',
        ];

        return isset($icons[$gateway_id])
            ? '<span class="tsh-pay-ic">' . $icons[$gateway_id] . '</span>'
            : $icon;
    }

    public function redirect_cart_to_home(): void
    {
        if (is_cart()) {
            wp_safe_redirect(home_url('/'));
            exit;
        }
    }

    public function set_bacs_first(array $gateways): array
    {
        // Ẩn chuyển khoản ngân hàng thủ công (BACS) — thay bằng SePay (tự xác nhận)
        unset($gateways['bacs']);

        // Thứ tự hiển thị mong muốn
        $order  = ['tsh_sepay_credit', 'sepay', 'tsh_paypal_qr', 'tsh_cash'];
        $sorted = [];
        foreach ($order as $id) {
            if (isset($gateways[$id])) {
                $sorted[$id] = $gateways[$id];
                unset($gateways[$id]);
            }
        }
        // Giữ các cổng khác (nếu có) ở cuối
        return $sorted + $gateways;
    }

    public function declare_support(): void
    {
        add_theme_support('woocommerce');
    }

    public function register_endpoint(): void
    {
        add_rewrite_rule('^mua-ngay/?$', 'index.php?tsh_buy_now=1', 'top');
        add_rewrite_tag('%tsh_buy_now%', '([^&]+)');
    }

    public function handle_buy_now(): void
    {
        if (!get_query_var('tsh_buy_now')) return;

        $product_id = (int) ($_GET['product_id'] ?? 0);
        $nonce      = sanitize_text_field($_GET['nonce'] ?? '');

        if (!$product_id || !wp_verify_nonce($nonce, 'tsh_buy_now')) {
            wp_die(esc_html__('Yêu cầu không hợp lệ.', 'monamedia'), '', ['response' => 400]);
        }

        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) {
            wp_die(esc_html__('Sản phẩm không tồn tại.', 'monamedia'), '', ['response' => 404]);
        }

        $booking = $this->get_booking();
        $guests  = max(1, (int) preg_replace('/\D/', '', $booking['guests'] ?? '1'));

        WC()->cart->empty_cart();
        $added = WC()->cart->add_to_cart($product_id, 1, 0, [], ['tsh_guests' => $guests]);
        if (!$added) {
            wp_die(esc_html__('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.', 'monamedia'), '', ['response' => 400]);
        }
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }

    private function get_booking(): array
    {
        $token = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
        if (!$token) return [];
        return (array) get_transient('tsh_booking_' . $token);
    }

    public function prefill_checkout($value, string $input)
    {
        $booking = $this->get_booking();
        if (!$booking) return $value;

        $map = [
            'billing_first_name' => $booking['fullname'] ?? '',
            'billing_email'      => $booking['email']    ?? '',
            'billing_phone'      => $booking['phone']    ?? '',
        ];

        return $map[$input] ?? $value;
    }

    public function save_booking_meta(int $order_id): void
    {
        $token = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
        if (!$token) return;

        $booking = (array) get_transient('tsh_booking_' . $token);
        if (!$booking) return;

        $order = wc_get_order($order_id);
        if (!$order) return;

        $meta_map = [
            '_booking_date'       => 'date',
            '_booking_time'       => 'time',
            '_booking_location'   => 'location',
            '_booking_guests'     => 'guests',
            '_booking_instructor' => 'instructor',
            '_booking_children'   => 'children',
        ];

        foreach ($meta_map as $meta_key => $key) {
            if (!empty($booking[$key])) {
                $order->update_meta_data($meta_key, $booking[$key]);
            }
        }
        $order->save();

        delete_transient('tsh_booking_' . $token);
        setcookie('tsh_booking_token', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
    }

    /**
     * Ghi loại thanh toán + số tiền cọc/còn lại vào đơn.
     * Chạy khi tạo order (trước khi tính total), nên đọc subtotal từ cart.
     */
    public function save_payment_type_meta(\WC_Order $order, array $data): void
    {
        $type = $this->get_payment_type();
        $order->update_meta_data('_tsh_payment_type', $type);

        if ($type !== 'deposit') return;

        $cart = WC()->cart;
        $full = $cart ? (float) $cart->get_subtotal() : 0.0;
        if ($full <= 0) return;

        $remaining = round($full * 0.5);
        $deposit   = $full - $remaining;

        $order->update_meta_data('_tsh_full_amount', $full);
        $order->update_meta_data('_tsh_deposit_amount', $deposit);
        $order->update_meta_data('_tsh_remaining_amount', $remaining);
    }

    /**
     * Tính hạn e-ticket khi tạo đơn: lấy sản phẩm đầu trong cart → tra CPT gốc
     * (qua _wc_product_id) → đọc eticket_days. Nếu > 0 → lưu ngày hết hạn vào order.
     * (order chưa có line items ở hook này → đọc từ cart.)
     */
    public function save_eticket_meta(\WC_Order $order, array $data): void
    {
        $cart = WC()->cart;
        if (!$cart) return;
        $items = $cart->get_cart();
        if (empty($items)) return;

        $first      = reset($items);
        $product_id = (int) ($first['product_id'] ?? 0);
        if (!$product_id) return;

        $cpt = get_posts([
            'post_type'      => ['khoa_hoc', 'workshop', 'dich_vu'],
            'post_status'    => 'any',
            'meta_key'       => '_wc_product_id',
            'meta_value'     => $product_id,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);
        if (empty($cpt)) return;

        // Đọc thẳng meta (không dùng get_field) để khỏi phụ thuộc ACF field có đăng ký
        // ở tiến trình frontend hay không.
        $days = (int) get_post_meta($cpt[0], 'eticket_days', true);
        if ($days <= 0) return;

        // gmdate + current_time('timestamp') (giờ local) → ngày local đúng, không bị nhân đôi offset theo tz server.
        $expiry = gmdate('Y-m-d', current_time('timestamp') + $days * DAY_IN_SECONDS);
        $order->update_meta_data('_tsh_eticket_days', $days);
        $order->update_meta_data('_tsh_eticket_expiry', $expiry);
    }

    public function wrap_checkout_open(): void
    {
        echo '<div class="tsh-checkout-wrap"><div class="container">';
    }

    public function wrap_checkout_close(): void
    {
        echo '</div></div>';
    }

    public function simplify_checkout_fields(array $fields): array
    {
        $keep = ['billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone'];
        foreach (array_keys($fields['billing'] ?? []) as $key) {
            if (!in_array($key, $keep, true)) {
                unset($fields['billing'][$key]);
            }
        }
        // Last name is a hidden field auto-filled from full name — not required
        if (isset($fields['billing']['billing_last_name'])) {
            $fields['billing']['billing_last_name']['required'] = false;
        }
        unset($fields['shipping'], $fields['order']);
        return $fields;
    }

    // ── VietQR ────────────────────────────────────────────────────────────

    private function to_ascii(string $str): string
    {
        $map = [
            'á' => 'a',
            'à' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'ạ' => 'a',
            'ă' => 'a',
            'ắ' => 'a',
            'ằ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'ặ' => 'a',
            'â' => 'a',
            'ấ' => 'a',
            'ầ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ậ' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ẹ' => 'e',
            'ê' => 'e',
            'ế' => 'e',
            'ề' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ệ' => 'e',
            'í' => 'i',
            'ì' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ị' => 'i',
            'ó' => 'o',
            'ò' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ọ' => 'o',
            'ô' => 'o',
            'ố' => 'o',
            'ồ' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ộ' => 'o',
            'ơ' => 'o',
            'ớ' => 'o',
            'ờ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ợ' => 'o',
            'ú' => 'u',
            'ù' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ụ' => 'u',
            'ư' => 'u',
            'ứ' => 'u',
            'ừ' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ự' => 'u',
            'ý' => 'y',
            'ỳ' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'ỵ' => 'y',
            'đ' => 'd',
        ];
        foreach ($map as $from => $to) {
            $map[mb_strtoupper($from, 'UTF-8')] = strtoupper($to);
        }
        return strtoupper(strtr($str, $map));
    }

    private function vietqr_url(int $amount, string $add_info): string
    {
        return 'https://img.vietqr.io/image/' . TSH_BANK_ID . '-' . TSH_BANK_ACCOUNT . '-compact2.png?' . http_build_query([
            'amount'      => $amount,
            'addInfo'     => $add_info,
            'accountName' => TSH_BANK_NAME,
        ]);
    }

    // Nút copy dùng chung cho các dòng thông tin chuyển khoản
    private function copy_btn(): string
    {
        return '<button type="button" class="tsh-copy-btn" aria-label="Sao chép" title="Sao chép">'
            . '<svg class="tsh-copy-btn__ico tsh-copy-btn__ico--copy" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>'
            . '<svg class="tsh-copy-btn__ico tsh-copy-btn__ico--check" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
            . '</button>';
    }

    public function add_bacs_qr_checkout(string $description, string $payment_id): string
    {
        if (is_admin()) return $description;

        // ── BACS ─────────────────────────────────────────────────────────
        if ($payment_id === 'bacs') {
            $cart      = WC()->cart;
            $total     = $cart ? (int) round((float) $cart->get_total('edit')) : 0;
            $customer  = WC()->customer;
            $name_raw  = $customer ? trim($customer->get_billing_first_name() . ' ' . $customer->get_billing_last_name()) : '';
            $name_asc  = str_replace(' ', '', $this->to_ascii($name_raw)) ?: 'TSH';
            $phone     = preg_replace('/\D/', '', $customer ? $customer->get_billing_phone() : '');
            $amount_k  = $total > 0 ? round($total / 1000) . 'K' : '';
            $info      = 'HEAL-' . $name_asc . ($phone ? '-' . $phone : '') . ($amount_k ? '-' . $amount_k : '');
            $base      = 'https://img.vietqr.io/image/' . TSH_BANK_ID . '-' . TSH_BANK_ACCOUNT . '-compact2.png?' . http_build_query([
                'accountName' => TSH_BANK_NAME,
            ]);
            $src = $base . '&amount=' . $total . '&addInfo=' . rawurlencode($info);

            ob_start(); ?>
            <div class="tsh-bacs-checkout-wrap">
                <div class="tsh-bacs-checkout-info">
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Ngân hàng', 'monamedia'); ?></span><strong><?= esc_html(TSH_BANK_ID) ?></strong></div>
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tài khoản', 'monamedia'); ?></span><span class="tsh-bacs-qr__val"><strong><?= esc_html(TSH_BANK_ACCOUNT) ?></strong><?= $this->copy_btn() ?></span></div>
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Chủ tài khoản', 'monamedia'); ?></span><span class="tsh-bacs-qr__val"><strong><?= esc_html(TSH_BANK_NAME) ?></strong><?= $this->copy_btn() ?></span></div>
                    <div class="tsh-bacs-qr__row tsh-bacs-qr__row--ref"><span><?php esc_html_e('Nội dung CK', 'monamedia'); ?></span><span class="tsh-bacs-qr__val"><strong id="tsh-bacs-addinfo"><?= esc_html($info) ?></strong><?= $this->copy_btn() ?></span></div>
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tiền', 'monamedia'); ?></span><strong id="tsh-bacs-amount"><?= $total > 0 ? number_format($total, 0, ',', '.') . 'đ' : '—' ?></strong></div>
                </div>
                <div class="tsh-bacs-qr">
                    <img src="<?= esc_url($src) ?>" data-base="<?= esc_url($base) ?>" alt="QR chuyển khoản <?= esc_attr(TSH_BANK_ID) ?>">
                </div>
            </div>
            <script>
                jQuery(function($) {
                    function toAscii(str) {
                        str = str.replace(/đ/g, 'd').replace(/Đ/g, 'D');
                        return str.normalize('NFD').replace(/[̀-ͯ]/g, '').toUpperCase();
                    }

                    function updateBacsQr() {
                        var $img = $('.tsh-bacs-qr:not(.tsh-sepay-checkout-qr) img');
                        if (!$img.length) return;
                        var amount = parseInt($('.order-total .amount').first().text().replace(/\D/g, '')) || 0;
                        var name = toAscii(($('#billing_first_name').val() || '') + ' ' + ($('#billing_last_name').val() || '')).replace(/\s+/g, '');
                        var phone = ($('#billing_phone').val() || '').replace(/\D/g, '');
                        var amountK = amount > 0 ? Math.round(amount / 1000) + 'K' : '';
                        var addInfo = 'HEAL' + (name ? '-' + name : '') + (phone ? '-' + phone : '') + (amountK ? '-' + amountK : '');
                        $img.attr('src', $img.data('base') + '&amount=' + amount + '&addInfo=' + encodeURIComponent(addInfo));
                        $('#tsh-bacs-addinfo').text(addInfo);
                        if (amount > 0) {
                            $('#tsh-bacs-amount').text(amount.toLocaleString('vi-VN') + 'đ');
                        }
                    }
                    $(document.body).on('updated_checkout', updateBacsQr);
                    $(document).on('input', '#billing_first_name, #billing_last_name, #billing_phone', updateBacsQr);
                });
            </script>
        <?php
            return ob_get_clean();
        }

        return $description;
    }

    /**
     * Hiện khối thanh toán ở trang cảm ơn theo phương thức đã chọn.
     * Đơn đã thanh toán → success (mọi phương thức). Chưa thanh toán →
     * QR SePay (nội dung TSH{order_id}), thông tin PayPal, hoặc thông báo
     * "thanh toán khác" (nhân viên liên hệ xác nhận).
     */
    public function render_thankyou_payment(int $order_id): void
    {
        $order = wc_get_order($order_id);
        if (!$order) return;
        $method = $order->get_payment_method();
        $paid   = in_array($order->get_status(), ['processing', 'completed'], true);
        $email  = $order->get_billing_email();

        // Thanh toán khác (tiền mặt) → không hiện box thanh toán nào
        // (card đầu trang đã có "Đặt lịch thành công"). Cũng không hiện box xanh "Thanh toán thành công".
        if ($method === 'tsh_cash') {
            return;
        }

        // Đã thanh toán (SePay/PayPal) → success
        if ($paid) {
            echo '<div class="tsh-bacs-qr tsh-bacs-qr--ty tsh-bacs-qr--success"><div class="tsh-payment-confirmed tsh-payment-confirmed--full"><span>✓</span><div><p>' . esc_html__('Thanh toán thành công!', 'monamedia') . '</p><p class="tsh-payment-confirmed__sub">' . esc_html__('Email xác nhận đã gửi đến', 'monamedia') . ' <strong>' . esc_html($email) . '</strong></p></div></div></div>';
            return;
        }

        // SePay → QR TSH{order_id}, số tiền = tổng đơn
        if ($method === 'sepay') {
            $amount   = (int) round((float) $order->get_total());
            // Nội dung CK: HEAL-TÊN-SĐT-TSH{mãđơn}. Mốc chữ "TSH" ngay trước mã đơn để
            // webhook đọc đúng kể cả khi ngân hàng bỏ dấu "-" (tách mã đơn khỏi dãy SĐT).
            $name_asc = str_replace(' ', '', $this->to_ascii(trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()))) ?: 'KH';
            $phone    = preg_replace('/\D/', '', $order->get_billing_phone());
            $info     = 'HEAL-' . $name_asc . ($phone ? '-' . $phone : '') . '-TSH' . $order_id;
            $url      = $this->vietqr_url($amount, $info);
            ?>
            <div class="tsh-bacs-qr tsh-bacs-qr--ty" id="tsh-ty-sepay">
                <h3 class="tsh-bacs-qr__title"><?php esc_html_e('Quét mã để hoàn tất thanh toán', 'monamedia'); ?></h3>
                <img src="<?= esc_url($url) ?>" alt="QR SePay">
                <div class="tsh-bacs-qr__info">
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Ngân hàng', 'monamedia'); ?></span><strong><?= esc_html(TSH_BANK_ID) ?></strong></div>
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tài khoản', 'monamedia'); ?></span><span class="tsh-bacs-qr__val"><strong><?= esc_html(TSH_BANK_ACCOUNT) ?></strong><?= $this->copy_btn() ?></span></div>
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tiền', 'monamedia'); ?></span><strong><?= wc_price($amount) ?></strong></div>
                    <div class="tsh-bacs-qr__row tsh-bacs-qr__row--ref"><span><?php esc_html_e('Nội dung CK', 'monamedia'); ?></span><span class="tsh-bacs-qr__val"><strong><?= esc_html($info) ?></strong><?= $this->copy_btn() ?></span></div>
                </div>
                <div class="tsh-payment-waiting"><p><?php esc_html_e('Trang sẽ tự cập nhật sau khi nhận được thanh toán', 'monamedia'); ?></p></div>
            </div>
            <?php
            return;
        }

        // PayPal → tái dùng thankyou_page() sẵn có của gateway (QR + tên TK + email + nút xác nhận)
        if ($method === 'tsh_paypal_qr') {
            $gateways = WC()->payment_gateways() ? WC()->payment_gateways()->payment_gateways() : [];
            if (isset($gateways['tsh_paypal_qr']) && method_exists($gateways['tsh_paypal_qr'], 'thankyou_page')) {
                $gateways['tsh_paypal_qr']->thankyou_page($order_id);
            }
            return;
        }
    }

    // ── Booking meta ──────────────────────────────────────────────────────

    // ── Admin email: hiện nội dung CK nổi bật ────────────────────────────

    public function email_bacs_ref(\WC_Order $order, bool $sent_to_admin, bool $plain_text, \WC_Email $email): void
    {
        if (!$sent_to_admin || $order->get_payment_method() !== 'bacs') return;

        $name_asc  = str_replace(' ', '', $this->to_ascii(trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()))) ?: 'TSH';
        $phone     = preg_replace('/\D/', '', $order->get_billing_phone());
        $amount_k  = round((float) $order->get_total() / 1000) . 'K';
        $full_ref  = 'HEAL-' . $name_asc . ($phone ? '-' . $phone : '') . '-' . $amount_k;

        echo '<div style="margin:16px 0;padding:14px 16px;background:#fff8e1;border-left:4px solid #c2a056;font-family:sans-serif">
            <p style="margin:0 0 6px;font-size:13px;color:#666">Nội dung chuyển khoản khách sẽ ghi:</p>
            <p style="margin:0;font-size:16px;font-weight:700;color:#1b1c19;letter-spacing:.5px">' . esc_html($full_ref) . '</p>
            <p style="margin:6px 0 0;font-size:13px;color:#666">Số tiền: <strong>' . wc_price($order->get_total()) . '</strong></p>
        </div>';
    }

    // ── AJAX: khách xác nhận đã chuyển khoản ─────────────────────────────

    public function ajax_confirm_transfer(): void
    {
        $order_id = (int) ($_POST['order_id'] ?? 0);
        $key      = sanitize_text_field($_POST['order_key'] ?? '');

        $order = wc_get_order($order_id);
        if (!$order || $order->get_order_key() !== $key) {
            wp_send_json_error([], 403);
        }

        if (in_array($order->get_status(), ['pending', 'on-hold'], true)) {
            $method = $order->get_payment_method();
            $note   = $method === 'tsh_paypal_qr'
                ? 'Khách xác nhận đã thanh toán qua PayPal — chờ admin kiểm tra.'
                : 'Khách xác nhận đã chuyển khoản — chờ admin kiểm tra.';
            $order->add_order_note($note);
            $this->send_transfer_confirmed_email($order);
        }

        wp_send_json_success(['email' => $order->get_billing_email()]);
    }

    private function send_transfer_confirmed_email(\WC_Order $order): void
    {
        $admin_email = get_option('admin_email');
        $order_id    = $order->get_id();
        $name        = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
        $phone       = $order->get_billing_phone();
        $email       = $order->get_billing_email();
        $total       = wc_price($order->get_total());
        $items       = $order->get_items();
        $service     = $items ? reset($items)->get_name() : '';
        $b_date      = $order->get_meta('_booking_date');
        $b_time      = $order->get_meta('_booking_time');
        $method      = $order->get_payment_method();
        $is_paypal   = $method === 'tsh_paypal_qr';

        if ($is_paypal) {
            $gateway       = new WC_Gateway_TSH_Paypal();
            $heading       = 'Khách xác nhận đã thanh toán qua PayPal';
            $subject_label = 'Khách xác nhận PayPal';
            $payment_block = '
            <div style="margin:16px 0;padding:14px 16px;background:#fff8e1;border-left:4px solid #c2a056">
                <p style="margin:0 0 4px;font-size:12px;color:#666">Phương thức thanh toán:</p>
                <p style="margin:0;font-size:16px;font-weight:700;color:#1b1c19">PayPal — ' . esc_html($gateway->paypal_email) . '</p>
            </div>
            <p style="font-size:13px;color:#666">Vui lòng kiểm tra tài khoản PayPal và xác nhận đơn hàng.</p>';
        } else {
            $name_asc      = str_replace(' ', '', $this->to_ascii($name)) ?: 'TSH';
            $phone_raw     = preg_replace('/\D/', '', $phone);
            $amount_k      = round((float) $order->get_total() / 1000) . 'K';
            $ref           = 'HEAL-' . $name_asc . ($phone_raw ? '-' . $phone_raw : '') . '-' . $amount_k;
            $heading       = 'Khách xác nhận đã chuyển khoản';
            $subject_label = 'Khách xác nhận chuyển khoản';
            $payment_block = '
            <div style="margin:16px 0;padding:14px 16px;background:#fff8e1;border-left:4px solid #c2a056">
                <p style="margin:0 0 4px;font-size:12px;color:#666">Nội dung chuyển khoản khách ghi:</p>
                <p style="margin:0;font-size:16px;font-weight:700;letter-spacing:.5px;color:#1b1c19">' . esc_html($ref) . '</p>
            </div>
            <p style="font-size:13px;color:#666">Vui lòng kiểm tra tài khoản ACB <strong>' . esc_html(TSH_BANK_ACCOUNT) . '</strong> và xác nhận đơn hàng.</p>';
        }

        $subject = '[Đặt lịch] ' . $subject_label . ' — #' . str_pad($order_id, 5, '0', STR_PAD_LEFT) . ' ' . $name;

        $body = '
        <div style="font-family:sans-serif;max-width:560px;margin:0 auto">
            <h2 style="color:#1b1c19;border-bottom:2px solid #c2a056;padding-bottom:8px">' . $heading . '</h2>
            <table style="width:100%;border-collapse:collapse;font-size:14px">
                <tr><td style="padding:8px 0;color:#666;width:140px">Mã đơn</td><td style="padding:8px 0;font-weight:700">#' . str_pad($order_id, 5, '0', STR_PAD_LEFT) . '</td></tr>
                <tr><td style="padding:8px 0;color:#666">Khách hàng</td><td style="padding:8px 0">' . esc_html($name) . '</td></tr>
                <tr><td style="padding:8px 0;color:#666">Số điện thoại</td><td style="padding:8px 0">' . esc_html($phone) . '</td></tr>
                <tr><td style="padding:8px 0;color:#666">Email</td><td style="padding:8px 0">' . esc_html($email) . '</td></tr>
                ' . ($service ? '<tr><td style="padding:8px 0;color:#666">Dịch vụ</td><td style="padding:8px 0">' . esc_html($service) . '</td></tr>' : '') . '
                ' . ($b_date  ? '<tr><td style="padding:8px 0;color:#666">Ngày đặt</td><td style="padding:8px 0">' . esc_html($b_date) . ($b_time ? ' — ' . esc_html($b_time) : '') . '</td></tr>' : '') . '
                <tr><td style="padding:8px 0;color:#666">Số tiền</td><td style="padding:8px 0;font-weight:700;color:#c2a056">' . $total . '</td></tr>
            </table>
            ' . $payment_block . '
            <a href="' . esc_url(admin_url('post.php?post=' . $order_id . '&action=edit')) . '" style="display:inline-block;margin-top:8px;padding:10px 20px;background:#c2a056;color:#fff;text-decoration:none;border-radius:4px;font-weight:600">Xem đơn hàng</a>
        </div>';

        wp_mail($admin_email, $subject, $body, ['Content-Type: text/html; charset=UTF-8']);
    }

    // ── Checkout: ẩn Place Order khi chọn BACS ───────────────────────────

    public function checkout_bacs_js(): void
    {
        if (!is_checkout() || is_order_received_page()) return;
        ?>
        <script>
            jQuery(function($) {
                function showPaymentBox(method) {
                    $('.payment_box').hide();
                    $('.payment_box.payment_method_' + method).show();
                }

                // Master/Credit Card — placeholder chờ BCT: disable radio
                function disableMasterCard() {
                    var $mc = $('#payment_method_tsh_sepay_credit');
                    if (!$mc.length) return;
                    $mc.prop('disabled', true).closest('li').addClass('tsh-pm-disabled');
                    var $label = $mc.closest('li').find('label[for="payment_method_tsh_sepay_credit"]');
                    if ($label.length && !$label.find('.tsh-soon-badge').length) {
                        var $badge = $('<span class="tsh-soon-badge"><?= esc_js(__('Sắp ra mắt', 'monamedia')) ?></span>');
                        var $icon = $label.find('.tsh-pay-ic');
                        $icon.length ? $badge.insertBefore($icon) : $label.append(' ', $badge);
                    }
                    if ($mc.is(':checked')) $mc.prop('checked', false);
                }

                // Không active cổng nào khi mới vào — khách phải tự click chọn
                var userPicked = false;
                $(document.body).on('click change', 'input[name="payment_method"]:not(:disabled)', function() {
                    userPicked = true;
                });

                function refreshPayments() {
                    disableMasterCard();
                    if (!userPicked || !$('input[name="payment_method"]:checked').length) {
                        $('input[name="payment_method"]').prop('checked', false);
                        $('.payment_box').hide();
                        return;
                    }
                    showPaymentBox($('input[name="payment_method"]:checked').val());
                }

                refreshPayments();
                $(document.body).on('payment_method_selected updated_checkout', refreshPayments);
            });
        </script>
        <?php
    }

    public function payment_type_js(): void
    {
        if (!is_checkout() || is_order_received_page()) return;
        ?>
        <script>
            jQuery(function($) {
                // Đổi radio cọc/full → WooCommerce tính lại tổng (fee đọc từ post_data).
                $(document.body).on('change', 'input[name="tsh_paytype"]', function() {
                    $('.tsh-paytype__opt').removeClass('is-active');
                    $(this).closest('.tsh-paytype__opt').addClass('is-active');
                    $(document.body).trigger('update_checkout');
                });
            });
        </script>
        <?php
    }

    // ── Thank you: polling tự động xác nhận SePay ────────────────────────

    public function thankyou_polling_js(): void
    {
        if (!is_order_received_page()) return;
        global $wp;
        $order_id = absint($wp->query_vars['order-received'] ?? 0);
        $order    = $order_id ? wc_get_order($order_id) : null;
        if (!$order || !in_array($order->get_payment_method(), ['sepay', 'tsh_paypal_qr'], true)) return;
        if (!in_array($order->get_status(), ['pending', 'on-hold'], true)) return;
        $email = $order->get_billing_email();
        ?>
        <script>
            jQuery(function($) {
                var ajaxUrl = '<?= esc_js(admin_url('admin-ajax.php')) ?>';
                var orderId = <?= (int) $order_id ?>;
                var orderKey = '<?= esc_js($order->get_order_key()) ?>';
                var successHtml = '<div class="tsh-bacs-qr tsh-bacs-qr--ty tsh-bacs-qr--success"><div class="tsh-payment-confirmed tsh-payment-confirmed--full"><span>✓</span><div><p><?= esc_js(__('Thanh toán thành công!', 'monamedia')) ?></p><p class="tsh-payment-confirmed__sub"><?= esc_js(__('Email xác nhận đã gửi đến', 'monamedia')) ?> <strong><?= esc_js($email) ?></strong></p></div></div></div>';
                var timer;

                // PayPal: nút "Tôi đã thanh toán"
                $('#tsh-confirm-transfer').on('click', function() {
                    var $btn = $(this);
                    $btn.prop('disabled', true).text('<?= esc_js(__('Đang gửi xác nhận...', 'monamedia')) ?>');
                    $.post(ajaxUrl, { action: 'tsh_confirm_transfer', order_id: orderId, order_key: orderKey })
                        .always(function() { $btn.hide(); $('#tsh-transfer-msg').show(); });
                });

                // SePay: polling → swap tại chỗ (không reload)
                function check() {
                    $.get(ajaxUrl, { action: 'tsh_order_status', order_id: orderId, order_key: orderKey })
                        .done(function(res) {
                            if (!res.success) return;
                            var s = res.data.status;
                            if (s === 'processing' || s === 'completed') {
                                clearInterval(timer);
                                $('#tsh-ty-sepay').replaceWith(successHtml);
                            }
                        });
                }
                if ($('#tsh-ty-sepay').length) timer = setInterval(check, 5000);
            });
        </script>
        <?php
    }

    // ── Admin new order email ─────────────────────────────────────────────

    public function prevent_duplicate_new_order_email(bool $enabled, $order, $email): bool
    {
        if (!$enabled || !$order instanceof \WC_Order) return $enabled;

        if ($order->get_meta('_tsh_new_order_email_sent')) {
            return false;
        }
        $order->update_meta_data('_tsh_new_order_email_sent', '1');
        $order->save_meta_data();
        return true;
    }

    /**
     * Không gửi email on-hold cho KHÁCH (đơn chưa thanh toán). Email "cảm ơn"
     * chỉ gửi khi payment_complete() (customer processing email).
     */
    public function disable_customer_onhold_email(bool $enabled, $order): bool
    {
        return false;
    }

    private function order_booking_heading(\WC_Order $order): string
    {
        $items   = $order->get_items();
        $service = $items ? reset($items)->get_name() : '';
        $name    = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
        $date    = $order->get_meta('_booking_date');

        $parts = array_filter([$name ?: '', $service ?: '', $date ?: '']);
        return implode(' — ', $parts);
    }

    public function new_order_email_heading($heading, $order): string
    {
        if (!$order instanceof \WC_Order) return $heading;
        $custom = $this->order_booking_heading($order);
        return $custom ?: $heading;
    }

    public function new_order_email_subject($subject, $order): string
    {
        if (!$order instanceof \WC_Order) return $subject;
        $custom = $this->order_booking_heading($order);
        return $custom ? '[Đặt lịch] ' . $custom : $subject;
    }

    public function customer_email_subject($subject, $order): string
    {
        if (!$order instanceof \WC_Order) return $subject;
        $items   = $order->get_items();
        $service = $items ? reset($items)->get_name() : '';
        $date    = $order->get_meta('_booking_date');
        $time    = $order->get_meta('_booking_time');

        $parts = array_filter([$service ?: '', $date ?: '', $time ?: '']);
        return $parts ? '[Đặt lịch] ' . implode(' — ', $parts) : $subject;
    }

    // ── Guests pricing ────────────────────────────────────────────────────

    public function restore_guests_cart_item(array $cart_item, array $values): array
    {
        if (!empty($values['tsh_guests'])) {
            $cart_item['tsh_guests'] = (int) $values['tsh_guests'];
        }
        return $cart_item;
    }

    public function apply_guests_price(\WC_Cart $cart): void
    {
        if (is_admin() && !defined('DOING_AJAX')) return;
        foreach ($cart->get_cart() as $item) {
            $guests = (int) ($item['tsh_guests'] ?? 1);
            if ($guests <= 1) continue;
            $base = (float) $item['data']->get_regular_price();
            if ($base > 0) {
                $item['data']->set_price($base * $guests);
            }
        }
    }

    // ── Đặt cọc 50% / thanh toán 100% ─────────────────────────────────────

    private function get_payment_type(): string
    {
        $session = WC()->session;
        $type = $session ? (string) $session->get('tsh_payment_type') : '';
        return $type === 'deposit' ? 'deposit' : 'full';
    }

    /**
     * Đọc lựa chọn cọc từ post_data của update_order_review (field tsh_paytype
     * trong form checkout) → set session. Thay cho ajax tsh_set_payment_type.
     */
    public function sync_payment_type_from_post(string $posted_data): void
    {
        parse_str($posted_data, $arr);
        $type = ($arr['tsh_paytype'] ?? '') === 'deposit' ? 'deposit' : 'full';
        if (WC()->session) {
            WC()->session->set('tsh_payment_type', $type);
        }
    }

    /**
     * Lúc submit đặt lịch: chốt lại session theo field tsh_paytype đã post,
     * để apply_deposit_fee tính đúng khi tạo đơn.
     */
    public function sync_payment_type_on_process(): void
    {
        $type = ($_POST['tsh_paytype'] ?? '') === 'deposit' ? 'deposit' : 'full';
        if (WC()->session) {
            WC()->session->set('tsh_payment_type', $type);
        }
    }

    /**
     * Tính subtotal (giá×số người×số lượng) từ item ngay trong hook fee.
     * KHÔNG dùng $cart->get_subtotal() ở đây: trong woocommerce_cart_calculate_fees,
     * reset_totals() đã zero toàn bộ totals và subtotal chưa được ghi lại → luôn = 0.
     * Đọc trực tiếp giá item (đã gồm giá×số người do apply_guests_price set trước đó).
     */
    private function cart_items_subtotal(\WC_Cart $cart): float
    {
        $subtotal = 0.0;
        foreach ($cart->get_cart() as $item) {
            if (empty($item['data']) || !is_object($item['data'])) continue;
            $subtotal += (float) $item['data']->get_price() * (int) ($item['quantity'] ?? 1);
        }
        return $subtotal;
    }

    /**
     * Khi chọn cọc 50%: thêm phí âm = -round(subtotal*0.5) để tổng đơn còn 50%.
     * subtotal đã gồm giá×số người (apply_guests_price chạy trước ở
     * woocommerce_before_calculate_totals).
     */
    public function apply_deposit_fee(\WC_Cart $cart): void
    {
        if (is_admin() && !defined('DOING_AJAX')) return;
        if ($this->get_payment_type() !== 'deposit') return;

        $full = $this->cart_items_subtotal($cart);
        if ($full <= 0) return;

        $remaining = round($full * 0.5);
        if ($remaining <= 0) return;

        $cart->add_fee(__('Đặt cọc 50% (thanh toán phần còn lại tại cơ sở)', 'monamedia'), -$remaining);
    }

    public function display_guests_in_cart(array $item_data, array $cart_item): array
    {
        $guests = (int) ($cart_item['tsh_guests'] ?? 1);
        if ($guests > 1) {
            $item_data[] = [
                'key'   => __('Số người tham gia', 'monamedia'),
                'value' => $guests . ' ' . __('người', 'monamedia'),
            ];
        }
        return $item_data;
    }

    // ── Booking meta ──────────────────────────────────────────────────────

    public function display_booking_meta(\WC_Order $order): void
    {
        $labels = [
            '_booking_date'       => 'Ngày đặt',
            '_booking_time'       => 'Khung giờ',
            '_booking_location'   => 'Chi nhánh',
            '_booking_guests'     => 'Số người',
            '_booking_instructor' => 'Người hướng dẫn',
            '_booking_children'   => 'Trẻ em tham gia',
        ];

        $rows = [];
        foreach ($labels as $key => $label) {
            $val = $order->get_meta($key);
            if ($val) $rows[] = '<strong>' . esc_html($label) . ':</strong> ' . esc_html($val);
        }

        if (!$rows) return;

        echo '<div class="tsh-booking-meta" style="margin-top:12px"><h4>Thông tin đặt lịch</h4><p>' . implode('<br>', $rows) . '</p></div>';
    }

    /**
     * Hiển thị trạng thái e-ticket trong đơn (admin). Luôn hiện 1 dòng để dễ kiểm tra:
     * có hạn → "hết hạn dd/mm/yyyy"; không có → "chưa phát hành".
     */
    public function display_eticket_admin(\WC_Order $order): void
    {
        $expiry = $order->get_meta('_tsh_eticket_expiry');
        if ($expiry) {
            $days = (int) $order->get_meta('_tsh_eticket_days');
            echo '<div class="tsh-eticket-admin" style="margin-top:12px;padding:10px 12px;background:#eef6ff;border-left:4px solid #2271b1"><strong>E-ticket quà tặng:</strong> hết hạn <strong>' . esc_html(date_i18n('d/m/Y', strtotime($expiry))) . '</strong>' . ($days ? ' (' . (int) $days . ' ngày)' : '') . '</div>';
        } else {
            echo '<div class="tsh-eticket-admin" style="margin-top:12px;padding:10px 12px;background:#f6f7f7;border-left:4px solid #c3c4c7;color:#777"><strong>E-ticket quà tặng:</strong> chưa phát hành (dịch vụ chưa set số ngày, hoặc đơn tạo trước khi set).</div>';
        }
    }

    // ── Hiển thị số tiền còn lại (đơn đặt cọc) ────────────────────────────

    private function deposit_notice_html(\WC_Order $order): string
    {
        if ($order->get_meta('_tsh_payment_type') !== 'deposit') return '';
        $deposit   = (float) $order->get_meta('_tsh_deposit_amount');
        $remaining = (float) $order->get_meta('_tsh_remaining_amount');
        if ($remaining <= 0) return '';

        return sprintf(
            /* translators: 1: số đã cọc, 2: số còn lại */
            __('Đã đặt cọc %1$s. Còn lại %2$s thu tại cơ sở khi tham gia.', 'monamedia'),
            html_entity_decode(wp_strip_all_tags(wc_price($deposit)), ENT_QUOTES, 'UTF-8'),
            html_entity_decode(wp_strip_all_tags(wc_price($remaining)), ENT_QUOTES, 'UTF-8')
        );
    }

    public function display_deposit_admin(\WC_Order $order): void
    {
        $msg = $this->deposit_notice_html($order);
        if (!$msg) return;
        echo '<div class="tsh-deposit-admin" style="margin-top:12px;padding:10px 12px;background:#fff8e1;border-left:4px solid #c2a056"><strong>' . esc_html__('Đặt cọc', 'monamedia') . ':</strong> ' . esc_html($msg) . '</div>';
    }

    public function email_deposit_notice(\WC_Order $order, bool $sent_to_admin, bool $plain_text, \WC_Email $email): void
    {
        $msg = $this->deposit_notice_html($order);
        if (!$msg) return;
        if ($plain_text) {
            echo "\n" . $msg . "\n";
            return;
        }
        echo '<div style="margin:16px 0;padding:14px 16px;background:#fff8e1;border-left:4px solid #c2a056;font-family:sans-serif;color:#1b1c19">' . esc_html($msg) . '</div>';
    }
}

new TSH_WooCommerce_Hook();
