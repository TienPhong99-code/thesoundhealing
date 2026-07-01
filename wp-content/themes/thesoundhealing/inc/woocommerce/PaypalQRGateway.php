<?php
defined('ABSPATH') || exit;

if (!class_exists('WC_Payment_Gateway')) {
    return;
}

add_filter('woocommerce_payment_gateways', function (array $gateways): array {
    $gateways[] = 'WC_Gateway_TSH_Paypal';
    return $gateways;
});

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

    // Nút copy dùng chung cho các dòng thông tin PayPal
    private function copy_btn(): string {
        return '<button type="button" class="tsh-copy-btn" aria-label="Sao chép" title="Sao chép">'
            . '<svg class="tsh-copy-btn__ico tsh-copy-btn__ico--copy" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>'
            . '<svg class="tsh-copy-btn__ico tsh-copy-btn__ico--check" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>'
            . '</button>';
    }

    public function payment_fields(): void {
        ob_start(); ?>
        <div class="tsh-bacs-checkout-wrap tsh-paypal-checkout-wrap">
            <div class="tsh-bacs-checkout-info">
                <?php if ($this->description) : ?>
                    <p class="tsh-paypal-desc"><?= esc_html($this->description) ?></p>
                <?php endif; ?>
                <?php if ($this->paypal_name) : ?>
                    <div class="tsh-bacs-qr__row"><span>Tên tài khoản</span><span class="tsh-bacs-qr__val"><strong><?= esc_html($this->paypal_name) ?></strong><?= $this->copy_btn() ?></span></div>
                <?php endif; ?>
                <?php if ($this->paypal_email) : ?>
                    <div class="tsh-bacs-qr__row"><span>Email PayPal</span><span class="tsh-bacs-qr__val"><strong><?= esc_html($this->paypal_email) ?></strong><?= $this->copy_btn() ?></span></div>
                <?php endif; ?>
            </div>
            <?php if ($this->qr_image) : ?>
            <div class="tsh-bacs-qr tsh-paypal-qr">
                <img src="<?= esc_url($this->qr_image) ?>" alt="PayPal QR">
            </div>
            <?php endif; ?>
        </div>
        <?php
        echo ob_get_clean();
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
                <div class="tsh-bacs-qr__row"><span>Tên tài khoản</span><span class="tsh-bacs-qr__val"><strong><?= esc_html($this->paypal_name) ?></strong><?= $this->copy_btn() ?></span></div>
                <?php endif; ?>
                <?php if ($this->paypal_email) : ?>
                <div class="tsh-bacs-qr__row"><span>Email PayPal</span><span class="tsh-bacs-qr__val"><strong><?= esc_html($this->paypal_email) ?></strong><?= $this->copy_btn() ?></span></div>
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

