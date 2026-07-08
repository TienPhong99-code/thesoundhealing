# Popup chọn ngôn ngữ khi vào trang chủ

**Ngày:** 2026-07-08
**Branch:** feature/lich-dinh-ky (hoặc branch riêng cho tính năng)
**Trạng thái:** Đã duyệt thiết kế, chờ viết implementation plan

## Mục tiêu

Khi khách vào **trang chủ lần đầu**, hiện một popup cho phép chọn ngôn ngữ (Tiếng Việt / English). Lưu lựa chọn để những lần sau **không hỏi lại**.

## Quyết định đã chốt

| Vấn đề | Quyết định |
|--------|-----------|
| Khi nào hiện | Chỉ **trang chủ** (`is_front_page()`), chỉ **lần đầu** (chưa có lựa chọn lưu) |
| Lần sau quay lại | **Chỉ ẩn popup** — KHÔNG auto-redirect. Khách ở nguyên URL họ vào |
| Đóng popup không chọn | **Cho phép** (X / bấm ra ngoài / Escape) = giữ Tiếng Việt, và cũng lưu lại để không hỏi nữa |
| Cơ chế lưu | `localStorage` (không cần cookie/PHP server-side vì không auto-redirect) |
| Lưu gì | 1 khóa `tsh_lang` = `vi` \| `en`. Có khóa = "đã hỏi rồi" → không hiện lại |

Lý do chọn `localStorage` thay vì cookie: không cần PHP đọc lựa chọn phía server (vì không redirect tự động), nên tránh được rủi ro đụng full-page cache của cookie. Nếu tương lai muốn auto-redirect server-side thì mới cần chuyển sang cookie — hiện tại YAGNI.

## Bối cảnh kỹ thuật (đã khảo sát)

- **WPML**: mặc định `vi`, tiếng Anh prefix `/en/`. Danh sách ngôn ngữ + URL trang hiện tại lấy qua `icl_get_languages('skip_missing=0&orderby=KEY&order=DIR')`. Mỗi item có `language_code` (`vi`/`en`), `url` (URL trang hiện tại theo ngôn ngữ đó — ở trang chủ là trang chủ ngôn ngữ đó), `active` (1 nếu đang xem), `native_name`.
  - Nơi dùng hiện có: `partials/components/header-main.php:12-31` (`mona_render_lang_switcher()`), map nhãn `['vi' => 'VN', 'en' => 'ENG']`.
- **Hệ thống modal generic** (`assets/scripts/modules/common/modal.js`):
  - Markup: `<div data-modal="ID" class="... fixed inset-0 z-[9999] ...">` chứa `.modal-box`, nút đóng `[data-modal-close]`.
  - Mở/đóng bằng class `is-active` trên `[data-modal]` + `no-scroll` trên `body`.
  - Đóng qua: nút `[data-modal-close]`, click backdrop (ngoài `.modal-box`), phím `Escape`.
  - API toàn cục: `window.modalOpen(id)`, `window.modalClose(target)` (modal.js:100-101).
  - CSS state `[data-modal].is-active` đã có sẵn trong `assets/css/style.css` (~dòng 550-582). Mặc định `[data-modal]` ẩn (`opacity:0; pointer-events:none`).
- **Nhúng modal**: `footer.php:20-28` render các modal ở body-level; đã có pattern `if (is_front_page())` (dòng 26).
- **Enqueue script**: `inc/hooks/CommonHook.php`; `mona-modal` enqueue tại dòng 123; đã có khối `if (is_front_page())` tại dòng 129-132 để nhét script front-page.
- **Chưa có** localStorage/cookie cho preference nào trong code theme → không xung đột.
- **Không có** biến JS toàn cục (`mona_params`/ajax_url đã bị comment) → truyền dữ liệu ngôn ngữ qua markup, không cần localize.

## Kiến trúc

### 1. Markup — file mới `partials/modals/modal-language.php`

- Guard: `defined('ABSPATH') || exit;`
- Lấy `$langs = icl_get_languages(...)`. **Bail (không render gì)** nếu `!function_exists('icl_get_languages')` hoặc số ngôn ngữ `< 2`.
- Cấu trúc theo đúng chuẩn modal có sẵn:
  - `<div data-modal="lang" class="modal-overlay fixed inset-0 z-[9999] flex items-center justify-center p-4">`
  - Bên trong `.modal-box` (Tailwind, giống modal-share): tiêu đề song ngữ (vd "Chọn ngôn ngữ / Select language"), và mỗi ngôn ngữ là một `<a>`:
    - `href="{esc_url($item['url'])}"`, thuộc tính `data-lang-code="{$item['language_code']}"`, `class="js-lang-choice ..."`.
    - Nhãn dùng map `['vi' => 'Tiếng Việt', 'en' => 'English']` (fallback `native_name`), có thể đánh dấu ngôn ngữ `active`.
  - Nút đóng `[data-modal-close]` (X) như modal-share — vì cho phép đóng = giữ Tiếng Việt.
- Style: **Tailwind utility classes ngay trong markup** (đúng convention theme). Không sửa file CSS.
- Chuỗi hiển thị bọc `esc_html_e(..., 'monamedia')` để hỗ trợ dịch chuẩn theme.

### 2. Nhúng — sửa `footer.php`

Trong khối `if (is_front_page())` hiện có (dòng 26-28), thêm:
```php
get_template_part('partials/modals/modal-language');
```
(Đặt cạnh dòng `section-popup-du-an` đang có.)

### 3. Logic — file JS mới `assets/scripts/modules/common/lang-popup.js`

IIFE, chạy khi DOM ready:
1. Tìm `var modal = document.querySelector('[data-modal="lang"]')`. Nếu không có → thoát (không ở trang chủ / WPML tắt).
2. Đọc `localStorage.getItem('tsh_lang')`. Nếu **đã có giá trị** → thoát (đã hỏi rồi).
3. Chưa có → `localStorage.setItem('tsh_lang', 'vi')` (mặc định, để dù đóng kiểu nào cũng không hỏi lại) rồi `window.modalOpen('lang')`.
4. Bắt sự kiện click trên các `a.js-lang-choice` trong modal: cập nhật `localStorage.setItem('tsh_lang', code)` theo `data-lang-code`, sau đó **để link tự điều hướng** (chọn EN → sang `/en/`; chọn VN → cùng URL, có thể `preventDefault` + `modalClose('lang')` để chỉ đóng cho mượt).
5. Có `try/catch` quanh truy cập `localStorage` để an toàn khi trình duyệt chặn (private mode) — nếu lỗi thì vẫn mở popup, chỉ là không nhớ được.

Lý do set cờ ngay lúc mở (bước 3): tránh phải hook vào cơ chế đóng nội bộ của modal.js — mọi cách đóng (X/backdrop/Escape) đều đã an toàn vì cờ đã set.

### 4. Enqueue — sửa `inc/hooks/CommonHook.php`

Trong khối `if (is_front_page())` (dòng 129-132), thêm:
```php
wp_enqueue_script(
   'mona-lang-popup',
   MONA_THEME_PATH_URI . '/assets/scripts/modules/common/lang-popup.js',
   array('mona-modal'),
   filemtime(MONA_THEME_PATH . '/assets/scripts/modules/common/lang-popup.js'),
   array('in_footer' => true)
);
```
Phụ thuộc `mona-modal` để `window.modalOpen` tồn tại. Là classic script (không nằm trong danh sách chuyển `type="module"`).

## Luồng dữ liệu

```
Khách vào "/" lần đầu
  → PHP footer render modal-language (icl_get_languages → <a href=url data-lang-code>)
  → lang-popup.js (DOM ready): localStorage['tsh_lang'] rỗng?
       Có rỗng → set 'vi' + modalOpen('lang')
  → Khách bấm "English"  → set 'en' → điều hướng /en/
     Khách bấm "Tiếng Việt" hoặc đóng (X/ngoài/Escape) → giữ 'vi', đóng modal

Khách vào "/" lần sau
  → lang-popup.js: localStorage['tsh_lang'] đã có → thoát, không mở popup
  → (không auto-redirect — khách ở nguyên trang họ vào)
```

## Xử lý lỗi & biên

- **WPML tắt / 1 ngôn ngữ**: `modal-language.php` bail → không có `[data-modal="lang"]` → lang-popup.js thoát ngay. Không lỗi.
- **localStorage bị chặn (private mode)**: `try/catch` → popup vẫn mở, chỉ không nhớ được (chấp nhận được).
- **Chỉ trang chủ**: modal + script chỉ enqueue/nhúng khi `is_front_page()`. Các trang khác hoàn toàn không tải.
- **SEO/bot**: không auto-redirect, không cloaking; popup thuần client-side, nội dung chỉ là link tới URL ngôn ngữ đã tồn tại.

## File đụng tới

| Loại | File |
|------|------|
| Mới | `partials/modals/modal-language.php` |
| Mới | `assets/scripts/modules/common/lang-popup.js` |
| Sửa | `footer.php` — include modal trong khối `is_front_page()` |
| Sửa | `inc/hooks/CommonHook.php` — enqueue `mona-lang-popup` trong khối `is_front_page()` (dòng ~129-132) |

Không sửa CSS (dùng Tailwind + state modal có sẵn). Không sửa DB.

## Kiểm thử (thủ công, không có test suite)

1. Xóa `localStorage` (hoặc dùng cửa sổ ẩn danh), vào `/` → popup hiện.
2. Bấm **English** → chuyển sang `/en/`, `tsh_lang='en'`. Reload `/en/` → không hiện popup.
3. Xóa storage, vào `/`, bấm **Tiếng Việt** → popup đóng, ở `/`, `tsh_lang='vi'`. Reload → không hiện.
4. Xóa storage, vào `/`, đóng bằng X / Escape / bấm ra ngoài → popup đóng, `tsh_lang='vi'`. Reload → không hiện.
5. Vào một trang không phải trang chủ (vd bài viết) lần đầu → **không** có popup.
6. Kiểm tra không lỗi console; `body` không kẹt class `no-scroll` sau khi đóng.
