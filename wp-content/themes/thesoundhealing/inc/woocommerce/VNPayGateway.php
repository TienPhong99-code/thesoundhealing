<?php
defined('ABSPATH') || exit;

if (!class_exists('WC_Payment_Gateway')) {
    return;
}

add_filter('woocommerce_payment_gateways', function (array $gateways): array {
    $gateways[] = 'TSH_VNPay_Gateway';
    return $gateways;
});

class TSH_VNPay_Gateway extends WC_Payment_Gateway {

    private const SANDBOX_URL    = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    private const PRODUCTION_URL = 'https://pay.vnpayment.vn/paymentv2/vpcpay.html';

    public function __construct() {
        $this->id                 = 'tsh_vnpay';
        $this->method_title       = 'VNPAY';
        $this->method_description = 'Thanh toán qua VNPAY (ATM / QR / Ví điện tử)';
        $this->has_fields         = false;

        $this->init_form_fields();
        $this->init_settings();

        $this->title       = $this->get_option('title', 'VNPAY');
        $this->description = $this->get_option('description', 'Thanh toán an toàn qua cổng VNPAY');

        add_action("woocommerce_update_options_payment_gateways_{$this->id}", [$this, 'process_admin_options']);
        add_action('woocommerce_api_vnpay_return', [$this, 'handle_return']);
        add_action('woocommerce_api_vnpay_ipn',    [$this, 'handle_ipn']);
    }

    public function init_form_fields(): void {
        $this->form_fields = [
            'enabled' => [
                'title'   => 'Kích hoạt',
                'type'    => 'checkbox',
                'default' => 'yes',
            ],
            'title' => [
                'title'   => 'Tiêu đề hiển thị',
                'type'    => 'text',
                'default' => 'VNPAY',
            ],
            'description' => [
                'title'   => 'Mô tả',
                'type'    => 'text',
                'default' => 'Thanh toán an toàn qua cổng VNPAY',
            ],
            'tmn_code' => [
                'title' => 'vnp_TmnCode (Merchant Code)',
                'type'  => 'text',
            ],
            'hash_secret' => [
                'title' => 'vnp_HashSecret',
                'type'  => 'password',
            ],
            'environment' => [
                'title'   => 'Môi trường',
                'type'    => 'select',
                'options' => ['sandbox' => 'Sandbox (Test)', 'production' => 'Production (Live)'],
                'default' => 'sandbox',
            ],
        ];
    }

    public function process_payment($order_id): array {
        $order       = wc_get_order($order_id);
        $amount      = (int) round($order->get_total() * 100);
        $tmn_code    = $this->get_option('tmn_code');
        $hash_secret = $this->get_option('hash_secret');
        $env         = $this->get_option('environment', 'sandbox');
        $base_url    = $env === 'production' ? self::PRODUCTION_URL : self::SANDBOX_URL;

        $params = [
            'vnp_Amount'    => $amount,
            'vnp_Command'   => 'pay',
            'vnp_CreateDate'=> date('YmdHis'),
            'vnp_CurrCode'  => 'VND',
            'vnp_IpAddr'    => $this->get_client_ip(),
            'vnp_Locale'    => 'vn',
            'vnp_OrderInfo' => 'Thanh toan don hang #' . $order_id,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => home_url('/wc-api/vnpay_return'),
            'vnp_TmnCode'   => $tmn_code,
            'vnp_TxnRef'    => (string) $order_id,
            'vnp_Version'   => '2.1.0',
        ];

        ksort($params);
        $hash_data                = http_build_query($params);
        $params['vnp_SecureHash'] = hash_hmac('sha512', $hash_data, $hash_secret);

        $order->update_status('pending', 'Chờ thanh toán qua VNPAY.');

        return [
            'result'   => 'success',
            'redirect' => $base_url . '?' . http_build_query($params),
        ];
    }

    public function handle_return(): void {
        $data        = $_GET; // phpcs:ignore
        $secure_hash = sanitize_text_field($data['vnp_SecureHash'] ?? '');
        unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);

        ksort($data);
        $hash_data  = http_build_query($data);
        $check_hash = hash_hmac('sha512', $hash_data, $this->get_option('hash_secret'));

        $order_id = (int) ($data['vnp_TxnRef'] ?? 0);
        $order    = wc_get_order($order_id);

        if (!$order) {
            wp_die('Order not found', '', ['response' => 404]);
        }

        if (!hash_equals($check_hash, $secure_hash)) {
            wp_redirect(wc_get_checkout_url());
            exit;
        }

        if (($data['vnp_ResponseCode'] ?? '') === '00') {
            $order->update_status('processing', 'VNPAY: thanh toán thành công. Mã GD: ' . sanitize_text_field($data['vnp_TransactionNo'] ?? ''));
            wp_redirect($order->get_checkout_order_received_url());
        } else {
            $order->update_status('failed', 'VNPAY: thanh toán thất bại. Mã lỗi: ' . sanitize_text_field($data['vnp_ResponseCode'] ?? ''));
            wc_add_notice('Thanh toán thất bại. Vui lòng thử lại hoặc chọn phương thức khác.', 'error');
            wp_redirect(wc_get_checkout_url());
        }
        exit;
    }

    public function handle_ipn(): void {
        $data        = $_GET; // phpcs:ignore
        $secure_hash = sanitize_text_field($data['vnp_SecureHash'] ?? '');
        unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);

        ksort($data);
        $hash_data  = http_build_query($data);
        $check_hash = hash_hmac('sha512', $hash_data, $this->get_option('hash_secret'));

        if (!hash_equals($check_hash, $secure_hash)) {
            wp_send_json(['RspCode' => '97', 'Message' => 'Invalid signature']);
        }

        $order_id = (int) ($data['vnp_TxnRef'] ?? 0);
        $order    = wc_get_order($order_id);
        if (!$order) {
            wp_send_json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        $expected_amount = (int) round($order->get_total() * 100);
        $received_amount = (int) ($data['vnp_Amount'] ?? 0);
        if ($expected_amount !== $received_amount) {
            wp_send_json(['RspCode' => '04', 'Message' => 'Invalid amount']);
        }

        if (($data['vnp_ResponseCode'] ?? '') === '00') {
            $order->payment_complete(sanitize_text_field($data['vnp_TransactionNo'] ?? ''));
            wp_send_json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        wp_send_json(['RspCode' => '99', 'Message' => 'Unknown error']);
    }

    private function get_client_ip(): string {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                return sanitize_text_field(explode(',', $_SERVER[$key])[0]);
            }
        }
        return '127.0.0.1';
    }
}
