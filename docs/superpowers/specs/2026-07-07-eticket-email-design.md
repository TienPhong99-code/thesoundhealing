# Thiết kế: Gửi e-ticket qua email (bỏ tải ảnh)

**Ngày:** 2026-07-07
**Nhánh:** feature/lich-dinh-ky
**Liên quan:** nối tiếp [[2026-07-07-eticket-qua-tang-design]] — đổi cách giao e-ticket từ tải ảnh (client html2canvas) sang **gửi email riêng khi thanh toán xác nhận**.

## 1. Mục tiêu

Bỏ phương án tải ảnh voucher (html2canvas). Thay bằng: khi đơn được **xác nhận thanh toán** (chuyển `processing`/`completed`), tự **gửi 1 email riêng** cho khách chứa voucher e-ticket dạng **HTML**. Dọn toàn bộ debug tạm.

## 2. Quyết định đã chốt

| Vấn đề | Quyết định |
|---|---|
| Dạng voucher trong email | **HTML** trong thân email (không sinh ảnh) |
| Email | **Riêng biệt**, tiêu đề "E-ticket quà tặng của bạn — #{mã đơn}" |
| Thời điểm gửi | Khi đơn → `processing` hoặc `completed` (SePay webhook tự, hoặc admin duyệt PayPal/tiền mặt) |
| Điều kiện | Chỉ khi đơn có e-ticket (dịch vụ có `eticket_days`); gửi **một lần** |
| Tải ảnh | **Bỏ**: nút, `.tsh-voucher` ẩn, enqueue html2canvas + eticket.js |
| Trên trang cảm ơn | Thay nút bằng **dòng thông báo**: "E-ticket quà tặng sẽ được gửi qua email sau khi thanh toán được xác nhận" (chỉ khi đơn có e-ticket) |
| Ảnh banner trong email | **Không** kèm (nhẹ + tránh mail client chặn ảnh) |
| Debug tạm | **Bỏ hết** (hộp vàng trang cảm ơn + phần `debug:` dòng admin) |

## 3. Helper tính hạn (bền, không phụ thuộc lúc tạo đơn)

Method `eticket_expiry_for(\WC_Order $order): string` trong `TSH_WooCommerce_Hook`:
- Nếu `$order->get_meta('_tsh_eticket_expiry')` có → trả về.
- Nếu chưa có → tính từ **item đầu của đơn**: `product_id` → query CPT (`_wc_product_id`) → `get_post_meta($cpt, 'eticket_days', true)`. Nếu `> 0`: `expiry = ngày tạo đơn + days` (local), lưu `_tsh_eticket_expiry` + `_tsh_eticket_days`, `$order->save()`, trả về expiry.
- Nếu không có dịch vụ / days ≤ 0 → trả `''`.
- Tính ngày local: `$order->get_date_created()->getTimestamp() + gmt_offset*3600`, cộng `days*DAY_IN_SECONDS`, `gmdate('Y-m-d', ...)`.

Dùng chung cho: dòng admin (`display_eticket_admin`) + email. Gộp logic on-demand hiện có ở thankyou.php vào helper này (thankyou.php gọi helper).

## 4. Email e-ticket riêng

- Đăng ký gửi trên hook `woocommerce_order_status_processing` và `woocommerce_order_status_completed` (2 args: `$order_id`, `$order`).
- Method `send_eticket_email(int $order_id, \WC_Order $order = null): void`:
  - Lấy order; nếu đã gửi (`_tsh_eticket_email_sent`) → return.
  - `$expiry = $this->eticket_expiry_for($order)`; nếu rỗng → return (đơn không có e-ticket).
  - Dựng nội dung voucher HTML: Mã e-ticket (`#` + str_pad 5), Dịch vụ (item đầu), Số người (`_booking_guests` ?: 1), Ngày hết hạn (`date_i18n('d/m/Y', strtotime($expiry))`), 2 hotline (English `0939 624 684`, Tiếng Việt `0906 502 582`), ghi chú "Vui lòng liên hệ hotline để đặt lịch. Xuất trình mã e-ticket khi sử dụng."
  - Gửi qua `wp_mail($order->get_billing_email(), $subject, $html, $headers)` với `Content-Type: text/html; charset=UTF-8`.
  - Đánh dấu `$order->update_meta_data('_tsh_eticket_email_sent', '1'); $order->save();`.
- Style email: inline CSS (email không dùng file CSS ngoài), tông gold `#c2a056` như voucher web, khung viền + nền nhạt.

## 5. Dọn dẹp (bỏ tải + debug)

- `woocommerce/checkout/thankyou.php`:
  - Bỏ hộp debug vàng.
  - Bỏ nút `#tsh-eticket-btn` + khối `.tsh-voucher` ẩn.
  - Giữ biến `$eticket_expiry` (gọi `eticket_expiry_for` qua 1 hàm/helper — hoặc đọc meta + fallback như hiện tại nhưng bỏ phần debug `$eticket_dbg2`).
  - Thêm dòng thông báo (chỉ khi `$eticket_expiry`): "E-ticket quà tặng sẽ được gửi qua email sau khi thanh toán được xác nhận."
- `inc/hooks/CommonHook.php`: bỏ block enqueue `html2canvas` + `tsh-eticket`.
- Xoá `assets/scripts/eticket.js`. (Lib `html2canvas.min.js` để lại trên đĩa, vô hại.)
- `inc/woocommerce/WooCommerceHook.php`:
  - Bỏ `_tsh_eticket_debug` trong `save_eticket_meta` (giữ method, nó vẫn lưu sớm khi worker fresh).
  - Bỏ phần `debug:` trong `display_eticket_admin` (dùng `eticket_expiry_for` để luôn hiện đúng ngày).
- Bỏ CSS `.tsh-voucher` trong style.css (không còn dùng trên web). (Không bắt buộc — để lại vô hại; ưu tiên dọn.)

## 6. Edge case

- Đơn không có e-ticket (dịch vụ chưa set ngày) → không gửi email, không hiện dòng thông báo.
- Gửi 1 lần (`_tsh_eticket_email_sent`), kể cả khi order đổi qua lại processing/completed nhiều lần.
- Đơn nhiều item → lấy item đầu (nhất quán).
- Toàn bộ code mới **cần worker chạy code mới** — khuyến nghị **restart PHP triệt để** (LiteSpeed per-worker OPcache); helper on-demand giảm phụ thuộc thời điểm tạo đơn.
- `wp_mail` phụ thuộc cấu hình gửi mail của site (đã hoạt động vì các email WooCommerce khác gửi được).

## 7. Kiểm thử (thủ công — sau restart PHP)

1. Bỏ debug: trang cảm ơn không còn hộp vàng; đơn admin không còn `debug:`.
2. Trang cảm ơn đơn có e-ticket: không còn nút tải, hiện dòng "sẽ gửi qua email".
3. Đơn SePay: chuyển khoản → webhook xác nhận (processing) → khách nhận email "E-ticket quà tặng của bạn" với voucher HTML đúng: mã, dịch vụ, số người, ngày hết hạn, 2 hotline.
4. Đơn PayPal/tiền mặt: admin đổi trạng thái → processing → email gửi.
5. Đổi trạng thái lại nhiều lần → không gửi email trùng.
6. Dịch vụ không set ngày → không gửi email, không hiện dòng thông báo.
