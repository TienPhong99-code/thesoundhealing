<?php
defined('ABSPATH') || exit;

class WC_Gateway_TSH_Paypal extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'tsh_paypal_qr';
        $this->method_title       = 'PayPal QR (Thủ công)';
        $this->method_description = 'Khách quét QR PayPal rồi xác nhận thủ công. Admin kiểm tra và duyệt đơn.';
        $this->has_fields         = true;

        $this->init_form_fields();
        $this->init_settings();

        $this->title        = $this->get_option('title',        'Thanh toán qua PayPal');
        $this->description  = $this->get_option('description',  '');
        $this->paypal_name  = $this->get_option('paypal_name',  '');
        $this->paypal_email = $this->get_option('paypal_email', '');
        $this->qr_image     = $this->get_option('qr_image',     '');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_thankyou_' . $this->id, [$this, 'thankyou_page']);
        add_filter('woocommerce_payment_gateways', [$this, 'register_gateway']);
    }

    public function register_gateway(array $gateways): array {
        $gateways[] = self::class;
        return $gateways;
    }

    public function init_form_fields(): void {
        $this->form_fields = [
            'enabled' => [
                'title'   => 'Bật/Tắt',
                'type'    => 'checkbox',
                'label'   => 'Bật thanh toán PayPal QR',
                'default' => 'no',
            ],
            'title' => [
                'title'   => 'Tiêu đề hiển thị',
                'type'    => 'text',
                'default' => 'Thanh toán qua PayPal',
            ],
            'description' => [
                'title'   => 'Mô tả',
                'type'    => 'textarea',
                'default' => 'Quét mã QR PayPal để thanh toán. Sau khi thanh toán nhấn xác nhận bên dưới.',
            ],
            'paypal_name' => [
                'title'   => 'Tên tài khoản PayPal',
                'type'    => 'text',
                'default' => 'KHANH NGUYEN TRAN KIM',
            ],
            'paypal_email' => [
                'title'   => 'Email PayPal',
                'type'    => 'text',
                'default' => 'khanh.nguyen@healiverse.vn',
            ],
            'qr_image' => [
                'title'       => 'URL ảnh QR PayPal',
                'type'        => 'text',
                'description' => 'Upload ảnh QR vào Media Library rồi dán URL vào đây.',
            ],
        ];
    }

    public function payment_fields(): void {
        if ($this->description) {
            echo '<p class="tsh-paypal-desc">' . esc_html($this->description) . '</p>';
        }
        if ($this->qr_image) {
            echo '<div class="tsh-bacs-qr tsh-paypal-qr">
                <img src="' . esc_url($this->qr_image) . '" alt="PayPal QR">
                ' . ($this->paypal_email ? '<p class="tsh-bacs-qr__note">' . esc_html($this->paypal_email) . '</p>' : '') . '
            </div>';
        }
    }

    public function process_payment($order_id): array {
        $order = wc_get_order($order_id);
        $order->update_status('on-hold', 'Chờ xác nhận thanh toán PayPal QR.');
        WC()->cart->empty_cart();
        return ['result' => 'success', 'redirect' => $this->get_return_url($order)];
    }

    public function thankyou_page(int $order_id): void {
        $order = wc_get_order($order_id);
        if (!$order) return;

        if (in_array($order->get_status(), ['processing', 'completed'], true)) {
            ?>
            <div class="tsh-bacs-qr tsh-bacs-qr--ty tsh-bacs-qr--success">
                <div class="tsh-payment-confirmed tsh-payment-confirmed--full">
                    <span>✓</span>
                    <div>
                        <p>Thanh toán thành công!</p>
                        <p class="tsh-payment-confirmed__sub">Email xác nhận đã gửi đến <strong><?= esc_html($order->get_billing_email()) ?></strong></p>
                    </div>
                </div>
            </div>
            <?php
            return;
        }
        ?>
        <div class="tsh-bacs-qr tsh-bacs-qr--ty">
            <h3 class="tsh-bacs-qr__title">Hoàn tất thanh toán PayPal</h3>
            <?php if ($this->qr_image) : ?>
            <img src="<?= esc_url($this->qr_image) ?>" alt="PayPal QR">
            <?php endif; ?>
            <div class="tsh-bacs-qr__info">
                <?php if ($this->paypal_name) : ?>
                <div class="tsh-bacs-qr__row"><span>Tên tài khoản</span><strong><?= esc_html($this->paypal_name) ?></strong></div>
                <?php endif; ?>
                <?php if ($this->paypal_email) : ?>
                <div class="tsh-bacs-qr__row"><span>Email PayPal</span><strong><?= esc_html($this->paypal_email) ?></strong></div>
                <?php endif; ?>
                <div class="tsh-bacs-qr__row"><span>Số tiền</span><strong><?= wc_price($order->get_total()) ?></strong></div>
            </div>
            <button type="button" id="tsh-confirm-transfer"
                data-order="<?= (int) $order_id ?>"
                data-key="<?= esc_attr($order->get_order_key()) ?>"
                class="tsh-confirm-btn">
                Tôi đã thanh toán xong
            </button>
            <div id="tsh-transfer-msg" style="display:none" class="tsh-transfer-msg">
                <p>Cảm ơn bạn! Chúng tôi sẽ kiểm tra và xác nhận đặt lịch trong vòng <strong>2 giờ</strong>.</p>
                <p>Email xác nhận gửi đến: <strong><?= esc_html($order->get_billing_email()) ?></strong></p>
            </div>
        </div>
        <?php
    }
}

new WC_Gateway_TSH_Paypal();
