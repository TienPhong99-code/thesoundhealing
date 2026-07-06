<?php
defined('ABSPATH') || exit;

class TSH_Sepay_Webhook {

    public function __construct() {
        add_action('rest_api_init', [$this, 'register_route']);
        add_action('wp_ajax_nopriv_tsh_order_status', [$this, 'ajax_order_status']);
        add_action('wp_ajax_tsh_order_status',        [$this, 'ajax_order_status']);
    }

    public function register_route(): void {
        register_rest_route('tsh/v1', '/sepay', [
            'methods'             => 'POST',
            'callback'            => [$this, 'handle'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle(WP_REST_Request $request): WP_REST_Response {
        // SePay gửi: Authorization: Apikey YOUR_KEY
        $auth    = $request->get_header('Authorization') ?? '';
        $api_key = trim(str_ireplace('Apikey', '', $auth));

        if (!defined('TSH_SEPAY_API_KEY') || $api_key !== TSH_SEPAY_API_KEY) {
            return new WP_REST_Response(['error' => 'Unauthorized'], 401);
        }

        $body = $request->get_json_params();

        if (($body['transferType'] ?? '') !== 'in') {
            return new WP_REST_Response(['message' => 'skipped'], 200);
        }

        $content     = $body['content'] ?? '';
        $transferred = (float) ($body['transferAmount'] ?? 0);

        // Trường hợp 1: QR thank you — nội dung "HEAL-TÊN-SĐT-[order_id]" (mã đơn ở cuối)
        // hoặc "TSH[order_id]" (tương thích ngược). Mã đơn là nhóm số cuối chuỗi.
        $order_id = 0;
        if (preg_match('/HEAL.*?(\d+)\s*$/i', $content, $matches)) {
            $order_id = (int) $matches[1];
        } elseif (preg_match('/TSH\s*(\d+)/i', $content, $matches)) {
            $order_id = (int) $matches[1];
        }
        if ($order_id) {
            $order = wc_get_order($order_id);

            if (!$order) {
                return new WP_REST_Response(['error' => 'Order not found'], 404);
            }

            if (!in_array($order->get_status(), ['pending', 'on-hold'], true)) {
                return new WP_REST_Response(['message' => 'already processed'], 200);
            }

            if ($transferred < (float) $order->get_total()) {
                return new WP_REST_Response(['error' => 'Insufficient amount'], 400);
            }

            $order->payment_complete();
            $order->add_order_note(sprintf(
                'SePay xác nhận: +%s đ — Nội dung: "%s"',
                number_format($transferred, 0, ',', '.'),
                sanitize_text_field($content)
            ));

            return new WP_REST_Response(['success' => true, 'order_id' => $order_id], 200);
        }

        // Trường hợp 2: QR checkout — nội dung chứa "TSHCK[8 hex]"
        if (preg_match('/TSHCK([0-9A-F]{8})/i', $content, $tm)) {
            $token    = strtoupper($tm[1]);
            $existing = get_transient('tsh_sepay_tk_' . $token);
            if (!$existing || empty($existing['paid'])) {
                set_transient('tsh_sepay_tk_' . $token, [
                    'paid'   => true,
                    'amount' => $transferred,
                ], 3600);
            }
            return new WP_REST_Response(['success' => true, 'type' => 'checkout_token'], 200);
        }

        return new WP_REST_Response(['error' => 'No match found'], 400);
    }

    public function ajax_order_status(): void {
        $order_id = (int) ($_GET['order_id'] ?? 0);
        $key      = sanitize_text_field($_GET['order_key'] ?? '');

        $order = wc_get_order($order_id);
        if (!$order || $order->get_order_key() !== $key) {
            wp_send_json_error([], 403);
        }

        wp_send_json_success(['status' => $order->get_status()]);
    }
}

new TSH_Sepay_Webhook();
