# Thông tin đặt cọc trong email WooCommerce (khách + admin)

Ngày: 2026-07-14

## Vấn đề

Khách đặt lịch có thể chọn **Đặt cọc 50%** ở trang thanh toán. Khi đó `apply_deposit_fee()` thêm một fee âm nên `order total` = 50% giá dịch vụ.

Email hiện tại **không hề nhắc tới cọc**:

- 4 template email của theme (`woocommerce/emails/*.php`) là template tự viết, **không gọi action `woocommerce_email_after_order_table`** → hook `WooCommerceHook::email_deposit_notice()` (đăng ký ở dòng 25) không bao giờ chạy trong các email này.
- Dòng "Tổng thanh toán" in `$order->get_formatted_order_total()` — với đơn cọc đây chỉ là **50%** nhưng lại gắn nhãn "Tổng thanh toán". Khách và admin đều thấy số tiền hụt một nửa mà không có giải thích, admin cũng không biết còn phải thu bao nhiêu tại cơ sở.

## Bối cảnh hiện có

- Meta lưu khi tạo đơn (`WooCommerceHook::save_payment_type_meta()`, dòng 469):
  - `_tsh_payment_type` — `deposit` | `full`
  - `_tsh_full_amount` — giá trị đầy đủ (cart subtotal, đã gồm giá × số người)
  - `_tsh_deposit_amount` — số đã cọc (= order total)
  - `_tsh_remaining_amount` — số còn lại thu tại cơ sở
- `WooCommerceHook::deposit_notice_html()` (dòng 1416) đã dựng sẵn câu thông báo cọc, đang dùng cho box trong admin order (`display_deposit_admin`) và hook email mặc định.
- Trang cảm ơn (`woocommerce/checkout/thankyou.php`) đã hiển thị cọc — dùng làm chuẩn về wording.
- 3 template có mục "Dịch vụ & Thanh toán": `customer-processing-order.php`, `customer-on-hold-order.php`, `admin-new-order.php`. Template `customer-completed-order.php` cố ý không có tóm tắt đơn (thư cảm ơn + mời review Google) → **không đụng tới**.

## Giải pháp

Gom markup cọc vào helper dùng chung, rồi gọi từ 3 template. Đơn thanh toán 100% render y như cũ.

### 1. `inc/functions/DepositFunction.php` (file mới)

Đăng ký trong `configs/loadFile.php`.

```php
tsh_deposit_info(\WC_Order $order): ?array
```

Trả `null` nếu `_tsh_payment_type !== 'deposit'` hoặc `remaining <= 0`. Ngược lại trả `['full' => float, 'deposit' => float, 'remaining' => float]`. Fallback cho đơn cũ thiếu `_tsh_full_amount`: `full = deposit + remaining`.

```php
tsh_email_deposit_rows(\WC_Order $order): void
```

Echo 4 `<tr>` khớp đúng style bảng sẵn có trong template (label `12px #aaa`, value `13px 600 #1b1c19`, viền dưới `#f0ede6`):

| Nhãn | Giá trị |
|---|---|
| Hình thức thanh toán | Đặt cọc 50% |
| Tổng giá trị dịch vụ | `full` |
| Đã đặt cọc | `deposit` — in đậm màu gold `#c2a056`, `14px` (chỗ của "Tổng thanh toán" cũ) |
| Còn lại thu tại cơ sở | `remaining` |

```php
tsh_email_deposit_box(\WC_Order $order, bool $for_admin = false): void
```

Echo hộp lưu ý (table 100%, nền `#fff8e1`, viền trái 3px `#c2a056`):

- Khách: "Đã đặt cọc **X**. Còn lại **Y** thu tại cơ sở khi tham gia."
- Admin: "Đơn đặt cọc — cần thu thêm **Y** tại cơ sở."

Cả 3 hàm không làm gì (không echo) khi `tsh_deposit_info()` trả `null`.

### 2. Ba template email

Với mỗi template, trong bảng "Dịch vụ & Thanh toán":

- Đơn cọc → **ẩn** dòng "Tổng thanh toán", gọi `tsh_email_deposit_rows($order)` thay vào; dòng "Phương thức thanh toán" giữ nguyên bên dưới.
- Đơn 100% → giữ nguyên dòng "Tổng thanh toán" như hiện tại.
- Gọi `tsh_email_deposit_box($order)` ngay trước hộp Notice sẵn có (`admin-new-order.php`: `$for_admin = true`, đặt sau bảng detail).

### 3. Dọn `WooCommerceHook`

`deposit_notice_html()` đọc số liệu qua `tsh_deposit_info()` thay vì tự `get_meta()` — một nguồn sự thật duy nhất. Giữ nguyên hook `email_deposit_notice` (vẫn có tác dụng với các email WC dùng template mặc định: huỷ đơn, hoàn tiền) và `display_deposit_admin`.

## Ngôn ngữ

Chuỗi mới dùng `__(..., 'monamedia')` (gettext .po/.mo), khớp với các dòng sẵn có trong 3 template này. Hai template khách đã gọi `tsh_switch_email_locale($order)` ở đầu file nên khách EN nhận đúng bản dịch; `admin-new-order.php` không switch locale (admin nhận ngôn ngữ site) — giữ nguyên hành vi đó.

Số tiền in qua `wc_price()` bọc `wp_kses_post()`, đúng như dòng "Tổng thanh toán" hiện tại.

## Ngoài phạm vi

- Email "Đơn hoàn thành" — cố ý không có tóm tắt đơn.
- Thay đổi cách tính cọc, tỉ lệ cọc, hay luồng thu tiền còn lại.
