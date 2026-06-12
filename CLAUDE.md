# CLAUDE.md

File này cung cấp hướng dẫn cho Claude Code (claude.ai/code) khi làm việc với code trong repository này.

## Lệnh thường dùng

```bash
# Frontend (chạy từ wp-content/themes/thesoundhealing/)
npm run dev      # Watch Tailwind CSS → assets/css/tailwind.output.css
npm run build    # Minify Tailwind CSS cho production
```

Không có PHP linter hay test suite. Kiểm tra thay đổi bằng cách chạy site WordPress ở local.

## Kiến trúc

**WordPress custom theme** cho thương hiệu wellness (The Sound Healing), nằm tại `wp-content/themes/thesoundhealing/`.

**Luồng load file:** `functions.php` → `inc/init.php` → `configs/loadFile.php`. Toàn bộ file PHP (hooks, CPT, ACF, helpers) được đăng ký tập trung trong `configs/loadFile.php`. Khi thêm file PHP mới, phải đăng ký tại đây.

**Tầng PHP:**
- `inc/classes/` — Singleton `Mona_SetupTheme` xử lý theme_support, login customization, tắt block assets
- `inc/hooks/` — WordPress actions/filters (CommonHook, ImageHook, PostHook, AjaxHook, CF7Hook, SearchHook, ShortcodeHook)
- `inc/cpt/` — 5 custom post type: `khoa_hoc`, `workshop`, `dich_vu`, `du_an`, `tuyen_dung`
- `inc/acf/` — 13 file ACF dùng `vinkla/extended-acf` v14 để định nghĩa field group theo kiểu fluent type-safe
- `inc/functions/` — Helper: `mona_replace_tel()`, `mona_debug()`, `mona_remove_p_tag()`, `mona_regist_acf_field_group()`
- `inc/ajax/` — AJAX handlers cho nội dung động

**Tầng template:**
- `front-page.php`, `single.php`, `archive.php`, `page.php` — Cấu trúc chuẩn WordPress
- `page-template/` — 8 page template tuỳ chỉnh (khoa-hoc, workshop, dich-vu, about, lien-he, search-results, v.v.)
- `partials/sections/` — Section partial phân theo trang (home/, khoa-hoc/, dich-vu/, v.v.)
- `partials/components/` — Component tái sử dụng (card, search-booking, pagination, v.v.)
- `partials/modals/` — Nội dung modal

**Tầng CSS:**
- `assets/css/style.css` — Stylesheet chính ~50KB chứa CSS variables, import font Roboto, style component
- `assets/css/tailwind.css` — Entry point Tailwind; output ra `tailwind.output.css`
- Tailwind CDN cũng được inject qua `CommonHook.php` (wp_head priority 1) với theme token định nghĩa inline

**CSS variables (design token):**
```css
--color-pri: #c2a056   /* vàng gold */
--color-sec: #1b1c19   /* tối */
--size-hd: 80px        /* chiều cao header (60px trên tablet) */
--pd-sc: 40px          /* padding section */
--rs: 16px             /* khoảng cách responsive (8px trên tablet) */
```

**Tầng JS:**
- File entry theo trang: `main.js` (global), `home.js`, `khoa-hoc.js`, `ws.js`, `dich-vu.js`, `single.js`
- `assets/scripts/modules/common/` — Module dùng chung: header, swiper, fancybox, modal, tab, accordion, animation, footer, utils
- `assets/scripts/modules/home/`, `single/`, `blog/` — Module riêng theo trang
- Thư viện ngoài tại `assets/library/`: Swiper, FancyBox, Flatpickr
- Stack: jQuery + GSAP + ScrollTrigger + Swiper; khởi tạo trong `$(document).ready()`

## Pattern ACF

Field group dùng `vinkla/extended-acf` với PHP builder kiểu fluent. Tất cả group được đăng ký qua helper `mona_regist_acf_field_group()`:

```php
use Extended\ACF\Fields\{Image, Text, Repeater, Tab};
use Extended\ACF\Location;

mona_regist_acf_field_group([
    'title'    => 'Chi tiết khóa học',
    'location' => [Location::where('post_type', '==', 'khoa_hoc')],
    'fields'   => [
        Tab::make('Giới thiệu')->placement('left'),
        Text::make('Cấp độ', 'level'),
    ],
]);
```

## Form CF7

ID form được định nghĩa là constant trong `functions.php` (`MONA_CF7_KHOA_HOC`, `MONA_CF7_WORKSHOP`, `MONA_CF7_DICH_VU`). Hook validate và xử lý submit nằm trong `inc/hooks/CF7Hook.php`.
