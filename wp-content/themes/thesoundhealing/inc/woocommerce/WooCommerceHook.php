<?php
defined('ABSPATH') || exit;

class TSH_WooCommerce_Hook {

    public function __construct() {
        add_action('after_setup_theme',   [$this, 'declare_support']);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        add_action('init',                [$this, 'register_endpoint']);
        add_action('template_redirect',   [$this, 'handle_buy_now']);
        add_filter('woocommerce_checkout_get_value', [$this, 'prefill_checkout'], 10, 2);
        add_action('woocommerce_checkout_order_processed', [$this, 'save_booking_meta']);
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'display_booking_meta']);
        add_filter('woocommerce_checkout_fields', [$this, 'simplify_checkout_fields']);
    }

    public function declare_support(): void {
        add_theme_support('woocommerce');
    }

    public function register_endpoint(): void {
        add_rewrite_rule('^mua-ngay/?$', 'index.php?tsh_buy_now=1', 'top');
        add_rewrite_tag('%tsh_buy_now%', '([^&]+)');
    }

    public function handle_buy_now(): void {
        if (!get_query_var('tsh_buy_now')) return;

        $product_id = (int) ($_GET['product_id'] ?? 0);
        $nonce      = sanitize_text_field($_GET['nonce'] ?? '');

        if (!$product_id || !wp_verify_nonce($nonce, 'tsh_buy_now')) {
            wp_die('Yêu cầu không hợp lệ.', '', ['response' => 400]);
        }

        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) {
            wp_die('Sản phẩm không tồn tại.', '', ['response' => 404]);
        }

        WC()->cart->empty_cart();
        $added = WC()->cart->add_to_cart($product_id, 1);
        if (!$added) {
            wp_die('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.', '', ['response' => 400]);
        }
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }

    private function get_booking(): array {
        $token = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
        if (!$token) return [];
        return (array) get_transient('tsh_booking_' . $token);
    }

    public function prefill_checkout($value, string $input) {
        $booking = $this->get_booking();
        if (!$booking) return $value;

        $map = [
            'billing_first_name' => $booking['fullname'] ?? '',
            'billing_email'      => $booking['email']    ?? '',
            'billing_phone'      => $booking['phone']    ?? '',
        ];

        return $map[$input] ?? $value;
    }

    public function save_booking_meta(int $order_id): void {
        $token = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
        if (!$token) return;

        $booking = (array) get_transient('tsh_booking_' . $token);
        if (!$booking) return;

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
                update_post_meta($order_id, $meta_key, $booking[$key]);
            }
        }

        delete_transient('tsh_booking_' . $token);
        setcookie('tsh_booking_token', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
    }

    public function wrap_checkout_open(): void {
        echo '<div class="tsh-checkout-wrap"><div class="container">';
    }

    public function wrap_checkout_close(): void {
        echo '</div></div>';
    }

    public function simplify_checkout_fields(array $fields): array {
        $keep = ['billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone'];
        foreach (array_keys($fields['billing'] ?? []) as $key) {
            if (!in_array($key, $keep, true)) {
                unset($fields['billing'][$key]);
            }
        }
        unset($fields['shipping'], $fields['order']);
        return $fields;
    }

    public function display_booking_meta(\WC_Order $order): void {
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
}

new TSH_WooCommerce_Hook();
