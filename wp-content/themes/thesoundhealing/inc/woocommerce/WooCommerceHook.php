<?php
defined('ABSPATH') || exit;

class TSH_WooCommerce_Hook {

    public function __construct() {
        add_action('after_setup_theme',   [$this, 'declare_support']);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        add_action('init',                [$this, 'register_endpoint']);
        add_action('template_redirect',   [$this, 'handle_buy_now']);
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
}

new TSH_WooCommerce_Hook();
