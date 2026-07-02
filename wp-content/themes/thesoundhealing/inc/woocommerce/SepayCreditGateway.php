<?php
defined('ABSPATH') || exit;

if (!class_exists('WC_Payment_Gateway')) {
    return;
}

add_filter('woocommerce_payment_gateways', function (array $gateways): array {
    $gateways[] = 'WC_Gateway_TSH_Sepay_Credit';
    return $gateways;
});

/**
 * Placeholder gateway — chỉ hiển thị nút để người dùng biết sắp có thanh toán thẻ SePay.
 * Chưa triển khai xử lý thực; chọn + đặt hàng sẽ báo "sắp ra mắt".
 * has_fields = false + không mô tả → KHÔNG render payment_box (chỉ hiện radio + tiêu đề).
 */
class WC_Gateway_TSH_Sepay_Credit extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'tsh_sepay_credit';
        $this->method_title       = 'Master/Credit Card';
        $this->method_description = 'Thanh toán thẻ Master/Credit Card (placeholder — chờ có giấy phép BCT rồi triển khai).';
        $this->has_fields         = false;

        $this->init_form_fields();
        $this->init_settings();

        $this->title       = $this->get_option('title', 'Master/Credit Card');
        $this->description = '';

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
    }

    public function init_form_fields(): void {
        $this->form_fields = [
            'enabled' => [
                'title'   => 'Bật/Tắt',
                'type'    => 'checkbox',
                'label'   => 'Hiển thị Master/Credit Card',
                'default' => 'yes',
            ],
            'title' => [
                'title'   => 'Tiêu đề hiển thị',
                'type'    => 'text',
                'default' => 'Master/Credit Card',
            ],
        ];
    }

    public function process_payment($order_id): array {
        // Chưa triển khai — chặn hoàn tất đơn và báo người dùng.
        wc_add_notice(
            __('Phương thức thanh toán thẻ SePay sắp ra mắt. Vui lòng chọn phương thức khác.', 'monamedia'),
            'error'
        );
        return ['result' => 'failure'];
    }
}
