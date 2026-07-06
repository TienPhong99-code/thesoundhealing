# Thiết kế: Chuyển thanh toán (QR/PayPal) sang trang cảm ơn

**Ngày:** 2026-07-06
**Nhánh:** feature/lich-dinh-ky
**Liên quan:** nối tiếp [[2026-07-06-coc-50-100-checkout-design]] (option cọc 50%/100%).

## 1. Mục tiêu & luồng mới

Đổi luồng thanh toán từ "trả tiền trên checkout rồi mới tạo đơn" sang **luồng WooCommerce chuẩn**: chọn cọc + phương thức trên checkout → bấm "Đặt lịch ngay" → **tạo đơn pending** → trang cảm ơn (order-received) mới hiện QR/PayPal để khách trả.

```
Checkout (xác nhận đặt lịch)
  ├─ 2 radio cọc 50% / 100% (mặc định 100%)
  ├─ chọn phương thức: SePay / PayPal / Thanh toán khác
  ├─ KHÔNG hiện QR
  └─ nút "Đặt lịch ngay" (nút place-order THẬT của WooCommerce)
        │  đổi radio cọc → update_checkout → Tổng cập nhật tại chỗ (chỉ số tiền)
        ▼
  Tạo order PENDING (total đã tính cọc) → redirect order-received
        ▼
Trang cảm ơn (order-received)
  ├─ SePay:  QR (nội dung TSH{order_id}, số tiền = tổng đơn) + polling ngầm
  │          → webhook xác nhận → swap TẠI CHỖ sang "Thanh toán thành công" + gửi email
  ├─ PayPal: QR/thông tin + nút "Tôi đã thanh toán" → báo admin, cảm ơn tại chỗ (đơn pending)
  └─ Khác:   "Đặt lịch thành công, nhân viên sẽ liên hệ" (không QR/polling, đơn pending)
```

## 2. Quyết định đã chốt

| Vấn đề | Quyết định |
|---|---|
| Đơn pending chưa trả | Để nguyên pending, admin tự xử — **không** auto-cancel |
| Email xác nhận | **Chỉ gửi khi đã thanh toán** (SePay confirm / PayPal "đã thanh toán"). Pending không gửi email khách |
| "Thanh toán khác" (tsh_cash) | Trang cảm ơn báo "Đặt lịch thành công, nhân viên sẽ liên hệ" — đơn pending, không QR |
| SePay xác nhận | **Tự động, swap tại chỗ** (không reload cả trang), theo `order_id` (không phụ thuộc thiết bị quét) |
| Cọc 50% | Giữ nguyên; order total đã trừ cọc; dòng "còn lại" hiện ở cảm ơn/email/admin |
| Điều khiển cọc | Bỏ ajax `tsh_set_payment_type`; đọc `tsh_paytype` từ **field form checkout** qua `woocommerce_checkout_update_order_review` |

## 3. Trang checkout — thay đổi

- **Giữ:** 2 radio cọc (`payment_type_options`), chọn phương thức thanh toán, nút place-order thật.
- **Điều khiển cọc (thay cơ chế cũ):**
  - Radio `tsh_paytype` là field trong form checkout. Đổi radio → JS gọi `$(body).trigger('update_checkout')` (native WC, không ajax riêng).
  - Hook `woocommerce_checkout_update_order_review($post_data)`: parse `tsh_paytype` từ chuỗi post_data → `WC()->session->set('tsh_payment_type', ...)`.
  - `apply_deposit_fee` (giữ nguyên) đọc session → áp phí âm → Tổng cập nhật tại chỗ.
- **Gỡ:** action `ajax_set_payment_type` + đăng ký `wp_ajax(_nopriv)_tsh_set_payment_type`; method `payment_type_js` (thay bằng đoạn JS nhỏ chỉ trigger update_checkout); QR không render trên checkout nữa (bỏ nhánh sepay trong `add_bacs_qr_checkout`); nút giả `#tsh-place-order` + polling sepay trong `checkout_bacs_js`.
- **Reset mặc định 100%:** giữ `woocommerce_checkout_init` set `tsh_payment_type='full'`.

## 4. Trang cảm ơn (order-received) — thay đổi

Dựa trên `show_bacs_qr_thankyou` + `thankyou_polling_js` sẵn có, mở rộng cho đúng 3 nhánh theo `$order->get_payment_method()`:

- **SePay** (`sepay`):
  - Đơn `processing`/`completed` → hiện trạng thái "Thanh toán thành công / Cảm ơn".
  - Đơn `pending` → hiện QR VietQR: nội dung `TSH{order_id}`, số tiền = `$order->get_total()` (đã tính cọc). Polling `tsh_order_status` mỗi 5s.
  - Khi polling thấy `processing` → **swap tại chỗ** khu QR sang success (không `location.reload()`).
- **PayPal** (`tsh_paypal_qr`): QR/thông tin PayPal + nút "Tôi đã thanh toán" (`tsh_confirm_transfer` sẵn có) → báo admin, hiện cảm ơn tại chỗ. Đơn pending.
- **Thanh toán khác** (`tsh_cash`): hiện "Đặt lịch thành công, nhân viên sẽ liên hệ xác nhận thanh toán". Không QR/polling.
- Mọi nhánh: hiện thông tin đặt lịch (ngày/giờ/chi nhánh/số người) + dòng cọc "Đã đặt cọc X. Còn lại Y thu tại cơ sở" nếu là đơn cọc.
- **F5 trang cảm ơn:** đọc lại `$order->get_status()` → đã trả thì hiện success, chưa thì hiện QR.

## 5. Tạo đơn, trạng thái & email

- Bấm "Đặt lịch ngay" → WC tạo order **pending**. Hook `woocommerce_checkout_create_order` lưu `_booking_*` + `_tsh_payment_type/_tsh_*_amount` (đã có `save_booking_meta`, `save_payment_type_meta`).
- **Email chỉ khi đã thanh toán:**
  - SePay: webhook → `payment_complete()` → `processing` → email "Cảm ơn đặt lịch" (khách) + admin. (`auto_complete_sepay` giữ nguyên.)
  - PayPal: khách bấm "đã thanh toán" → email báo admin kiểm tra (`send_transfer_confirmed_email` sẵn có). Đơn vẫn pending tới khi admin xác nhận.
  - Thanh toán khác: pending → chưa email khách; admin theo dõi.
  - **Chặn** email WooCommerce mặc định cho trạng thái pending/on-hold nếu chưa thanh toán (dùng filter enable email theo điều kiện).

## 6. SePay webhook & token

- Nội dung chuyển khoản dùng **`TSH{order_id}`** (khớp theo đơn), thay `TSHCK{token}` theo session của luồng checkout cũ.
- Webhook `SepayWebhook.php` đã hỗ trợ khớp `TSH{order_id}` → `payment_complete()`. Giữ nguyên; bỏ nhánh `TSHCK{token}` nếu không còn dùng.

## 7. Edge case

- Sản phẩm giá 0/"Liên hệ": không hiện option cọc (giữ nguyên).
- Cọc + số người: order total đã gồm giá×người rồi mới trừ cọc → đúng.
- Khách quét bằng máy khác / người khác trả hộ: trang cảm ơn theo `order_id` nên vẫn tự cập nhật.
- Đơn pending trùng (khách bấm lại): mỗi lần submit tạo 1 đơn WC mới — chấp nhận (admin xử lý), không xử lý dedup ở phase này.
- Toàn bộ code mới **chỉ chạy sau khi reset OPcache** trên server (LiteSpeed `validate_timestamps=0`). Xem [[deploy-ftp-opcache]].

## 8. Kiểm thử (thủ công — repo không có test suite; sau khi reset OPcache)

1. Checkout: đổi cọc 50%/100% → Tổng cập nhật tại chỗ, **không** hiện QR trên checkout.
2. Bấm "Đặt lịch ngay" (SePay) → sang trang cảm ơn, hiện QR đúng số tiền (50%/100%), nội dung `TSH{order_id}`.
3. Chuyển khoản thật/giả webhook → trang cảm ơn **tự swap** sang success, không reload; đơn `processing`; email gửi.
4. PayPal → trang cảm ơn hiện PayPal + nút "đã thanh toán" → bấm → cảm ơn + email admin; đơn pending.
5. Thanh toán khác → trang cảm ơn báo "nhân viên liên hệ"; đơn pending; không email khách.
6. F5 trang cảm ơn khi đã trả → hiện success luôn.
7. Lặp cho workshop & dịch vụ.
8. Xác nhận không còn QR/nút giả/polling trên checkout.
