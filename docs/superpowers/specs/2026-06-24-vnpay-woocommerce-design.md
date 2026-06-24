# Design: WooCommerce + VNPAY Payment Integration

**Date:** 2026-06-24
**Project:** The Sound Healing — WordPress custom theme
**Approach:** Hybrid (giữ CPT, thêm WooCommerce phía sau)

---

## Tóm tắt

Tích hợp thanh toán VNPAY qua WooCommerce cho 3 loại nội dung: Khóa học (`khoa_hoc`), Workshop (`workshop`), Dịch vụ (`dich_vu`). Giữ nguyên toàn bộ CPT, template, UI hiện tại. WooCommerce đóng vai trò order management + payment gateway phía sau.

---

## Kiến trúc tổng quan

```
CPT Post (khoa_hoc / workshop / dich_vu)
         │  save_post hook
         ▼
  WC Product (simple, virtual, catalog_visibility=hidden)
  ─ title ← post title
  ─ regular_price ← parse ACF 'price' field
  ─ status ← post status
  ─ post meta: _wc_product_id (lưu ở CPT post)
         │
         │  nút "ĐẶT LỊCH" trên card/single
         ▼
  Buy Now endpoint (/mua-ngay/)
  (clear cart → add product → redirect checkout)
         │
         ▼
  WooCommerce Checkout
  ─ VNPAY Gateway (custom WC_Payment_Gateway)
         │
         ├─► VNPAY Payment URL (redirect)
         │         │
         │    [Khách thanh toán]
         │         │
         ├─◄ IPN URL (server-to-server verify)
         └─◄ Return URL (redirect về site)
         │
         ▼
  WC Order → status: processing / failed
```

---

## File structure mới

Thêm thư mục `inc/woocommerce/` với 3 file:

| File | Vai trò |
|---|---|
| `WooCommerceHook.php` | Khai báo WC theme support, tắt WC CSS mặc định |
| `WcProductSync.php` | Auto-sync CPT → WC Product qua `save_post` hook |
| `VNPayGateway.php` | Custom `WC_Payment_Gateway` cho VNPAY |

Cả 3 đăng ký trong `configs/loadFile.php`.

---

## Section 1: WooCommerceHook.php

- `add_theme_support('woocommerce')` — để WC không override layout theme
- `add_filter('woocommerce_enqueue_styles', '__return_empty_array')` — tắt WC CSS (tránh conflict Tailwind)
- Giữ WC JS (cần cho cart/checkout)
- Đăng ký Buy Now endpoint: `add_rewrite_rule` cho `/mua-ngay/`
- Handler endpoint: verify nonce → `WC()->cart->empty_cart()` → `WC()->cart->add_to_cart($product_id)` → `wp_redirect(wc_get_checkout_url())`

---

## Section 2: WcProductSync.php

### Trigger

Hook `save_post` cho 3 post type:
```php
add_action('save_post_khoa_hoc',  [$this, 'sync']);
add_action('save_post_workshop',  [$this, 'sync']);
add_action('save_post_dich_vu',   [$this, 'sync']);
```

### Logic sync

1. **Parse giá** từ ACF text field `price`:
   - Xoá ký tự không phải số và dấu `.` → `(float)`
   - `"8.500.000 VNĐ"` → `8500000`
   - `"Liên hệ"` hoặc rỗng → `0`

2. **Tạo mới** nếu `_wc_product_id` chưa có hoặc product không tồn tại:
   - `new WC_Product_Simple()`
   - `set_virtual(true)`, `set_catalog_visibility('hidden')`
   - Lưu product ID vào `update_post_meta($post_id, '_wc_product_id', $product_id)`

3. **Cập nhật** nếu đã có:
   - `new WC_Product($wc_id)`
   - Update title, regular_price, status

4. **Đồng bộ status:**
   - CPT `publish` → WC `publish`
   - CPT `draft` / `trash` / `pending` → WC `draft`

### Rule: nút "ĐẶT LỊCH"

| Điều kiện | Hành vi nút |
|---|---|
| Có `_wc_product_id` + price > 0 | href → buy-now endpoint |
| price = 0 hoặc "Liên hệ" | href → trang detail (giữ nguyên) |
| Không có WC product | href → trang detail (giữ nguyên) |

---

## Section 3: VNPayGateway.php

**Class:** `TSH_VNPay_Gateway extends WC_Payment_Gateway`

**Settings (WooCommerce > Payments > VNPAY):**
- `vnp_TmnCode` — Mã merchant (Terminal Code)
- `vnp_HashSecret` — Hash secret key
- `environment` — `sandbox` | `production`

**VNPAY endpoints:**
- Sandbox: `https://sandbox.vnpayment.vn/paymentv2/vpcpay.html`
- Production: `https://pay.vnpayment.vn/paymentv2/vpcpay.html`

### process_payment($order_id)

1. Build params array:
   - `vnp_Amount` = `$order->get_total() * 100` (VNPAY tính đơn vị đồng × 100)
   - `vnp_Command` = `pay`
   - `vnp_CreateDate` = `date('YmdHis')`
   - `vnp_CurrCode` = `VND`
   - `vnp_IpAddr` = client IP
   - `vnp_Locale` = `vn`
   - `vnp_OrderInfo` = order ID + tên sản phẩm
   - `vnp_OrderType` = `other`
   - `vnp_ReturnUrl` = `/wc-api/vnpay_return`
   - `vnp_TmnCode` = setting
   - `vnp_TxnRef` = order ID
   - `vnp_Version` = `2.1.0`
2. Sort params theo key (alphabetical)
3. Build query string → HMAC-SHA512 với `vnp_HashSecret`
4. Append `vnp_SecureHash` vào params
5. Return `['result' => 'success', 'redirect' => $payment_url . '?' . http_build_query($params)]`

### Return URL handler (/wc-api/vnpay_return)

1. Nhận GET params từ VNPAY redirect
2. Tách `vnp_SecureHash` ra khỏi params
3. Rebuild hash từ params còn lại → so sánh với `vnp_SecureHash`
4. Nếu hash hợp lệ + `vnp_ResponseCode == '00'`:
   - `$order->update_status('processing')`
   - Redirect → `$order->get_checkout_order_received_url()` (trang cảm ơn)
5. Nếu thất bại:
   - `$order->update_status('failed')`
   - Redirect → `wc_get_checkout_url()` với notice lỗi

### IPN handler (/wc-api/vnpay_ipn)

1. Nhận POST/GET từ VNPAY server
2. Verify hash (cùng logic Return URL)
3. Kiểm tra `vnp_Amount` khớp với `$order->get_total() * 100`
4. Nếu hợp lệ + `vnp_ResponseCode == '00'`:
   - `$order->payment_complete($vnp_TransactionNo)`
   - Response: `{"RspCode":"00","Message":"Confirm Success"}`
5. Nếu không hợp lệ:
   - Response: `{"RspCode":"99","Message":"Unknown error"}`

---

## Section 4: Template integration

### Card components

**Áp dụng cho:** `card-khoa-hoc.php`, `card-workshop.php`, `card-dich-vu.php`

Logic thêm vào đầu file, sau khi đọc `$item`:
```php
$post_id  = $item['post_id'] ?? 0;
$wc_id    = $post_id ? get_post_meta($post_id, '_wc_product_id', true) : 0;
$price_raw = $item['price'] ?? '';
$has_wc   = $wc_id && !empty($price_raw) && strtolower(trim($price_raw)) !== 'liên hệ';

$dat_lich_url = $has_wc
    ? add_query_arg(['product_id' => $wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'))
    : $card_url;
```

Nút giữ text "ĐẶT LỊCH", chỉ thay `href` từ `$card_url` sang `$dat_lich_url`.

> **Lưu ý:** Hiện tại `$item` trong card được build từ PostFunction — cần thêm `post_id` vào array item khi map dữ liệu.

### Single pages

**Áp dụng cho:** `single-khoa_hoc.php`, `single-workshop.php`, `single-dich_vu.php`

Trong booking widget (cột phải / mobile slot):
- Nếu có WC product + giá > 0: **thay CF7 form** bằng nút "ĐẶT LỊCH" → buy-now endpoint
- Nếu "Liên hệ" hoặc không có WC product: **giữ nguyên CF7 form**

```php
$wc_id     = get_post_meta($post_id, '_wc_product_id', true);
$has_price = $wc_id && strtolower(trim($price)) !== 'liên hệ' && (float)preg_replace('/[^\d]/', '', $price) > 0;

if ($has_price) {
    $buy_url = add_query_arg(['product_id' => $wc_id, 'nonce' => wp_create_nonce('tsh_buy_now')], home_url('/mua-ngay/'));
    // render nút ĐẶT LỊCH
} else {
    // render CF7 form như cũ
}
```

---

## Dependencies

| Dependency | Ghi chú |
|---|---|
| WooCommerce plugin | Cài từ WordPress.org, version ≥ 8.x |
| VNPAY merchant account | Cần TmnCode + HashSecret từ VNPAY |
| VNPAY sandbox account | Dùng để test trước khi go live |

---

## Out of scope

- Re-style WooCommerce checkout page (có thể làm sau)
- Email notification tùy chỉnh (dùng WC default email)
- Refund qua VNPAY (thực hiện thủ công qua VNPAY portal)
- Installment / trả góp
