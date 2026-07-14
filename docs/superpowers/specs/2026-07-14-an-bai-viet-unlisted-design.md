# Ẩn bài viết khỏi danh sách (unlisted) — dành cho sự kiện riêng tư

Ngày: 2026-07-14

## Vấn đề

Có những sự kiện chỉ dành cho khách được mời: không muốn hiện ở trang chủ, danh sách, tìm kiếm hay Google, nhưng vẫn cần một link để gửi riêng cho khách — mở link là vào được, đặt lịch và thanh toán bình thường.

WordPress không có sẵn trạng thái này. `private` chỉ admin xem được (khách mở ra 404), `password protected` bắt nhập mật khẩu. Cái cần là kiểu "unlisted" của YouTube: **ẩn khỏi mọi nơi liệt kê, nhưng ai có link là vào thẳng.**

## Phạm vi

3 CPT: `khoa_hoc`, `workshop`, `dich_vu`. Không áp dụng cho blog post, dự án, tuyển dụng.

## Bối cảnh kỹ thuật

Theme liệt kê bài ở rất nhiều nơi: 4 section trang chủ (`get_posts()`), 5 section list (`WP_Query`), 4 query AJAX load-more (`inc/ajax/PostAjax.php`), trang tìm kiếm (`page-search-results.php`), archive, taxonomy.

Điểm mấu chốt: **cả `WP_Query` lẫn `get_posts()` đều kích hoạt hook `pre_get_posts`.** Nên chặn được tập trung tại một hook duy nhất, không phải sửa từng file liệt kê.

Sản phẩm WooCommerce đi kèm mỗi bài đã luôn được đặt `catalog_visibility = 'hidden'` (`WcProductSync.php:156`), nên booking và thanh toán của bài ẩn chạy bình thường — không cần đụng gì bên WooCommerce.

## Giải pháp

### 1. Công tắc — file mới `inc/acf/HiddenPostACF.php`

Field ACF `TrueFalse` tên `tsh_hidden`:

- Nhãn: "Ẩn khỏi danh sách"
- Vị trí: `position: side` (cột phải, cạnh ô E-ticket)
- Helper text: "Bài vẫn vào được bằng link trực tiếp, nhưng không hiện ở trang chủ, danh sách, tìm kiếm và không lên Google. Dùng cho sự kiện riêng tư."
- Location: 3 CPT `khoa_hoc`, `workshop`, `dich_vu`

Đăng ký trong `configs/loadFile.php`.

### 2. Bộ lọc — file mới `inc/hooks/HiddenPostHook.php`

Một callback `pre_get_posts`. Bỏ qua (không lọc) khi bất kỳ điều nào sau đây đúng:

- `is_admin()` — admin phải thấy đủ bài để quản lý
- `$query->is_singular()` — **trang chi tiết phải mở được; đây chính là cái làm nên "unlisted"**
- post type của query không giao với 3 CPT
- query có bật cờ `tsh_include_hidden`

Còn lại thì thêm `meta_query`:

```php
'relation' => 'OR',
['key' => 'tsh_hidden', 'compare' => 'NOT EXISTS'],
['key' => 'tsh_hidden', 'value' => '1', 'compare' => '!='],
```

Nhánh `NOT EXISTS` là bắt buộc: bài cũ chưa từng lưu meta này vẫn phải hiện bình thường.

Gộp với `meta_query` sẵn có của query (nếu có) thay vì ghi đè.

### 3. Cờ thoát cho 3 query nội bộ

Có 3 chỗ tra ngược từ product WooCommerce về bài CPT, **chạy ở front-end lúc khách checkout**:

- `inc/functions/EticketFunction.php:20`
- `inc/woocommerce/WooCommerceHook.php:504`
- `inc/woocommerce/WcProductSync.php:95`

Nếu bộ lọc quét cả 3 query này thì **mua vé sự kiện ẩn sẽ không nhận được e-ticket**, và lỗi im lặng (hàm trả về `''`, không báo gì).

Thêm `'tsh_include_hidden' => true` vào mỗi query đó (1 dòng/chỗ). Query nội bộ nào về sau cần thấy bài ẩn thì chỉ việc thêm cờ này.

Ghi chú: `WcProductSync.php:95` đang có `suppress_filters => true`. `suppress_filters` chỉ tắt các filter `posts_*` (posts_where, posts_join…), **không tắt `pre_get_posts`** — nên vẫn phải thêm cờ.

### 4. Chặn Google — cùng file hook

Site đang dùng Yoast SEO (`wordpress-seo`).

- `wp_robots` (priority 100, chạy sau Yoast): nếu đang ở single của bài ẩn → thêm `noindex`, `nofollow`.
- `wpseo_exclude_from_sitemap_by_post_ids`: trả về danh sách ID bài ẩn để loại khỏi sitemap Yoast.

Nguồn sự thật là field `tsh_hidden`, không ghi đè meta noindex riêng của Yoast — tránh hai chỗ dữ liệu lệch nhau.

### 5. Dấu hiệu trong admin — cùng file hook

`display_post_states`: bài ẩn hiện chữ "— Ẩn" cạnh tiêu đề trong danh sách admin, để nhìn phát biết ngay bài nào đang ẩn mà không phải mở từng bài.

## Cách dùng

Mở bài → tick "Ẩn khỏi danh sách" → Cập nhật → copy link ở nút Xem → gửi riêng cho khách.

## Kiểm thử

1. Tick ẩn một workshop → workshop đó biến mất khỏi: trang chủ, `/workshop`, trang tìm kiếm (`/tim-kiem`), load-more AJAX, archive, taxonomy.
2. Mở thẳng link workshop đó → vẫn vào được, hiện đầy đủ, nút đặt lịch chạy.
3. Xem source trang đó → có `<meta name="robots" content="noindex, nofollow">`.
4. Mở `/sitemap_index.xml` → sitemap workshop không chứa bài đó.
5. **Bài ẩn có `eticket_days > 0`: đặt mua thử → đơn phải có `_tsh_eticket_expiry`, email e-ticket phải gửi được.** Đây là điểm dễ vỡ nhất, phải test thật.
6. Bỏ tick → bài hiện lại bình thường ở mọi nơi.
7. Bài cũ (chưa từng lưu `tsh_hidden`) vẫn hiện bình thường.
8. Danh sách admin → bài ẩn có chữ "— Ẩn" cạnh tiêu đề.
