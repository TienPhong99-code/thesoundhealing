# Popup chọn ngôn ngữ — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Hiện popup chọn ngôn ngữ (Tiếng Việt / English) khi khách vào trang chủ lần đầu, lưu lựa chọn để lần sau không hỏi lại.

**Architecture:** Tái dùng hệ thống modal generic có sẵn (`data-modal="lang"` + `window.modalOpen`). Một partial PHP render danh sách ngôn ngữ WPML từ `icl_get_languages()`; một file JS mở popup lần đầu và lưu lựa chọn vào `localStorage`. Chỉ nạp ở trang chủ.

**Tech Stack:** WordPress theme (PHP), WPML (`icl_get_languages`), Tailwind CDN (utility classes inline), vanilla JS (không jQuery), `localStorage`.

## Global Constraints

- Popup CHỈ nạp/hiện ở trang chủ: `is_front_page()`.
- CHỈ hiện lần đầu; KHÔNG auto-redirect ở lần sau.
- Lưu lựa chọn: `localStorage` khóa `tsh_lang`, giá trị `vi` | `en`. Có khóa = "đã hỏi rồi" → không hiện lại.
- Đóng popup không chọn = giữ Tiếng Việt, vẫn lưu (`tsh_lang='vi'`).
- Tái dùng modal có sẵn: markup `data-modal="lang"` + `.modal-box` + `[data-modal-close]`; mở bằng `window.modalOpen('lang')` (từ handle `mona-modal`, file `assets/scripts/modules/common/modal.js`).
- KHÔNG sửa file CSS (dùng Tailwind inline + state `[data-modal].is-active` đã có trong `style.css`). KHÔNG sửa DB.
- Escape output: `esc_url()`, `esc_attr()`, `esc_html()`; chuỗi hiển thị bọc `esc_html_e(..., 'monamedia')`.
- Ngôn ngữ WPML: lấy qua `icl_get_languages('skip_missing=0&orderby=KEY&order=DIR')`; mỗi item có key `language_code` (`vi`/`en`), `url` (URL trang hiện tại theo ngôn ngữ đó), `active` (1 nếu đang xem), `native_name`.
- Không có test suite tự động → mọi "test" là kiểm tra thủ công trên trình duyệt (view-source + DevTools Console/Application).

---

## File Structure

| Loại | File | Trách nhiệm |
|------|------|-------------|
| Mới | `wp-content/themes/thesoundhealing/partials/modals/modal-language.php` | Markup popup; render ngôn ngữ từ WPML; bail nếu WPML tắt/<2 ngôn ngữ |
| Mới | `wp-content/themes/thesoundhealing/assets/scripts/modules/common/lang-popup.js` | Mở popup lần đầu; lưu lựa chọn vào localStorage; gating |
| Sửa | `wp-content/themes/thesoundhealing/footer.php` | Include partial trong khối `is_front_page()` |
| Sửa | `wp-content/themes/thesoundhealing/inc/hooks/CommonHook.php` | Enqueue `mona-lang-popup` trong khối `is_front_page()` |

Tất cả đường dẫn bên dưới tương đối từ theme root: `wp-content/themes/thesoundhealing/`.

---

### Task 1: Markup popup ngôn ngữ + nhúng vào footer

**Files:**
- Create: `partials/modals/modal-language.php`
- Modify: `footer.php:26-28` (khối `if (is_front_page())`)

**Interfaces:**
- Consumes: hàm WPML `icl_get_languages()`; state CSS `[data-modal].is-active` có sẵn.
- Produces: một node DOM `[data-modal="lang"]` trên trang chủ (mặc định ẩn qua CSS), chứa các link `a.js-lang-choice[data-lang-code][data-lang-active]`. Task 2 dựa vào các selector này.

- [ ] **Step 1: Tạo file `partials/modals/modal-language.php`**

```php
<?php
defined('ABSPATH') || exit;

/**
 * Popup chọn ngôn ngữ (hiện ở trang chủ, lần đầu).
 * Chỉ render khi WPML bật và có >= 2 ngôn ngữ.
 */
if (!function_exists('icl_get_languages')) {
    return;
}

$mona_langs = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR');
if (empty($mona_langs) || !is_array($mona_langs) || count($mona_langs) < 2) {
    return;
}

$mona_lang_names = ['vi' => 'Tiếng Việt', 'en' => 'English'];
?>

<div data-modal="lang"
    class="modal-overlay fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <div class="modal-box bg-white w-full max-w-[360px] p-6 rounded-2xl relative flex flex-col gap-5">

        <!-- Close -->
        <button type="button" data-modal-close
            class="cursor-pointer absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-[#808080] hover:text-[#1b1c19] transition-colors"
            aria-label="<?php echo esc_attr__('Đóng', 'monamedia'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M18 6 6 18M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="flex flex-col gap-1.5 pr-8">
            <h2 class="text-[22px] font-bold text-[#1b1c19]">
                <?php esc_html_e('Chọn ngôn ngữ', 'monamedia'); ?>
                <span class="text-[#808080] font-normal"> / Select language</span>
            </h2>
        </div>

        <!-- Options -->
        <div class="flex flex-col gap-3">
            <?php foreach ($mona_langs as $mona_lang) :
                $mona_code      = $mona_lang['language_code'];
                $mona_label     = $mona_lang_names[$mona_code] ?? $mona_lang['native_name'];
                $mona_is_active = !empty($mona_lang['active']);
            ?>
                <a href="<?php echo esc_url($mona_lang['url']); ?>"
                    data-lang-code="<?php echo esc_attr($mona_code); ?>"
                    data-lang-active="<?php echo $mona_is_active ? '1' : '0'; ?>"
                    class="js-lang-choice flex items-center justify-between w-full py-3 px-4 rounded-xl border text-[15px] font-medium transition-colors <?php echo $mona_is_active ? 'border-[#c2a056] bg-[#faf7ef] text-[#1b1c19]' : 'border-[#c0c8c6] text-[#1b1c19] hover:bg-[#f5f4f1]'; ?>">
                    <span><?php echo esc_html($mona_label); ?></span>
                    <?php if ($mona_is_active) : ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c2a056" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</div>
```

- [ ] **Step 2: Nhúng partial vào `footer.php`**

Sửa khối `if (is_front_page())` hiện có (dòng 26-28). Thay:

```php
if (is_front_page()) {
    get_template_part('partials/sections/home/section', 'popup-du-an');
}
```

thành:

```php
if (is_front_page()) {
    get_template_part('partials/sections/home/section', 'popup-du-an');
    get_template_part('partials/modals/modal-language');
}
```

- [ ] **Step 3: Kiểm tra thủ công — markup render đúng & mặc định ẩn**

Mở trang chủ site local, xem View Source (Ctrl+U) hoặc DevTools Elements:
- Expected: có `<div data-modal="lang" ...>` gần cuối `<body>`.
- Bên trong có 2 link `a.js-lang-choice`: một `data-lang-code="vi"` (nhãn "Tiếng Việt"), một `data-lang-code="en"` (nhãn "English"); link ngôn ngữ đang xem có `data-lang-active="1"`.
- Link EN có `href` chứa `/en/`.
- Trong DevTools, node `[data-modal="lang"]` có `opacity: 0` (ẩn mặc định, chưa có class `is-active`).

Kiểm tra nhanh trang KHÁC trang chủ (vd một bài viết): Expected — KHÔNG có `[data-modal="lang"]` trong DOM.

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/thesoundhealing/partials/modals/modal-language.php wp-content/themes/thesoundhealing/footer.php
git commit -m "feat(lang-popup): markup popup chọn ngôn ngữ + nhúng ở trang chủ

Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>"
```

---

### Task 2: Logic mở popup + lưu lựa chọn (localStorage) + enqueue

**Files:**
- Create: `assets/scripts/modules/common/lang-popup.js`
- Modify: `inc/hooks/CommonHook.php:129-132` (khối `if (is_front_page())`)

**Interfaces:**
- Consumes: node `[data-modal="lang"]` và các `a.js-lang-choice[data-lang-code][data-lang-active]` từ Task 1; `window.modalOpen` / `window.modalClose` từ handle `mona-modal`.
- Produces: hành vi runtime — popup tự mở lần đầu, `localStorage['tsh_lang']` được set. Không có API cho task sau (task cuối).

- [ ] **Step 1: Tạo file `assets/scripts/modules/common/lang-popup.js`**

```js
// Popup chọn ngôn ngữ — hiện ở trang chủ lần đầu, nhớ lựa chọn bằng localStorage
(function () {
  'use strict';

  var STORAGE_KEY = 'tsh_lang';

  function getStored() {
    try {
      return localStorage.getItem(STORAGE_KEY);
    } catch (e) {
      return null;
    }
  }

  function setStored(code) {
    try {
      localStorage.setItem(STORAGE_KEY, code);
    } catch (e) {
      /* private mode: bỏ qua, không nhớ được */
    }
  }

  function closeLang(modal) {
    if (window.modalClose) {
      window.modalClose('lang');
    } else {
      modal.classList.remove('is-active');
      document.body.classList.remove('no-scroll');
    }
  }

  function openLang(modal) {
    if (window.modalOpen) {
      window.modalOpen('lang');
    } else {
      modal.classList.add('is-active');
      document.body.classList.add('no-scroll');
    }
  }

  function init() {
    var modal = document.querySelector('[data-modal="lang"]');
    if (!modal) return; // không ở trang chủ / WPML tắt

    // Lưu lựa chọn khi bấm 1 ngôn ngữ
    var links = modal.querySelectorAll('.js-lang-choice');
    Array.prototype.forEach.call(links, function (link) {
      link.addEventListener('click', function (e) {
        setStored(link.getAttribute('data-lang-code') || 'vi');
        // Ngôn ngữ đang xem: không cần reload, chỉ đóng cho mượt
        if (link.getAttribute('data-lang-active') === '1') {
          e.preventDefault();
          closeLang(modal);
        }
        // Ngôn ngữ khác: để link tự điều hướng (vd EN → /en/)
      });
    });

    if (getStored()) return; // đã hỏi rồi → không mở lại

    setStored('vi'); // mặc định: dù đóng kiểu nào cũng không hỏi lại
    openLang(modal);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
```

- [ ] **Step 2: Enqueue script trong `inc/hooks/CommonHook.php`**

Trong khối `if (is_front_page()) { ... }` (dòng 129-132), thêm dòng enqueue (đặt sau `mona-home`):

```php
   if (is_front_page()) {
      wp_enqueue_script('mona-hero', MONA_THEME_PATH_URI . '/assets/scripts/modules/home/hero.js', array('mona-gsap', 'mona-MorphSVGPlugin', 'mona-SplitText'), filemtime(MONA_THEME_PATH . '/assets/scripts/modules/home/hero.js'), array('in_footer' => true));
      wp_enqueue_script('mona-home', MONA_THEME_PATH_URI . '/assets/scripts/home.js', array('jquery', 'mona-swiper', 'mona-main', 'mona-gsap', 'mona-ScrollTrigger', 'mona-MorphSVGPlugin', 'mona-SplitText', 'mona-hero'), filemtime(MONA_THEME_PATH . '/assets/scripts/home.js'), array('in_footer' => true));
      wp_enqueue_script('mona-lang-popup', MONA_THEME_PATH_URI . '/assets/scripts/modules/common/lang-popup.js', array('mona-modal'), filemtime(MONA_THEME_PATH . '/assets/scripts/modules/common/lang-popup.js'), array('in_footer' => true));
   }
```

(Chỉ dòng `mona-lang-popup` là mới; hai dòng trên giữ nguyên để định vị.)

- [ ] **Step 3: Kiểm tra thủ công — hiện lần đầu**

Trong DevTools > Application > Local Storage, xóa khóa `tsh_lang` (hoặc dùng cửa sổ ẩn danh). Tải lại trang chủ.
- Expected: popup TỰ hiện (node `[data-modal="lang"]` có class `is-active`, `body` có class `no-scroll`). Console không lỗi.
- Application > Local Storage: có `tsh_lang = "vi"`.

- [ ] **Step 4: Kiểm tra thủ công — chọn English**

Xóa `tsh_lang`, tải lại trang chủ, bấm nút **English**.
- Expected: điều hướng sang URL có `/en/`. Local Storage: `tsh_lang = "en"`.
- Tải lại `/en/`: popup KHÔNG hiện.

- [ ] **Step 5: Kiểm tra thủ công — chọn Tiếng Việt & đóng không chọn**

- Xóa `tsh_lang`, tải lại `/`, bấm **Tiếng Việt** (ngôn ngữ đang xem): Expected — popup đóng, KHÔNG reload, `body` bỏ class `no-scroll`, `tsh_lang = "vi"`. Tải lại `/`: popup không hiện.
- Xóa `tsh_lang`, tải lại `/`, đóng bằng nút X (rồi thử lại với phím Escape, và click ra ngoài `.modal-box`): mỗi lần Expected — popup đóng, `tsh_lang = "vi"`, `body` không kẹt `no-scroll`. Tải lại `/`: popup không hiện.

- [ ] **Step 6: Kiểm tra thủ công — trang không phải trang chủ**

Vào một bài viết/khóa học (không phải trang chủ) với `tsh_lang` đã xóa.
- Expected: KHÔNG có script `lang-popup.js` được tải (Network), KHÔNG có popup.

- [ ] **Step 7: Commit**

```bash
git add wp-content/themes/thesoundhealing/assets/scripts/modules/common/lang-popup.js wp-content/themes/thesoundhealing/inc/hooks/CommonHook.php
git commit -m "feat(lang-popup): mở popup lần đầu ở trang chủ + lưu lựa chọn localStorage

Co-Authored-By: Claude Opus 4.8 (1M context) <noreply@anthropic.com>"
```

---

## Self-Review

**Spec coverage:**
- Hiện popup chỉ trang chủ, lần đầu → Task 1 (nhúng trong `is_front_page()`) + Task 2 (enqueue trong `is_front_page()`, gating localStorage). ✔
- Lần sau chỉ ẩn popup, không auto-redirect → Task 2 Step 1 (`if (getStored()) return`), không có logic redirect. ✔
- Đóng không chọn = giữ VI + lưu → Task 2 `setStored('vi')` ngay lúc mở; mọi cách đóng đều an toàn. ✔
- Lưu bằng localStorage `tsh_lang` = vi|en → Task 2. ✔
- Bail khi WPML tắt/<2 ngôn ngữ → Task 1 Step 1 (early `return`) + Task 2 (`if (!modal) return`). ✔
- Không sửa CSS/DB → không task nào đụng. ✔

**Placeholder scan:** Không có TBD/TODO; mọi step có code/lệnh/kỳ vọng cụ thể. ✔

**Type/selector consistency:** Task 1 tạo `[data-modal="lang"]`, `.js-lang-choice`, `data-lang-code`, `data-lang-active` — Task 2 dùng đúng các tên này. Khóa `tsh_lang` nhất quán. Handle enqueue `mona-lang-popup` khớp tên file. ✔
