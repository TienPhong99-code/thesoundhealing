# WooCommerce + VNPAY Payment Integration — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Tích hợp thanh toán VNPAY qua WooCommerce cho 3 CPT (khoa_hoc, workshop, dich_vu) theo mô hình hybrid — giữ nguyên UI/template, tự động đồng bộ CPT → WC Product, nút "ĐẶT LỊCH" trỏ thẳng đến buy-now endpoint → checkout VNPAY.

**Architecture:** Mỗi khi admin publish/update CPT post, một `save_post` hook tự tạo hoặc cập nhật WooCommerce Product tương ứng (virtual, hidden). Nút "ĐẶT LỊCH" trong card và single page sẽ trỏ đến `/mua-ngay/?product_id={wc_id}` thay vì trang detail. Endpoint này xoá giỏ hàng, thêm sản phẩm và redirect sang WooCommerce checkout với VNPAY gateway.

**Tech Stack:** WordPress, WooCommerce ≥ 8.x, PHP ≥ 8.0, ACF (vinkla/extended-acf), VNPAY API v2.1.0 (HMAC-SHA512), Tailwind CSS

## Global Constraints

- WooCommerce phải được cài và kích hoạt trước khi chạy bất kỳ task nào
- Tất cả file PHP mới phải được đăng ký trong `configs/loadFile.php`
- Sau khi thêm rewrite rule mới, phải vào WordPress Admin > Settings > Permalinks > Save để flush rewrite rules
- ACF field name cho giá: `price` (khoa_hoc), `ws_price` (workshop), `dv_price` (dich_vu)
- Nút "ĐẶT LỊCH" KHÔNG đổi text, chỉ đổi href
- Nếu giá là "Liên hệ" hoặc rỗng → giữ nguyên href trỏ trang detail (card) hoặc giữ CF7 form (single)
- Không xoá hay sửa CF7 form — chỉ thay thế bằng nút khi có WC product + price > 0

---

## File Map

| Action | File |
|--------|------|
| Create | `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php` |
| Create | `wp-content/themes/thesoundhealing/inc/woocommerce/WcProductSync.php` |
| Create | `wp-content/themes/thesoundhealing/inc/woocommerce/VNPayGateway.php` |
| Modify | `wp-content/themes/thesoundhealing/configs/loadFile.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/sections/khoa-hoc/section-list.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/sections/workshop/section-list.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/sections/dich-vu/section-list.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/components/card-khoa-hoc.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/components/card-workshop.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/components/card-dich-vu.php` |
| Modify | `wp-content/themes/thesoundhealing/inc/ajax/PostAjax.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/templates/single/single-khoa_hoc.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/templates/single/single-workshop.php` |
| Modify | `wp-content/themes/thesoundhealing/partials/templates/single/single-dich_vu.php` |

---

## Task 1: WooCommerce Foundation + Buy Now Endpoint

**Files:**
- Create: `inc/woocommerce/WooCommerceHook.php`
- Modify: `configs/loadFile.php`

**Interfaces:**
- Produces: endpoint `/mua-ngay/` nhận `?product_id={id}&nonce={nonce}`, clear cart, add to cart, redirect checkout

- [ ] **Step 1: Tạo thư mục và file**

```bash
mkdir -p wp-content/themes/thesoundhealing/inc/woocommerce
```

- [ ] **Step 2: Viết WooCommerceHook.php**

Tạo file `inc/woocommerce/WooCommerceHook.php`:

```php
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
        WC()->cart->add_to_cart($product_id, 1);
        wp_redirect(wc_get_checkout_url());
        exit;
    }
}

new TSH_WooCommerce_Hook();
```

- [ ] **Step 3: Đăng ký WooCommerceHook.php trong loadFile.php**

Mở `configs/loadFile.php`, thêm 1 dòng vào cuối mảng (trước dấu `]`):

```php
    // WooCommerce
    MONA_THEME_INC_PATH . '/woocommerce/WooCommerceHook.php',
```

> `WcProductSync.php` và `VNPayGateway.php` sẽ được đăng ký trong Task 2 và Task 3 tương ứng, sau khi file đã được tạo.

- [ ] **Step 4: Flush rewrite rules**

Vào WordPress Admin > Settings > Permalinks > click **Save Changes** (không cần đổi gì, chỉ cần save).

- [ ] **Step 5: Verify endpoint hoạt động**

Mở trình duyệt, truy cập: `https://your-site.local/mua-ngay/?product_id=1&nonce=invalid`

Expected: Trang hiện `"Yêu cầu không hợp lệ."` (không phải 404).

- [ ] **Step 6: Commit**

```bash
git add inc/woocommerce/WooCommerceHook.php configs/loadFile.php
git commit -m "feat: add WooCommerce theme support and buy-now endpoint"
```

---

## Task 2: CPT → WooCommerce Product Auto-Sync

**Files:**
- Create: `inc/woocommerce/WcProductSync.php`

**Interfaces:**
- Consumes: ACF fields `price` (khoa_hoc), `ws_price` (workshop), `dv_price` (dich_vu) từ post
- Produces: post meta `_wc_product_id` (int) trên CPT post; `TSH_WC_Product_Sync::parse_price(string): float` (public static, dùng lại ở Task 3 và Task 4)

- [ ] **Step 1: Tạo WcProductSync.php**

```php
<?php
defined('ABSPATH') || exit;

class TSH_WC_Product_Sync {

    private const POST_TYPES = ['khoa_hoc', 'workshop', 'dich_vu'];
    public  const META_KEY   = '_wc_product_id';

    private const PRICE_FIELDS = [
        'khoa_hoc' => 'price',
        'workshop' => 'ws_price',
        'dich_vu'  => 'dv_price',
    ];

    public function __construct() {
        foreach (self::POST_TYPES as $type) {
            add_action("save_post_{$type}", [$this, 'sync'], 20, 2);
        }
    }

    public function sync(int $post_id, WP_Post $post): void {
        if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) return;
        if (!function_exists('wc_get_product')) return;

        $field     = self::PRICE_FIELDS[$post->post_type] ?? 'price';
        $price_raw = (string) (get_field($field, $post_id) ?: '');
        $price     = self::parse_price($price_raw);

        $wc_id = (int) get_post_meta($post_id, self::META_KEY, true);

        if ($wc_id && ($product = wc_get_product($wc_id))) {
            $this->update_product($product, $post, $price);
        } else {
            $wc_id = $this->create_product($post, $price);
            update_post_meta($post_id, self::META_KEY, $wc_id);
        }
    }

    public static function parse_price(string $raw): float {
        if (empty($raw) || mb_stripos($raw, 'liên hệ') !== false) return 0.0;
        $digits = preg_replace('/[^\d]/', '', $raw);
        return $digits !== '' ? (float) $digits : 0.0;
    }

    private function create_product(WP_Post $post, float $price): int {
        $product = new WC_Product_Simple();
        $product->set_name($post->post_title);
        $product->set_regular_price($price > 0 ? (string) $price : '');
        $product->set_virtual(true);
        $product->set_catalog_visibility('hidden');
        $product->set_status($post->post_status === 'publish' ? 'publish' : 'draft');
        return $product->save();
    }

    private function update_product(WC_Product $product, WP_Post $post, float $price): void {
        $product->set_name($post->post_title);
        $product->set_regular_price($price > 0 ? (string) $price : '');
        $product->set_status($post->post_status === 'publish' ? 'publish' : 'draft');
        $product->save();
    }
}

new TSH_WC_Product_Sync();
```

- [ ] **Step 2: Verify sync hoạt động**

1. Vào WordPress Admin > Khóa học > chọn bất kỳ bài nào đã publish > click **Update**
2. Mở database hoặc dùng plugin "Post Meta Inspector": kiểm tra post meta `_wc_product_id` đã có giá trị integer
3. Vào WooCommerce > Products: phải thấy 1 product mới tên giống bài khóa học, visibility = "Hidden", type = "Simple"

- [ ] **Step 3: Verify sync giá**

1. Trong admin khóa học, ACF field "Giá" đang là `"8.500.000 VNĐ"` → sau Update, WC product price = `8500000`
2. Đổi giá thành `"Liên hệ"` → Update → WC product price = empty (không có giá)

- [ ] **Step 4: Commit**

```bash
git add inc/woocommerce/WcProductSync.php
git commit -m "feat: auto-sync CPT post to WooCommerce product on save"
```

---

## Task 3: VNPAY Payment Gateway

**Files:**
- Create: `inc/woocommerce/VNPayGateway.php`

**Interfaces:**
- Consumes: WooCommerce order object
- Produces: WC payment gateway `tsh_vnpay`; handlers tại `/wc-api/vnpay_return` và `/wc-api/vnpay_ipn`

- [ ] **Step 1: Tạo VNPayGateway.php**

```php
<?php
defined('ABSPATH') || exit;

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
        $hash_data             = urldecode(http_build_query($params));
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
        $hash_data  = urldecode(http_build_query($data));
        $check_hash = hash_hmac('sha512', $hash_data, $this->get_option('hash_secret'));

        $order_id = (int) ($data['vnp_TxnRef'] ?? 0);
        $order    = wc_get_order($order_id);

        if (!$order) {
            wp_die('Order not found', '', ['response' => 404]);
        }

        if (hash_equals($check_hash, $secure_hash) && ($data['vnp_ResponseCode'] ?? '') === '00') {
            $order->update_status('processing', 'VNPAY: thanh toán thành công. Mã GD: ' . ($data['vnp_TransactionNo'] ?? ''));
            wp_redirect($order->get_checkout_order_received_url());
        } else {
            $order->update_status('failed', 'VNPAY: thanh toán thất bại. Mã lỗi: ' . ($data['vnp_ResponseCode'] ?? ''));
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
        $hash_data  = urldecode(http_build_query($data));
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
            $order->payment_complete($data['vnp_TransactionNo'] ?? '');
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
```

- [ ] **Step 2: Kích hoạt và cấu hình gateway**

1. Vào WooCommerce > Settings > Payments
2. Phải thấy "VNPAY" trong danh sách → Enable
3. Click "Set up": nhập `vnp_TmnCode` và `vnp_HashSecret` từ VNPAY sandbox account
4. Environment: chọn "Sandbox (Test)" → Save

- [ ] **Step 3: Test flow (sandbox)**

1. Mở product bất kỳ đã có trên WooCommerce (từ Task 2 sync)
2. Thêm vào cart, đến checkout, chọn VNPAY → Place Order
3. Expected: redirect đến `sandbox.vnpayment.vn` với đúng merchant và số tiền

- [ ] **Step 4: Commit**

```bash
git add inc/woocommerce/VNPayGateway.php
git commit -m "feat: add VNPAY custom WooCommerce payment gateway"
```

---

## Task 4: Section List + Card Template Integration

Thêm `post_id` vào `$item` array trong cả 3 section list và 3 AJAX handlers. Cập nhật card để tính `$dat_lich_url` từ `_wc_product_id`.

**Files:**
- Modify: `partials/sections/khoa-hoc/section-list.php`
- Modify: `partials/sections/workshop/section-list.php`
- Modify: `partials/sections/dich-vu/section-list.php`
- Modify: `partials/components/card-khoa-hoc.php`
- Modify: `partials/components/card-workshop.php`
- Modify: `partials/components/card-dich-vu.php`
- Modify: `inc/ajax/PostAjax.php`

**Interfaces:**
- Consumes: `TSH_WC_Product_Sync::META_KEY` = `'_wc_product_id'` (từ Task 2), `TSH_WC_Product_Sync::parse_price(string): float` (public static từ Task 2)

### 4A — section-list.php: thêm post_id vào item array

- [ ] **Step 1: Cập nhật khoa-hoc/section-list.php**

Mở `partials/sections/khoa-hoc/section-list.php`. Trong vòng lặp `while ($query->have_posts())`, tìm block `$items[] = [` (khoảng dòng 130) và thêm `'post_id'` vào cuối array:

**Tìm:**
```php
            'url'        => get_permalink($post_id),
        ];
```
**Thay bằng:**
```php
            'url'        => get_permalink($post_id),
            'post_id'    => $post_id,
        ];
```

- [ ] **Step 2: Cập nhật workshop/section-list.php**

Mở `partials/sections/workshop/section-list.php`. Tìm và thêm tương tự:

**Tìm:**
```php
            'url'        => get_permalink($post_id),
        ];
```
**Thay bằng:**
```php
            'url'        => get_permalink($post_id),
            'post_id'    => $post_id,
        ];
```

- [ ] **Step 3: Cập nhật dich-vu/section-list.php**

Mở `partials/sections/dich-vu/section-list.php`. Tìm và thêm tương tự:

**Tìm:**
```php
            'url'        => get_permalink($post_id),
        ];
```
**Thay bằng:**
```php
            'url'        => get_permalink($post_id),
            'post_id'    => $post_id,
        ];
```

### 4B — PostAjax.php: thêm post_id vào AJAX item arrays

- [ ] **Step 4: Cập nhật kiena_ajax_load_more_khoa_hoc trong PostAjax.php**

Mở `inc/ajax/PostAjax.php`. Trong function `kiena_ajax_load_more_khoa_hoc` (khoảng dòng 130), tìm:

**Tìm:**
```php
            $item = [
                'image' => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
                'level' => get_field('level', $post_id),
                'term'  => $term_name,
                'title' => get_the_title($post_id),
                'desc'  => get_field('short_desc', $post_id),
                'price' => get_field('price', $post_id),
                'url'   => get_permalink($post_id),
            ];
```
**Thay bằng:**
```php
            $item = [
                'image'   => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
                'level'   => get_field('level', $post_id),
                'term'    => $term_name,
                'title'   => get_the_title($post_id),
                'desc'    => get_field('short_desc', $post_id),
                'price'   => get_field('price', $post_id),
                'url'     => get_permalink($post_id),
                'post_id' => $post_id,
            ];
```

- [ ] **Step 5: Cập nhật kiena_ajax_load_more_workshop trong PostAjax.php**

Trong function `kiena_ajax_load_more_workshop` (khoảng dòng 188), tìm:

**Tìm:**
```php
            $item = [
                'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
                'type'     => $type_name,
                'status'   => get_field('ws_status', $post_id) ?: 'open',
                'date'     => get_field('ws_date', $post_id),
                'time'     => get_field('ws_time', $post_id),
                'title'    => get_the_title($post_id),
                'location' => get_field('ws_location', $post_id),
                'desc'     => get_field('ws_short_desc', $post_id),
                'price'    => get_field('ws_price', $post_id),
                'url'      => get_permalink($post_id),
            ];
```
**Thay bằng:**
```php
            $item = [
                'image'    => ['url' => $thumb ?: '', 'alt' => get_the_title($post_id)],
                'type'     => $type_name,
                'status'   => get_field('ws_status', $post_id) ?: 'open',
                'date'     => get_field('ws_date', $post_id),
                'time'     => get_field('ws_time', $post_id),
                'title'    => get_the_title($post_id),
                'location' => get_field('ws_location', $post_id),
                'desc'     => get_field('ws_short_desc', $post_id),
                'price'    => get_field('ws_price', $post_id),
                'url'      => get_permalink($post_id),
                'post_id'  => $post_id,
            ];
```

### 4C — Card components: tính dat_lich_url

- [ ] **Step 6: Cập nhật card-khoa-hoc.php**

Mở `partials/components/card-khoa-hoc.php`. Sau dòng khai báo `$best_seller` (dòng 15 hiện tại), thêm:

**Tìm:**
```php
$best_seller = $item['best_seller']  ?? false;
?>
```
**Thay bằng:**
```php
$best_seller = $item['best_seller']  ?? false;

// Buy Now URL
$_kh_post_id  = $item['post_id'] ?? 0;
$_kh_wc_id    = $_kh_post_id ? (int) get_post_meta($_kh_post_id, '_wc_product_id', true) : 0;
$_kh_price_raw = $item['price'] ?? '';
$_kh_has_wc   = $_kh_wc_id && TSH_WC_Product_Sync::parse_price($_kh_price_raw) > 0;
$dat_lich_url = $_kh_has_wc
    ? add_query_arg(['product_id' => $_kh_wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : $card_url;
?>
```

Sau đó tìm nút "ĐẶT LỊCH" trong card (dòng 106-109 hiện tại):

**Tìm:**
```php
                <a href="<?php echo esc_url($card_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    ĐẶT LỊCH
                </a>
```
**Thay bằng:**
```php
                <a href="<?php echo esc_url($dat_lich_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    ĐẶT LỊCH
                </a>
```

- [ ] **Step 7: Cập nhật card-workshop.php**

Mở `partials/components/card-workshop.php`. Sau dòng `$best_seller` (dòng 13):

**Tìm:**
```php
$best_seller = $item['best_seller']  ?? false;
?>
```
**Thay bằng:**
```php
$best_seller = $item['best_seller']  ?? false;

// Buy Now URL
$_ws_post_id   = $item['post_id'] ?? 0;
$_ws_wc_id     = $_ws_post_id ? (int) get_post_meta($_ws_post_id, '_wc_product_id', true) : 0;
$_ws_price_raw = $item['price'] ?? '';
$_ws_has_wc    = $_ws_wc_id && TSH_WC_Product_Sync::parse_price($_ws_price_raw) > 0;
$dat_lich_url  = $_ws_has_wc
    ? add_query_arg(['product_id' => $_ws_wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : $card_url;
?>
```

Tìm nút "ĐẶT LỊCH" (dòng 104-107):

**Tìm:**
```php
                <a href="<?php echo esc_url($card_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    ĐẶT LỊCH
                </a>
```
**Thay bằng:**
```php
                <a href="<?php echo esc_url($dat_lich_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    ĐẶT LỊCH
                </a>
```

- [ ] **Step 8: Cập nhật card-dich-vu.php**

Mở `partials/components/card-dich-vu.php`. Sau dòng `$best_seller` (dòng 16):

**Tìm:**
```php
$best_seller    = $item['best_seller']   ?? false;
?>
```
**Thay bằng:**
```php
$best_seller    = $item['best_seller']   ?? false;

// Buy Now URL
$_dv_post_id   = $item['post_id'] ?? 0;
$_dv_wc_id     = $_dv_post_id ? (int) get_post_meta($_dv_post_id, '_wc_product_id', true) : 0;
$_dv_price_raw = $item['price'] ?? '';
$_dv_has_wc    = $_dv_wc_id && TSH_WC_Product_Sync::parse_price($_dv_price_raw) > 0;
$dat_lich_url  = $_dv_has_wc
    ? add_query_arg(['product_id' => $_dv_wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : $card_url;
?>
```

Tìm nút "ĐẶT LỊCH" (dòng 107-110):

**Tìm:**
```php
                <a href="<?php echo esc_url($card_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    ĐẶT LỊCH
                </a>
```
**Thay bằng:**
```php
                <a href="<?php echo esc_url($dat_lich_url); ?>"
                    class="relative z-[2] ml-auto flex items-center justify-center px-4 py-2 bg-[#c2a056] text-white text-[12px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85 whitespace-nowrap shrink-0">
                    ĐẶT LỊCH
                </a>
```

- [ ] **Step 9: Verify card integration**

1. Mở trang Khóa học trên site
2. Với bài đã publish và có giá (ví dụ "8.500.000 VNĐ"): hover nút "ĐẶT LỊCH" → URL phải chứa `/mua-ngay/?product_id=...&nonce=...`
3. Với bài có giá "Liên hệ": hover nút "ĐẶT LỊCH" → URL phải trỏ về trang detail như cũ
4. Click nút với giá hợp lệ → phải redirect đến trang checkout WooCommerce

- [ ] **Step 10: Commit**

```bash
git add partials/sections/khoa-hoc/section-list.php \
        partials/sections/workshop/section-list.php \
        partials/sections/dich-vu/section-list.php \
        partials/components/card-khoa-hoc.php \
        partials/components/card-workshop.php \
        partials/components/card-dich-vu.php \
        inc/ajax/PostAjax.php
git commit -m "feat: wire buy-now URL into card components for all 3 CPT types"
```

---

## Task 5: Single Page Template Integration

Thay thế CF7 form bằng nút "ĐẶT LỊCH" khi có WC product + price > 0. Giữ nguyên CF7 khi "Liên hệ".

**Files:**
- Modify: `partials/templates/single/single-khoa_hoc.php`
- Modify: `partials/templates/single/single-workshop.php`
- Modify: `partials/templates/single/single-dich_vu.php`

**Interfaces:**
- Consumes: `TSH_WC_Product_Sync::META_KEY`, `TSH_WC_Product_Sync::parse_price()`
- Consumes: `$price` (khoa_hoc), `$ws_price` (workshop), `$dv_price` (dich_vu) — đã có trong file

### 5A — single-khoa_hoc.php

- [ ] **Step 1: Thêm logic buy-now trước get_header()**

Mở `partials/templates/single/single-khoa_hoc.php`. Tìm dòng `get_header();` (khoảng dòng 89) và thêm TRƯỚC nó:

**Tìm:**
```php
get_header();
```
**Thay bằng:**
```php
// Buy Now
$_kh_wc_id  = (int) get_post_meta($post_id, '_wc_product_id', true);
$_kh_has_wc = $_kh_wc_id && TSH_WC_Product_Sync::parse_price($price) > 0;
$_kh_buy_url = $_kh_has_wc
    ? add_query_arg(['product_id' => $_kh_wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : '';

get_header();
```

- [ ] **Step 2: Thay CF7 form bằng nút buy trong booking widget**

Trong cùng file, tìm block CF7 Form (khoảng dòng 522-534):

**Tìm:**
```php
                            <!-- CF7 Form -->
                            <div id="kh-form-inner" class="flex p-6 max-md:p-4 overflow-y-auto flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php
                                $cf7_id = defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '';
                                if ($cf7_id) : ?>
                                    <div class="cf7-khoa-hoc">
                                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_id) . '"]'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
```
**Thay bằng:**
```php
                            <!-- CF7 Form / Buy Now -->
                            <div id="kh-form-inner" class="flex p-6 max-md:p-4 overflow-y-auto flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php if ($_kh_has_wc) : ?>
                                    <a href="<?php echo esc_url($_kh_buy_url); ?>"
                                        class="flex items-center justify-center w-full py-3.5 bg-[#c2a056] text-white text-[14px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85">
                                        ĐẶT LỊCH
                                    </a>
                                <?php else : ?>
                                    <?php
                                    $cf7_id = defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '';
                                    if ($cf7_id) : ?>
                                        <div class="cf7-khoa-hoc">
                                            <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_id) . '"]'); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
```

### 5B — single-workshop.php

- [ ] **Step 3: Thêm logic buy-now trước get_header()**

Mở `partials/templates/single/single-workshop.php`. Tìm `get_header();` (khoảng dòng 98):

**Tìm:**
```php
get_header();
```
**Thay bằng:**
```php
// Buy Now
$_ws_wc_id  = (int) get_post_meta($post_id, '_wc_product_id', true);
$_ws_has_wc = $_ws_wc_id && TSH_WC_Product_Sync::parse_price($ws_price) > 0;
$_ws_buy_url = $_ws_has_wc
    ? add_query_arg(['product_id' => $_ws_wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : '';

get_header();
```

- [ ] **Step 4: Thay CF7 form trong single-workshop.php**

Tìm block CF7 (khoảng dòng 532-542):

**Tìm:**
```php
                            <!-- CF7 Form -->
                            <div id="ws-form-inner" class="flex p-6 max-md:p-4 overflow-y-auto flex-col gap-3">
```

Thêm logic vào bên trong tương tự step 2, thay `$cf7_id` → `$ws_cf7_id`, `cf7-khoa-hoc` → `cf7-workshop`, `$_kh_has_wc` → `$_ws_has_wc`, `$_kh_buy_url` → `$_ws_buy_url`:

**Tìm:**
```php
                            <!-- CF7 Form -->
                            <div id="ws-form-inner" class="flex p-6 max-md:p-4 overflow-y-auto flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php
                                $ws_cf7_id = defined('WS_CF7_FORM_ID') ? WS_CF7_FORM_ID : '';
                                if ($ws_cf7_id) : ?>
                                    <div class="cf7-workshop">
                                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($ws_cf7_id) . '"]'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
```
**Thay bằng:**
```php
                            <!-- CF7 Form / Buy Now -->
                            <div id="ws-form-inner" class="flex p-6 max-md:p-4 overflow-y-auto flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php if ($_ws_has_wc) : ?>
                                    <a href="<?php echo esc_url($_ws_buy_url); ?>"
                                        class="flex items-center justify-center w-full py-3.5 bg-[#c2a056] text-white text-[14px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85">
                                        ĐẶT LỊCH
                                    </a>
                                <?php else : ?>
                                    <?php
                                    $ws_cf7_id = defined('WS_CF7_FORM_ID') ? WS_CF7_FORM_ID : '';
                                    if ($ws_cf7_id) : ?>
                                        <div class="cf7-workshop">
                                            <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($ws_cf7_id) . '"]'); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
```

### 5C — single-dich_vu.php

- [ ] **Step 5: Thêm logic buy-now trước get_header()**

Mở `partials/templates/single/single-dich_vu.php`. Tìm `get_header();`:

**Tìm:**
```php
get_header();
```
**Thay bằng:**
```php
// Buy Now
$_dv_wc_id  = (int) get_post_meta($post_id, '_wc_product_id', true);
$_dv_has_wc = $_dv_wc_id && TSH_WC_Product_Sync::parse_price($dv_price) > 0;
$_dv_buy_url = $_dv_has_wc
    ? add_query_arg(['product_id' => $_dv_wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : '';

get_header();
```

- [ ] **Step 6: Thay CF7 form trong single-dich_vu.php**

Tìm block CF7 (khoảng dòng 519-529):

**Tìm:**
```php
                            <!-- CF7 Form -->
                            <div id="dv-form-inner" class="flex p-6 max-md:p-4 overflow-y-auto flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php
                                $dv_cf7_id = defined('DV_CF7_FORM_ID') ? DV_CF7_FORM_ID : (defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '');
                                if ($dv_cf7_id) : ?>
                                    <div class="cf7-dich-vu">
                                        <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($dv_cf7_id) . '"]'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
```
**Thay bằng:**
```php
                            <!-- CF7 Form / Buy Now -->
                            <div id="dv-form-inner" class="flex p-6 max-md:p-4 overflow-y-auto flex-col gap-3">
                                <h3 class="font-title text-pri text-[28px] max-md:text-[20px] font-bold">
                                    Đăng ký
                                </h3>
                                <?php if ($_dv_has_wc) : ?>
                                    <a href="<?php echo esc_url($_dv_buy_url); ?>"
                                        class="flex items-center justify-center w-full py-3.5 bg-[#c2a056] text-white text-[14px] font-semibold uppercase tracking-[0.5px] rounded-full transition-opacity hover:opacity-85">
                                        ĐẶT LỊCH
                                    </a>
                                <?php else : ?>
                                    <?php
                                    $dv_cf7_id = defined('DV_CF7_FORM_ID') ? DV_CF7_FORM_ID : (defined('KH_CF7_FORM_ID') ? KH_CF7_FORM_ID : '');
                                    if ($dv_cf7_id) : ?>
                                        <div class="cf7-dich-vu">
                                            <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($dv_cf7_id) . '"]'); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
```

- [ ] **Step 7: Verify single page integration**

1. Mở trang detail của 1 khóa học có giá hợp lệ (không phải "Liên hệ")
2. Booking widget bên phải phải hiện nút "ĐẶT LỊCH" (không có form CF7)
3. Click nút → redirect đến WooCommerce checkout
4. Mở trang detail của 1 dịch vụ có giá "Liên hệ" → booking widget vẫn hiện CF7 form như cũ

- [ ] **Step 8: Full end-to-end test (sandbox)**

1. Mở trang khóa học có giá → click "ĐẶT LỊCH"
2. Phải redirect đến `/mua-ngay/` → clear cart → redirect `/checkout/`
3. Điền thông tin checkout → Place Order → redirect VNPAY sandbox
4. Thanh toán thành công trên VNPAY sandbox → redirect về trang cảm ơn WooCommerce
5. Vào WooCommerce > Orders: phải thấy order với status "Processing"

- [ ] **Step 9: Commit**

```bash
git add partials/templates/single/single-khoa_hoc.php \
        partials/templates/single/single-workshop.php \
        partials/templates/single/single-dich_vu.php
git commit -m "feat: replace CF7 form with buy-now button on single pages when WC product exists"
```

---

## Checklist sau khi hoàn thành

- [ ] WooCommerce cài và kích hoạt
- [ ] Flush rewrite rules sau Task 1
- [ ] VNPAY sandbox credentials nhập đúng trong WC Settings > Payments
- [ ] Toàn bộ CPT posts đã được re-publish để trigger sync (hoặc chạy bulk update script)
- [ ] Test end-to-end sandbox thành công ít nhất 1 lần cho mỗi loại (khoa_hoc, workshop, dich_vu)
- [ ] Trước go-live: đổi Environment → Production, nhập production credentials
