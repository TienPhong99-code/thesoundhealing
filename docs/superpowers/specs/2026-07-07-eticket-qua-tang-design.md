# Thiết kế: E-ticket làm quà tặng (tải ảnh voucher)

**Ngày:** 2026-07-07
**Nhánh:** feature/lich-dinh-ky
**Liên quan:** [[2026-07-06-thanh-toan-o-trang-cam-on-design]] (trang cảm ơn).

## 1. Mục tiêu

Trên trang cảm ơn (order-received), khách bấm nút **"E-ticket làm quà tặng"** → hệ thống dựng một **voucher** dạng ảnh và **tự tải xuống file PNG**. Voucher dùng làm quà tặng: người nhận liên hệ hotline để đặt lịch. Kèm field admin cấu hình **số ngày hiệu lực** của e-ticket theo từng dịch vụ.

## 2. Quyết định đã chốt

| Vấn đề | Quyết định |
|---|---|
| Field hết hạn | **Riêng từng dịch vụ**, kiểu **số ngày hiệu lực** (không phải ngày cố định). Hết hạn = ngày đặt đơn + số ngày |
| Nội dung voucher | **Gọn**: Mã e-ticket, Tên dịch vụ, Số người, Ngày hết hạn, Hotline. **Bỏ** tên/email/SĐT người mua, khung giờ, chi nhánh, tổng thanh toán, phương thức |
| Hotline | 2 số như trang cảm ơn: English `0939 624 684`, Tiếng Việt `0906 502 582` |
| Tạo ảnh | **Client-side html2canvas** (chụp khối HTML → PNG). Không sinh ảnh server |
| Layout | Giống card trang cảm ơn: 2 cột (nội dung + ảnh `banner-confirm.png`) |
| Dịch vụ không set số ngày | Coi như không phát hành e-ticket → **ẩn nút** |
| Điều kiện hiện nút | Hiện bất kể trạng thái thanh toán (miễn có `eticket_days`); người nhận redeem qua hotline, NV kiểm tra đơn |

## 3. Data model & luồng dữ liệu

- **ACF field** `eticket_days` (Number, "Số ngày hiệu lực e-ticket") thêm vào 3 CPT: `khoa_hoc`, `workshop`, `dich_vu`.
- **Tra ngược product → CPT bằng query** (không cần sửa WcProductSync, chạy được với sản phẩm cũ): CPT đã lưu meta `_wc_product_id`. Khi tạo đơn, tra CPT gốc bằng:
  ```php
  $cpt = get_posts([
      'post_type'      => ['khoa_hoc', 'workshop', 'dich_vu'],
      'meta_key'       => '_wc_product_id',
      'meta_value'     => $product_id,
      'posts_per_page' => 1,
      'fields'         => 'ids',
  ]);
  ```
- **Tính hạn khi tạo đơn** (`woocommerce_checkout_create_order`): với item đầu → product_id → query CPT (trên) → `get_field('eticket_days', $cpt_id)`. Nếu > 0:
  - `_tsh_eticket_days` = số ngày
  - `_tsh_eticket_expiry` = ngày tạo đơn + số ngày (lưu `Y-m-d`)
  Nếu không có/không > 0 → không lưu (không phát hành e-ticket).

```
Admin đặt eticket_days trên dịch vụ
Khách đặt đơn → checkout_create_order:
   item đầu → product_id → query CPT (_wc_product_id) → eticket_days
   → lưu _tsh_eticket_expiry, _tsh_eticket_days vào order
Trang cảm ơn:
   nếu order có _tsh_eticket_expiry → render khối voucher ẩn + hiện nút
   bấm nút → html2canvas(.tsh-voucher) → tải PNG
```

## 4. Khối voucher (HTML render server-side trên trang cảm ơn)

- Chỉ render khi order có `_tsh_eticket_expiry`.
- Node `.tsh-voucher` đặt `position:absolute; left:-9999px; top:0` (render được nhưng không thấy trên trang) để html2canvas chụp.
- Layout 2 cột giống card:
  - **Cột nội dung:** tiêu đề "E-TICKET QUÀ TẶNG"; các dòng:
    - Mã e-ticket: `#` + `str_pad($order_id, 5, '0', STR_PAD_LEFT)`
    - Dịch vụ: tên item đầu (`$order->get_items()` → `reset()->get_name()`)
    - Số người: `_booking_guests` + "người"
    - Ngày hết hạn: `_tsh_eticket_expiry` format `d/m/Y`
    - Hotline: `0939 624 684` (English) + `0906 502 582` (Tiếng Việt)
    - Ghi chú: "Vui lòng liên hệ hotline để đặt lịch. Xuất trình mã e-ticket khi sử dụng."
  - **Cột ảnh:** `<img src="MONA_THEME_PATH_URI/assets/images/banner-confirm.png">`.
- **Không** hiển thị: người mua (tên/email/SĐT), khung giờ, chi nhánh, tổng thanh toán, phương thức.
- Kích thước cố định (vd rộng 900px) để ảnh xuất ổn định, không phụ thuộc viewport.

## 5. Nút & tạo ảnh

- Trong `thankyou.php`: nút "E-TICKET LÀM QUÀ TẶNG" hiện **bỏ `disabled`** và chỉ render khi order có `_tsh_eticket_expiry`; gắn `data-order="<id>"`.
- Thư viện **html2canvas** đặt tại `assets/library/html2canvas/html2canvas.min.js`.
- `assets/scripts/eticket.js`: bind click nút → đổi text "Đang tạo..." → `html2canvas(document.querySelector('.tsh-voucher'), {scale: 2, useCORS: true, backgroundColor: '#ffffff'})` → `canvas.toBlob(blob => download(blob, 'e-ticket-' + order + '.png'))` → khôi phục text nút. Bắt lỗi → alert nhẹ.
- Enqueue html2canvas + eticket.js trong `CommonHook.php` khi `is_order_received_page()`.

## 6. Các thành phần & file

| Việc | File |
|---|---|
| ACF field `eticket_days` cho 3 CPT | `inc/acf/EticketACF.php` (mới) + đăng ký trong `configs/loadFile.php` |
| Lưu `_tsh_source_post` khi sync | `inc/woocommerce/WcProductSync.php` |
| Tính + lưu `_tsh_eticket_expiry`/`_tsh_eticket_days` khi tạo đơn | `inc/woocommerce/WooCommerceHook.php` (hook `woocommerce_checkout_create_order`) |
| Bật nút + render `.tsh-voucher` | `woocommerce/checkout/thankyou.php` |
| JS tạo/tải ảnh | `assets/scripts/eticket.js` (mới) |
| Thư viện | `assets/library/html2canvas/html2canvas.min.js` (mới) |
| Enqueue theo trang | `inc/hooks/CommonHook.php` (block `is_order_received_page()`) |
| Style voucher | `assets/css/style.css` |

## 7. Edge case

- **Đơn nhiều item:** lấy item đầu (nhất quán luồng hiện tại).
- **Sản phẩm cũ chưa có `_tsh_source_post`:** cần re-sync (chạy lại `acf/save_post` hoặc script sync) để có reverse meta; nếu thiếu → không tính được hạn → không hiện nút. Ghi chú vận hành.
- **Font trong ảnh:** Roboto đã load sẵn ở theme; html2canvas render theo font hiện tại.
- **Ảnh banner:** cùng domain → html2canvas chụp được (không CORS). `useCORS:true` để an toàn.
- **`_booking_guests` rỗng:** mặc định "1 người".
- **html2canvas lỗi/khối không tồn tại:** nút báo lỗi nhẹ, không crash trang.
- **OPcache/deploy:** file PHP mới cần reset OPcache; deploy vào `public_html/...` (xem [[deploy-ftp-opcache]]).

## 8. Kiểm thử (thủ công — repo không có test; sau reset OPcache)

1. Admin: set `eticket_days` cho 1 dịch vụ (vd 90). Re-sync product.
2. Đặt đơn dịch vụ đó → trang cảm ơn hiện nút "E-ticket làm quà tặng".
3. Bấm nút → tải file `e-ticket-00xxx.png`; ảnh có 2 cột, đúng: mã, dịch vụ, số người, ngày hết hạn (= ngày đặt + 90), 2 hotline, ảnh banner; KHÔNG có thông tin người mua/giờ/chi nhánh/thanh toán.
4. Dịch vụ KHÔNG set `eticket_days` → không hiện nút.
5. Kiểm ảnh nét (scale 2), font đúng, không vỡ layout.
6. Lặp cho khóa học & workshop.
