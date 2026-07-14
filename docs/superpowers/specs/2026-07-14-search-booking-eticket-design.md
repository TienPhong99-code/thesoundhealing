# Lọc "Quà tặng E-ticket" trong box Search Booking

Ngày: 2026-07-14

## Vấn đề

Box Search Booking (dropdown **Loại hình**) hiện có 5 mục: Best Seller, Sound Healing, Usui Reiki, Khoá Học, Workshop. Khách muốn mua e-ticket làm quà tặng không có cách nào lọc ra những bài có phát hành e-ticket — phải mở từng bài xem mới biết.

## Bối cảnh hiện có

- `eticket_days` là trường ACF Number gắn trên cả 3 CPT `khoa_hoc`, `workshop`, `dich_vu` (`inc/acf/EticketACF.php`). Giá trị `> 0` = bài có phát hành e-ticket quà tặng; trống hoặc `0` = không.
- Trong dropdown Loại hình, **"Best Seller" không phải post type** — nó là filter theo meta (`dv_best_seller` / `kh_best_seller` / `ws_best_seller`) nhưng được xếp chung vào danh sách loại hình (`page-search-results.php`).
- JS của box (`assets/scripts/modules/common/search-booking.js`) hoàn toàn generic: chỉ loop qua `data-value` / `data-label`, không hardcode key nào.

## Giải pháp

Thêm **"Quà tặng E-ticket"** làm mục thứ 6 trong dropdown Loại hình, lọc theo `eticket_days > 0` — đúng pattern "Best Seller" đang dùng. Không phát sinh query param mới, không đụng layout, không đụng JS.

### Thay đổi

**1. `partials/components/search-booking.php`**

Thêm một entry vào mảng `$_sb_defaults`:

```php
'eticket' => [
    'label' => __('Quà tặng E-ticket', 'monamedia'),
    'desc'  => __('Tặng người thương một buổi chữa lành', 'monamedia'),
    'image' => $_img_base . 'dv-khai-van-huyen-hoc.jpg',
],
```

Vòng `foreach` bên dưới tự sinh option trong panel, hidden input, pre-fill từ GET, và mobile summary — không sửa gì thêm trong file này.

Lưu ý key: `str_replace('-', '_', 'eticket')` = `eticket`, nên field ACF là `sb_label_eticket` (không có dấu gạch).

**2. `inc/acf/SearchBookingACF.php`**

Thêm cụm 3 field vào cuối tab "Search Booking – Danh mục", theo đúng khuôn 5 mục hiện có:

- `sb_label_eticket` (Text) — placeholder "Quà tặng E-ticket"
- `sb_desc_eticket` (Text) — placeholder "Tặng người thương một buổi chữa lành"
- `sb_img_eticket` (Image, format URL)

Để trống → rơi về mặc định trong `$_sb_defaults`.

**3. `page-template/page-search-results.php`**

Ba chỗ:

- `$pt_map`: thêm `'eticket' => ['dich_vu', 'khoa_hoc', 'workshop']` — quét cả 3 loại bài.
- Trong vòng `foreach ($post_types as $pt)`, thêm nhánh vào chuỗi `if/elseif` lọc theo `$loai_hinh`:

```php
} elseif ($loai_hinh === 'eticket') {
    $et_clause = ['key' => 'eticket_days', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC'];
    if (!empty($query_args['meta_query'])) {
        $query_args['meta_query'][] = $et_clause;
    } else {
        $query_args['meta_query'] = [$et_clause];
    }
}
```

- `$label_map`: thêm `'eticket' => 'Quà tặng E-ticket'` để tag "filter đang chọn" hiển thị đúng tên.

**4. JS — không đổi.**

### Hành vi

- Bài không có meta `eticket_days`, hoặc để trống, hoặc `= 0` → bị loại khỏi kết quả.
- Bộ lọc Thời gian và Mức giá vẫn chồng lên bình thường: chọn "Quà tặng E-ticket" + "Từ 0 – 499.000" ra đúng các gói quà tặng dưới 500k.
- Chọn E-ticket thì không chọn được Sound Healing cùng lúc — Loại hình vốn chỉ cho chọn 1 giá trị. Đây là đánh đổi đã chấp nhận khi chọn phương án này.

### Đa ngôn ngữ

- Text mặc định đi qua `__()` → dịch bằng `.po`/`.mo`.
- Text admin nhập đi qua `mona_wpml_string()` → dịch ở WPML String Translation. Giống hệt 5 mục hiện có.
- Filter đọc meta của chính bài đang query, nên bản dịch EN phải có `eticket_days`. Rủi ro này giống hệt `dv_best_seller` đang chạy — nếu Best Seller trên `/en/` đúng thì E-ticket cũng đúng. Cần kiểm tra thực tế trên site EN sau khi làm xong.

### Ảnh mặc định

Theme chưa có ảnh chủ đề quà tặng. Tạm trỏ vào `dv-khai-van-huyen-hoc.jpg`; admin upload icon riêng qua ACF (5 mục hiện tại trên site cũng đang là icon do admin set, không phải ảnh mặc định trong code).

## Kiểm thử

Chạy site local, mở `/tim-kiem`:

1. Panel Loại hình hiện đủ 6 mục, "Quà tặng E-ticket" nằm cuối.
2. Chọn mục này → URL có `?loai-hinh=eticket`, kết quả chỉ gồm bài có `eticket_days > 0` (đối chiếu bằng cách sửa `eticket_days` của một bài về 0 rồi search lại — bài đó phải biến mất).
3. Kết hợp với Mức giá và Thời gian → lọc chồng đúng.
4. Reload trang kết quả → box pre-fill lại đúng "Quà tặng E-ticket", tag filter hiển thị đúng tên.
5. Sửa `sb_label_eticket` trong Theme Settings → tên trong dropdown đổi theo.
6. Mobile: mở popup, chọn mục này, dòng summary hiển thị đúng nhãn.
