<?php
defined('ABSPATH') || exit;

if (!class_exists('WC_Payment_Gateway')) {
    return;
}

add_filter('woocommerce_payment_gateways', function (array $gateways): array {
    $gateways[] = 'WC_Gateway_TSH_Cash';
    return $gateways;
});

class WC_Gateway_TSH_Cash extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'tsh_cash';
        $this->method_title       = 'Tiền mặt (Thủ công)';
        $this->method_description = 'Khách thanh toán tiền mặt trực tiếp. Admin kiểm tra và duyệt đơn.';
        $this->has_fields         = true;

        $this->init_form_fields();
        $this->init_settings();

        $this->title       = $this->get_option('title',       'Thanh toán khác');
        $this->description = $this->get_option('description',  '');

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        add_action('woocommerce_thankyou_' . $this->id, [$this, 'thankyou_page']);
    }

    public function init_form_fields(): void {
        $this->form_fields = [
            'enabled' => [
                'title'   => 'Bật/Tắt',
                'type'    => 'checkbox',
                'label'   => 'Bật thanh toán tiền mặt',
                'default' => 'yes',
            ],
            'title' => [
                'title'   => 'Tiêu đề hiển thị',
                'type'    => 'text',
                'default' => 'Thanh toán khác',
            ],
            'description' => [
                'title'   => 'Mô tả',
                'type'    => 'textarea',
                'default' => '',
            ],
        ];
    }

    public function payment_fields(): void {
        if (!$this->description) return;
        echo '<div class="tsh-cash-note">'
            . '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>'
            . '<p>' . esc_html($this->description) . '</p>'
            . '</div>';
    }

    public function process_payment($order_id): array {
        $order = wc_get_order($order_id);
        $order->update_status('on-hold', 'Chờ thanh toán tiền mặt khi khách đến.');
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
                        <p><?php esc_html_e('Đặt lịch thành công!', 'monamedia'); ?></p>
                        <p class="tsh-payment-confirmed__sub"><?php esc_html_e('Email xác nhận đã gửi đến', 'monamedia'); ?> <strong><?= esc_html($order->get_billing_email()) ?></strong></p>
                    </div>
                </div>
            </div>
            <?php
            return;
        }
        ?>
        <div class="tsh-bacs-qr tsh-bacs-qr--ty tsh-cash-ty">
            <h3 class="tsh-bacs-qr__title"><?php esc_html_e('Đặt lịch thành công', 'monamedia'); ?></h3>
            <div class="tsh-cash-ty__body">
                <p><?php echo wp_kses_post(__('Bạn đã chọn thanh toán bằng <strong>tiền mặt</strong>. Vui lòng thanh toán trực tiếp khi đến sử dụng dịch vụ.', 'monamedia')); ?></p>
                <p><?php echo wp_kses_post(__('Chúng tôi sẽ liên hệ xác nhận lịch hẹn trong vòng <strong>2 giờ</strong>.', 'monamedia')); ?></p>
                <div class="tsh-bacs-qr__info">
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tiền cần thanh toán', 'monamedia'); ?></span><strong><?= wc_price($order->get_total()) ?></strong></div>
                </div>
                <p class="tsh-cash-ty__note"><?php esc_html_e('Email xác nhận gửi đến:', 'monamedia'); ?> <strong><?= esc_html($order->get_billing_email()) ?></strong></p>
            </div>
        </div>
        <?php
    }
}
