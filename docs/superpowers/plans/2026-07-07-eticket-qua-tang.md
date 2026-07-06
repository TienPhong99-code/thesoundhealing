# E-ticket làm quà tặng — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Trên trang cảm ơn, khách bấm "E-ticket làm quà tặng" → html2canvas chụp khối voucher ẩn → tự tải ảnh PNG. Hạn e-ticket = ngày đặt + số ngày cấu hình theo từng dịch vụ.

**Architecture:** ACF field `eticket_days` (số ngày) trên 3 CPT. Khi tạo đơn, tra CPT gốc từ product trong cart → tính `_tsh_eticket_expiry` lưu vào order. Trang cảm ơn render khối `.tsh-voucher` ẩn (nếu có expiry) + bật nút; html2canvas (client) xuất PNG.

**Tech Stack:** WordPress, WooCommerce, vinkla/extended-acf, html2canvas, jQuery/vanilla JS. Không test framework, không PHP CLI.

## Global Constraints

- Theme path: `wp-content/themes/thesoundhealing/`. Hằng số: `MONA_THEME_PATH_URI` (uri), `MONA_THEME_PATH` (đường dẫn), `MONA_THEME_INC_PATH` (inc). Text domain `'monamedia'`.
- Order meta: `_tsh_eticket_days` (số ngày), `_tsh_eticket_expiry` (ngày hết hạn, format `Y-m-d`).
- ACF field: `eticket_days` (Number) trên `khoa_hoc`, `workshop`, `dich_vu`.
- Tra CPT gốc từ product: CPT lưu meta `_wc_product_id`. Query `get_posts(['post_type'=>['khoa_hoc','workshop','dich_vu'],'meta_key'=>'_wc_product_id','meta_value'=>$product_id,'posts_per_page'=>1,'fields'=>'ids'])`.
- Tại hook `woocommerce_checkout_create_order`, order **chưa có line items** → đọc sản phẩm từ **CART** (`WC()->cart->get_cart()`), lấy `$item['product_id']`.
- Voucher gọn: Mã e-ticket (`#` + str_pad order_id 5 số), Dịch vụ (tên item đầu), Số người (`_booking_guests`, mặc định "1"), Ngày hết hạn (`d/m/Y`), Hotline 2 số: English `0939 624 684`, Tiếng Việt `0906 502 582`, ghi chú "liên hệ hotline để đặt lịch". KHÔNG hiển thị người mua/giờ/chi nhánh/thanh toán.
- Nút hiện CHỈ khi order có `_tsh_eticket_expiry`; dịch vụ không set `eticket_days` → không lưu meta → không hiện nút.
- Tiêu đề voucher: `E-TICKET QUÀ TẶNG`. Text nút: `E-TICKET LÀM QUÀ TẶNG`.
- Repo KHÔNG có test PHP và KHÔNG có PHP CLI → bỏ `php -l`; verify bằng đọc code + user test trên WordPress live **sau reset OPcache**. Deploy vào `public_html/wp-content/themes/thesoundhealing/...` (FTP root = home, không phải docroot).

---

### Task 1: ACF field `eticket_days` cho 3 CPT

**Files:**
- Create: `wp-content/themes/thesoundhealing/inc/acf/EticketACF.php`
- Modify: `wp-content/themes/thesoundhealing/configs/loadFile.php` (thêm 1 dòng đăng ký)

**Interfaces:**
- Produces: ACF field `eticket_days` (Number) trên khoa_hoc/workshop/dich_vu — đọc bằng `get_field('eticket_days', $cpt_id)`.

- [ ] **Step 1: Tạo file EticketACF.php**

```php
<?php

use Extended\ACF\Fields\Number;
use Extended\ACF\Location;

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    mona_regist_acf_field_group([
        'title'    => 'E-ticket quà tặng',
        'style'    => 'default',
        'position' => 'side',
        'location' => [
            Location::where('post_type', '==', 'khoa_hoc'),
            Location::where('post_type', '==', 'workshop'),
            Location::where('post_type', '==', 'dich_vu'),
        ],
        'fields' => [
            Number::make('Số ngày hiệu lực e-ticket', 'eticket_days')
                ->helperText('E-ticket quà tặng có hạn bao nhiêu ngày kể từ ngày đặt. Để trống/0 = không phát hành e-ticket.')
                ->min(0)
                ->default(0),
        ],
    ]);
});
```

- [ ] **Step 2: Đăng ký trong loadFile.php** (thêm sau dòng `.../acf/DichVuPageACF.php,` — cạnh nhóm ACF)

```php
    MONA_THEME_INC_PATH . '/acf/EticketACF.php',
```

- [ ] **Step 3: Kiểm tra syntax** (php -l bỏ — không có CLI; đọc lại brace/paren, đúng use statements)

- [ ] **Step 4: Kiểm thử thủ công (sau reset OPcache)**

Mở 1 khóa học/workshop/dịch vụ trong admin → thấy box "E-ticket quà tặng" (cột side) với ô "Số ngày hiệu lực e-ticket". Nhập 90, Lưu.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/acf/EticketACF.php wp-content/themes/thesoundhealing/configs/loadFile.php
git commit -m "feat(eticket): ACF field số ngày hiệu lực e-ticket cho 3 CPT"
```

---

### Task 2: Tính & lưu hạn e-ticket khi tạo đơn

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

**Interfaces:**
- Consumes: field `eticket_days` (Task 1); meta `_wc_product_id` trên CPT.
- Produces: order meta `_tsh_eticket_days`, `_tsh_eticket_expiry` (Y-m-d).
- Method `save_eticket_meta(\WC_Order $order, array $data): void` — hook `woocommerce_checkout_create_order`.

- [ ] **Step 1: Thêm method** (đặt gần `save_payment_type_meta`)

```php
    /**
     * Tính hạn e-ticket khi tạo đơn: lấy sản phẩm đầu trong cart → tra CPT gốc
     * (qua _wc_product_id) → đọc eticket_days. Nếu > 0 → lưu ngày hết hạn vào order.
     * (order chưa có line items ở hook này → đọc từ cart.)
     */
    public function save_eticket_meta(\WC_Order $order, array $data): void
    {
        $cart = WC()->cart;
        if (!$cart) return;
        $items = $cart->get_cart();
        if (empty($items)) return;

        $first      = reset($items);
        $product_id = (int) ($first['product_id'] ?? 0);
        if (!$product_id) return;

        $cpt = get_posts([
            'post_type'      => ['khoa_hoc', 'workshop', 'dich_vu'],
            'meta_key'       => '_wc_product_id',
            'meta_value'     => $product_id,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);
        if (empty($cpt)) return;

        $days = (int) get_field('eticket_days', $cpt[0]);
        if ($days <= 0) return;

        $expiry = date('Y-m-d', current_time('timestamp') + $days * DAY_IN_SECONDS);
        $order->update_meta_data('_tsh_eticket_days', $days);
        $order->update_meta_data('_tsh_eticket_expiry', $expiry);
    }
```

- [ ] **Step 2: Đăng ký hook trong `__construct()`** (thêm sau dòng đăng ký `save_payment_type_meta`)

```php
        add_action('woocommerce_checkout_create_order', [$this, 'save_eticket_meta'], 10, 2);
```

- [ ] **Step 3: Kiểm tra syntax** (đọc lại brace/paren cân bằng, method nằm trong class)

- [ ] **Step 4: Kiểm thử thủ công (sau reset OPcache)**

Đặt 1 đơn cho dịch vụ đã set `eticket_days=90`. Vào admin đơn → Custom Fields (hoặc DB `wp_wc_orders_meta`): có `_tsh_eticket_days=90`, `_tsh_eticket_expiry` = ngày đặt + 90. Đặt đơn cho dịch vụ KHÔNG set số ngày → không có 2 meta này.

- [ ] **Step 5: Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "feat(eticket): tính + lưu ngày hết hạn e-ticket khi tạo đơn"
```

---

### Task 3: Khối voucher + bật nút trên trang cảm ơn + CSS

**Files:**
- Modify: `wp-content/themes/thesoundhealing/woocommerce/checkout/thankyou.php`
- Modify: `wp-content/themes/thesoundhealing/assets/css/style.css`

**Interfaces:**
- Consumes: order meta `_tsh_eticket_expiry` (Task 2); biến sẵn có trong template `$order`, `$order_id`, `$service_name`, `$b_guests`.
- Produces: nút `#tsh-eticket-btn[data-order]` + khối `.tsh-voucher` (Task 4 JS dùng).

- [ ] **Step 1: Thêm biến hạn e-ticket vào block khai báo** (sau dòng `$service_name = ...;`)

```php
            $eticket_expiry = $order->get_meta('_tsh_eticket_expiry');
```

- [ ] **Step 2: Thay nút "E-TICKET LÀM QUÀ TẶNG"** (khối `.tsh-ty-actions`) — chỉ hiện khi có e-ticket

Thay:
```php
            <button type="button" class="tsh-ty-btn tsh-ty-btn--ghost" disabled aria-disabled="true" title="<?php esc_attr_e('Tính năng sắp ra mắt', 'monamedia'); ?>"><?php esc_html_e('E-TICKET LÀM QUÀ TẶNG', 'monamedia'); ?></button>
```
bằng:
```php
            <?php if ($eticket_expiry) : ?>
            <button type="button" id="tsh-eticket-btn" class="tsh-ty-btn tsh-ty-btn--ghost" data-order="<?php echo esc_attr(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?>"><?php esc_html_e('E-TICKET LÀM QUÀ TẶNG', 'monamedia'); ?></button>
            <?php endif; ?>
```

- [ ] **Step 3: Render khối voucher ẩn** (thêm ngay TRƯỚC dòng `</div><!-- /.tsh-ty-body -->` ở cuối template)

```php
        <?php if ($eticket_expiry) : ?>
        <!-- Voucher e-ticket (ẩn ngoài màn hình, html2canvas chụp) -->
        <div class="tsh-voucher" aria-hidden="true">
            <div class="tsh-voucher__content">
                <p class="tsh-voucher__brand">THE SOUND HEALING</p>
                <h2 class="tsh-voucher__title"><?php esc_html_e('E-TICKET QUÀ TẶNG', 'monamedia'); ?></h2>
                <div class="tsh-voucher__code">
                    <span><?php esc_html_e('Mã e-ticket', 'monamedia'); ?></span>
                    <strong>#<?php echo esc_html(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?></strong>
                </div>
                <div class="tsh-voucher__rows">
                    <div class="tsh-voucher__row"><span><?php esc_html_e('Dịch vụ', 'monamedia'); ?></span><strong><?php echo esc_html($service_name); ?></strong></div>
                    <div class="tsh-voucher__row"><span><?php esc_html_e('Số người', 'monamedia'); ?></span><strong><?php echo esc_html(($b_guests ?: '1') . ' ' . __('người', 'monamedia')); ?></strong></div>
                    <div class="tsh-voucher__row"><span><?php esc_html_e('Ngày hết hạn', 'monamedia'); ?></span><strong><?php echo esc_html(date_i18n('d/m/Y', strtotime($eticket_expiry))); ?></strong></div>
                </div>
                <div class="tsh-voucher__hotline">
                    <p><?php esc_html_e('Liên hệ hotline để đặt lịch:', 'monamedia'); ?></p>
                    <p><strong>English:</strong> 0939 624 684 &nbsp; | &nbsp; <strong>Tiếng Việt:</strong> 0906 502 582</p>
                </div>
                <p class="tsh-voucher__note"><?php esc_html_e('Vui lòng xuất trình mã e-ticket khi sử dụng dịch vụ.', 'monamedia'); ?></p>
            </div>
            <div class="tsh-voucher__banner">
                <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/banner-confirm.png'); ?>" alt="" crossorigin="anonymous">
            </div>
        </div>
        <?php endif; ?>

- [ ] **Step 4: Thêm CSS `.tsh-voucher` vào cuối `assets/css/style.css`**

```css
/* ── E-ticket voucher (html2canvas chụp — đặt ngoài màn hình) ───────── */
.tsh-voucher {
  position: absolute;
  left: -9999px;
  top: 0;
  width: 900px;
  display: flex;
  background: #fff;
  border: 1px solid #e4e2dd;
  border-radius: 16px;
  overflow: hidden;
  font-family: 'Roboto', sans-serif;
}
.tsh-voucher__content {
  flex: 1;
  padding: 36px 40px;
}
.tsh-voucher__brand {
  font-size: 13px;
  letter-spacing: 2px;
  color: var(--color-pri);
  font-weight: 600;
  margin: 0 0 4px;
}
.tsh-voucher__title {
  font-size: 26px;
  font-weight: 800;
  color: var(--color-sec);
  margin: 0 0 20px;
}
.tsh-voucher__code {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 18px;
  background: #faf8f4;
  border: 1px dashed var(--color-pri);
  border-radius: 12px;
  margin-bottom: 20px;
}
.tsh-voucher__code span { color: #717171; font-size: 13px; }
.tsh-voucher__code strong { color: var(--color-pri); font-size: 22px; font-weight: 800; letter-spacing: 1px; }
.tsh-voucher__rows { margin-bottom: 20px; }
.tsh-voucher__row {
  display: flex;
  justify-content: space-between;
  gap: 16px;
  padding: 10px 0;
  border-bottom: 1px solid #f0ede6;
}
.tsh-voucher__row span { color: #717171; font-size: 14px; }
.tsh-voucher__row strong { color: var(--color-sec); font-size: 14px; font-weight: 600; text-align: right; }
.tsh-voucher__hotline {
  padding: 14px 16px;
  background: #fbf8f0;
  border-radius: 12px;
  margin-bottom: 14px;
}
.tsh-voucher__hotline p { margin: 0; font-size: 14px; color: var(--color-sec); }
.tsh-voucher__hotline p:first-child { color: #717171; font-size: 12px; margin-bottom: 4px; }
.tsh-voucher__note { margin: 0; font-size: 12px; color: #8a8577; font-style: italic; }
.tsh-voucher__banner {
  width: 320px;
  flex-shrink: 0;
}
.tsh-voucher__banner img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
```

- [ ] **Step 5: Kiểm thử thủ công (sau reset OPcache)**

Vào trang cảm ơn của đơn có e-ticket → nút "E-TICKET LÀM QUÀ TẶNG" hiện (không disabled). Mở DevTools → tìm `.tsh-voucher` trong DOM (nằm ngoài màn hình, left:-9999px) → kiểm nội dung: mã, dịch vụ, số người, ngày hết hạn, 2 hotline, ảnh banner. Đơn không có e-ticket → không có nút + không có khối voucher.

- [ ] **Step 6: Commit**

```bash
git add wp-content/themes/thesoundhealing/woocommerce/checkout/thankyou.php wp-content/themes/thesoundhealing/assets/css/style.css
git commit -m "feat(eticket): render voucher ẩn + bật nút trên trang cảm ơn"
```

---

### Task 4: html2canvas + eticket.js + enqueue

**Files:**
- Create: `wp-content/themes/thesoundhealing/assets/library/html2canvas/html2canvas.min.js` (tải về)
- Create: `wp-content/themes/thesoundhealing/assets/scripts/eticket.js`
- Modify: `wp-content/themes/thesoundhealing/inc/hooks/CommonHook.php` (enqueue theo trang)

**Interfaces:**
- Consumes: nút `#tsh-eticket-btn[data-order]` + khối `.tsh-voucher` (Task 3); global `html2canvas`.

- [ ] **Step 1: Tải html2canvas về assets/library**

```bash
mkdir -p wp-content/themes/thesoundhealing/assets/library/html2canvas
curl -sL https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js -o wp-content/themes/thesoundhealing/assets/library/html2canvas/html2canvas.min.js
wc -c wp-content/themes/thesoundhealing/assets/library/html2canvas/html2canvas.min.js
```
Expected: file > 100000 bytes (html2canvas 1.4.1 ~ 200KB). Nếu curl bị chặn mạng → tải thủ công từ https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js và đặt đúng path.

- [ ] **Step 2: Tạo `assets/scripts/eticket.js`**

```javascript
(function () {
  'use strict';
  var btn = document.getElementById('tsh-eticket-btn');
  var voucher = document.querySelector('.tsh-voucher');
  if (!btn || !voucher || typeof html2canvas === 'undefined') return;

  btn.addEventListener('click', function () {
    var original = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Đang tạo...';

    html2canvas(voucher, { scale: 2, useCORS: true, backgroundColor: '#ffffff' })
      .then(function (canvas) {
        canvas.toBlob(function (blob) {
          var url = URL.createObjectURL(blob);
          var a = document.createElement('a');
          a.href = url;
          a.download = 'e-ticket-' + (btn.getAttribute('data-order') || 'voucher') + '.png';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          URL.revokeObjectURL(url);
          btn.disabled = false;
          btn.textContent = original;
        }, 'image/png');
      })
      .catch(function () {
        btn.disabled = false;
        btn.textContent = original;
        alert('Không tạo được e-ticket, vui lòng thử lại.');
      });
  });
})();
```

- [ ] **Step 3: Enqueue trong `CommonHook.php`** (thêm block trong hàm `wp_enqueue_scripts`, cạnh các block `is_singular(...)`; trước dấu `}, 10);` đóng callback)

```php
   if (function_exists('is_order_received_page') && is_order_received_page()) {
      wp_enqueue_script('html2canvas', MONA_THEME_PATH_URI . '/assets/library/html2canvas/html2canvas.min.js', array(), '1.4.1', array('in_footer' => true));
      wp_enqueue_script('tsh-eticket', MONA_THEME_PATH_URI . '/assets/scripts/eticket.js', array('html2canvas'), filemtime(MONA_THEME_PATH . '/assets/scripts/eticket.js'), array('in_footer' => true));
   }
```

- [ ] **Step 4: Kiểm tra syntax** (đọc lại eticket.js + block PHP enqueue; php -l bỏ)

- [ ] **Step 5: Kiểm thử thủ công (sau reset OPcache)**

Trang cảm ơn đơn có e-ticket → bấm nút → nút đổi "Đang tạo..." rồi tự tải file `e-ticket-00xxx.png`. Mở ảnh: 2 cột (nội dung + banner), đúng mã/dịch vụ/số người/ngày hết hạn/2 hotline; ảnh nét (scale 2). Lặp cho khóa học & workshop.

- [ ] **Step 6: Commit**

```bash
git add wp-content/themes/thesoundhealing/assets/library/html2canvas/html2canvas.min.js wp-content/themes/thesoundhealing/assets/scripts/eticket.js wp-content/themes/thesoundhealing/inc/hooks/CommonHook.php
git commit -m "feat(eticket): html2canvas + JS tải ảnh voucher + enqueue trang cảm ơn"
```

---

## Self-Review

**Spec coverage:**
- Field `eticket_days` per-service (số ngày) → Task 1. ✔
- Tính hạn khi tạo đơn (order date + days) → Task 2. ✔
- Voucher gọn (mã/dịch vụ/số người/hết hạn/2 hotline), layout 2 cột giống card + banner → Task 3. ✔
- Ẩn nút khi không có e-ticket → Task 2 (không lưu meta) + Task 3 (`if ($eticket_expiry)`). ✔
- html2canvas tải PNG, nút "Đang tạo..." → Task 4. ✔
- Không hiện người mua/giờ/chi nhánh/thanh toán trên voucher → Task 3 (khối voucher chỉ chứa 5 dòng đã định). ✔
- Tra product → CPT bằng query `_wc_product_id` → Task 2. ✔

**Placeholder scan:** Không có TBD/TODO; mọi step có code/lệnh cụ thể. Step tải html2canvas có fallback rõ ràng nếu curl chặn. ✔

**Type consistency:** meta `_tsh_eticket_expiry`/`_tsh_eticket_days` nhất quán Task 2 (ghi) ↔ Task 3 (đọc); `#tsh-eticket-btn` + `.tsh-voucher` nhất quán Task 3 (render) ↔ Task 4 (JS); `eticket_days` nhất quán Task 1 ↔ Task 2. ✔

**Rủi ro verify lần đầu (sau reset OPcache):**
1. `get_field('eticket_days', $cpt_id)` chạy đúng ở ngữ cảnh checkout (ACF đã load ở frontend). 
2. html2canvas render ảnh banner cùng domain (không CORS) + font Roboto đúng.
3. `current_time('timestamp')` làm mốc tính hạn (xấp xỉ ngày tạo đơn — đủ dùng).
