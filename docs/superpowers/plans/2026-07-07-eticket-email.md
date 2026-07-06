# Gửi e-ticket qua email (bỏ tải ảnh) — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Bỏ phương án tải ảnh e-ticket (html2canvas) + debug tạm; khi đơn được xác nhận thanh toán (processing/completed) tự gửi 1 email riêng chứa voucher e-ticket dạng HTML.

**Architecture:** Một hàm global `tsh_eticket_expiry($order)` tính hạn e-ticket on-demand từ item của đơn (robust, không phụ thuộc save lúc tạo đơn). Dùng chung cho trang cảm ơn, dòng admin, và email. Email e-ticket riêng gửi trên hook đổi trạng thái processing/completed, gửi một lần.

**Tech Stack:** WordPress, WooCommerce, wp_mail, PHP. Không test framework, không PHP CLI.

## Global Constraints

- Theme `wp-content/themes/thesoundhealing/`. Hằng số `MONA_THEME_PATH_URI`, `MONA_THEME_PATH`, `MONA_THEME_INC_PATH`. Text domain `'monamedia'`.
- Order meta: `_tsh_eticket_expiry` (Y-m-d), `_tsh_eticket_days` (int), `_tsh_eticket_email_sent` ('1').
- Hàm helper: `tsh_eticket_expiry(\WC_Order $order, bool $persist = true): string` — trả expiry (Y-m-d) hoặc '' nếu dịch vụ không có `eticket_days`. `$persist=false` → chỉ tính, không `$order->save()` (dùng cho render admin).
- Tra dịch vụ từ product: query CPT `get_posts(['post_type'=>['khoa_hoc','workshop','dich_vu'],'post_status'=>'any','meta_key'=>'_wc_product_id','meta_value'=>$product_id,'posts_per_page'=>1,'fields'=>'ids'])`.
- Tính ngày local: `$order->get_date_created()->getTimestamp() + round(gmt_offset*3600)`, cộng `days*DAY_IN_SECONDS`, `gmdate('Y-m-d', ...)`.
- Hotline voucher: English `0939 624 684`, Tiếng Việt `0906 502 582`.
- Email: tiêu đề "E-ticket quà tặng của bạn — #{mã đơn 5 số}", `Content-Type: text/html; charset=UTF-8`, gửi qua `wp_mail`. Gửi **một lần** (`_tsh_eticket_email_sent`).
- Gửi khi đơn → `processing` hoặc `completed`, chỉ khi đơn có e-ticket.
- Bỏ: nút `#tsh-eticket-btn`, khối `.tsh-voucher` ẩn, enqueue html2canvas + `tsh-eticket`, file `eticket.js`, CSS `.tsh-voucher`, mọi debug (`_tsh_eticket_debug`, hộp vàng, `$eticket_dbg2`).
- Trên trang cảm ơn (khi đơn có e-ticket): thay nút bằng dòng "E-ticket quà tặng sẽ được gửi qua email sau khi thanh toán được xác nhận."
- Repo KHÔNG có test PHP và KHÔNG có PHP CLI → bỏ `php -l`; verify đọc code + user test **sau restart PHP** (LiteSpeed per-worker OPcache). Deploy `public_html/...`.

---

### Task 1: Hàm helper `tsh_eticket_expiry` + dọn debug ở WooCommerceHook

**Files:**
- Create: `wp-content/themes/thesoundhealing/inc/functions/EticketFunction.php`
- Modify: `wp-content/themes/thesoundhealing/configs/loadFile.php` (đăng ký)
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php` (dọn debug `save_eticket_meta`, `display_eticket_admin` dùng helper)

**Interfaces:**
- Produces: `tsh_eticket_expiry(\WC_Order $order, bool $persist = true): string`.

- [ ] **Step 1: Tạo `inc/functions/EticketFunction.php`**

```php
<?php

defined('ABSPATH') || exit;

/**
 * Trả ngày hết hạn e-ticket (Y-m-d) cho đơn, tính on-demand nếu chưa lưu.
 * Trả '' nếu dịch vụ không phát hành e-ticket (eticket_days <= 0 / không tìm thấy).
 * $persist=false: chỉ tính, không lưu (dùng khi render admin để tránh save mid-render).
 */
function tsh_eticket_expiry(\WC_Order $order, bool $persist = true): string
{
    $existing = $order->get_meta('_tsh_eticket_expiry');
    if ($existing) return (string) $existing;

    $items = $order->get_items();
    $first = $items ? reset($items) : null;
    $pid   = $first ? (int) $first->get_product_id() : 0;
    if (!$pid) return '';

    $cpt = get_posts([
        'post_type'      => ['khoa_hoc', 'workshop', 'dich_vu'],
        'post_status'    => 'any',
        'meta_key'       => '_wc_product_id',
        'meta_value'     => $pid,
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ]);
    if (empty($cpt)) return '';

    $days = (int) get_post_meta($cpt[0], 'eticket_days', true);
    if ($days <= 0) return '';

    $created = $order->get_date_created();
    $base    = $created
        ? ($created->getTimestamp() + (int) round(((float) get_option('gmt_offset', 0)) * 3600))
        : current_time('timestamp');
    $expiry = gmdate('Y-m-d', $base + $days * DAY_IN_SECONDS);

    if ($persist) {
        $order->update_meta_data('_tsh_eticket_days', $days);
        $order->update_meta_data('_tsh_eticket_expiry', $expiry);
        $order->save();
    }
    return $expiry;
}
```

- [ ] **Step 2: Đăng ký trong loadFile.php** (thêm cạnh nhóm functions, ví dụ sau dòng `.../functions/CommonFunction.php,`)

```php
    MONA_THEME_INC_PATH . '/functions/EticketFunction.php',
```

- [ ] **Step 3: Dọn debug trong `save_eticket_meta`** — bỏ mọi `_tsh_eticket_debug`

Thay toàn bộ thân method `save_eticket_meta` bằng:
```php
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
            'post_status'    => 'any',
            'meta_key'       => '_wc_product_id',
            'meta_value'     => $product_id,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);
        if (empty($cpt)) return;

        $days = (int) get_post_meta($cpt[0], 'eticket_days', true);
        if ($days <= 0) return;

        $expiry = gmdate('Y-m-d', current_time('timestamp') + $days * DAY_IN_SECONDS);
        $order->update_meta_data('_tsh_eticket_days', $days);
        $order->update_meta_data('_tsh_eticket_expiry', $expiry);
    }
```

- [ ] **Step 4: `display_eticket_admin` dùng helper, bỏ debug**

Thay toàn bộ method `display_eticket_admin` bằng:
```php
    public function display_eticket_admin(\WC_Order $order): void
    {
        $expiry = tsh_eticket_expiry($order, false);
        if ($expiry) {
            $days = (int) $order->get_meta('_tsh_eticket_days');
            echo '<div class="tsh-eticket-admin" style="margin-top:12px;padding:10px 12px;background:#eef6ff;border-left:4px solid #2271b1"><strong>E-ticket quà tặng:</strong> hết hạn <strong>' . esc_html(date_i18n('d/m/Y', strtotime($expiry))) . '</strong>' . ($days ? ' (' . (int) $days . ' ngày)' : '') . '</div>';
        } else {
            echo '<div class="tsh-eticket-admin" style="margin-top:12px;padding:10px 12px;background:#f6f7f7;border-left:4px solid #c3c4c7;color:#777"><strong>E-ticket quà tặng:</strong> không phát hành (dịch vụ chưa set số ngày).</div>';
        }
    }
```

- [ ] **Step 5: Kiểm tra + Commit** (php -l bỏ — đọc lại brace/paren; grep `_tsh_eticket_debug` = 0)

```bash
git add wp-content/themes/thesoundhealing/inc/functions/EticketFunction.php wp-content/themes/thesoundhealing/configs/loadFile.php wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "refactor(eticket): helper tsh_eticket_expiry + bỏ debug"
```

---

### Task 2: Email e-ticket riêng

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

**Interfaces:**
- Consumes: `tsh_eticket_expiry($order)` (Task 1).
- Produces: `send_eticket_email(int $order_id, $order = null): void` — hook `woocommerce_order_status_processing` + `woocommerce_order_status_completed`.

- [ ] **Step 1: Thêm method** (đặt gần `save_eticket_meta`)

```php
    /**
     * Gửi email e-ticket riêng cho khách khi đơn được xác nhận thanh toán
     * (processing/completed). Chỉ gửi khi đơn có e-ticket và chưa gửi lần nào.
     */
    public function send_eticket_email(int $order_id, $order = null): void
    {
        if (!$order) $order = wc_get_order($order_id);
        if (!$order) return;
        if ($order->get_meta('_tsh_eticket_email_sent')) return;

        $expiry = tsh_eticket_expiry($order);
        if (!$expiry) return;

        $code       = '#' . str_pad($order_id, 5, '0', STR_PAD_LEFT);
        $expiry_fmt = date_i18n('d/m/Y', strtotime($expiry));
        $guests     = $order->get_meta('_booking_guests') ?: '1';
        $items      = $order->get_items();
        $service    = $items ? reset($items)->get_name() : '';
        $email      = $order->get_billing_email();
        if (!$email) return;

        $html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;color:#1b1c19">'
            . '<div style="border:1px solid #e4e2dd;border-radius:12px;overflow:hidden">'
            . '<div style="background:#1b1c19;color:#c2a056;padding:18px 24px;font-weight:bold;letter-spacing:1px">THE SOUND HEALING — E-TICKET QUÀ TẶNG</div>'
            . '<div style="padding:24px">'
            . '<div style="padding:14px 18px;background:#faf8f4;border:1px dashed #c2a056;border-radius:10px;margin-bottom:18px">'
            . '<span style="color:#717171;font-size:13px">Mã e-ticket</span><br><strong style="color:#c2a056;font-size:22px;letter-spacing:1px">' . esc_html($code) . '</strong></div>'
            . '<table style="width:100%;border-collapse:collapse;font-size:14px">'
            . '<tr><td style="padding:8px 0;color:#717171">Dịch vụ</td><td style="padding:8px 0;text-align:right;font-weight:600">' . esc_html($service) . '</td></tr>'
            . '<tr><td style="padding:8px 0;color:#717171">Số người</td><td style="padding:8px 0;text-align:right;font-weight:600">' . esc_html($guests) . ' người</td></tr>'
            . '<tr><td style="padding:8px 0;color:#717171">Ngày hết hạn</td><td style="padding:8px 0;text-align:right;font-weight:700;color:#c2a056">' . esc_html($expiry_fmt) . '</td></tr>'
            . '</table>'
            . '<div style="margin-top:18px;padding:14px 16px;background:#fbf8f0;border-radius:10px;font-size:14px">'
            . '<p style="margin:0 0 6px;color:#717171;font-size:12px">Liên hệ hotline để đặt lịch:</p>'
            . '<p style="margin:0"><strong>English:</strong> 0939 624 684 &nbsp;|&nbsp; <strong>Tiếng Việt:</strong> 0906 502 582</p></div>'
            . '<p style="margin:14px 0 0;font-size:12px;color:#8a8577;font-style:italic">Vui lòng xuất trình mã e-ticket khi sử dụng dịch vụ.</p>'
            . '</div></div></div>';

        $subject = 'E-ticket quà tặng của bạn — ' . $code;
        $headers = ['Content-Type: text/html; charset=UTF-8'];

        wp_mail($email, $subject, $html, $headers);

        $order->update_meta_data('_tsh_eticket_email_sent', '1');
        $order->save();
    }
```

- [ ] **Step 2: Đăng ký 2 hook trong `__construct()`** (thêm cạnh nhóm hook đơn/email)

```php
        add_action('woocommerce_order_status_processing', [$this, 'send_eticket_email'], 20, 2);
        add_action('woocommerce_order_status_completed',  [$this, 'send_eticket_email'], 20, 2);
```

- [ ] **Step 3: Kiểm tra + Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "feat(eticket): gửi email e-ticket riêng khi đơn processing/completed"
```

---

### Task 3: Dọn trang cảm ơn + enqueue + eticket.js + CSS

**Files:**
- Modify: `wp-content/themes/thesoundhealing/woocommerce/checkout/thankyou.php`
- Modify: `wp-content/themes/thesoundhealing/inc/hooks/CommonHook.php`
- Delete: `wp-content/themes/thesoundhealing/assets/scripts/eticket.js`
- Modify: `wp-content/themes/thesoundhealing/assets/css/style.css` (bỏ `.tsh-voucher`)

**Interfaces:**
- Consumes: `tsh_eticket_expiry($order)` (Task 1).

- [ ] **Step 1: thankyou.php — thay khối var + fallback + bỏ hộp debug**

Thay đoạn từ `$eticket_expiry = $order->get_meta('_tsh_eticket_expiry');` đến hết block fallback (kết thúc trước `?>` đóng khối khai báo biến — tức toàn bộ đoạn tính `$eticket_dbg2`) bằng đúng một dòng:
```php
            $eticket_expiry = tsh_eticket_expiry($order);
```

Xoá hẳn khối hộp debug vàng:
```php
        <?php if (current_user_can('manage_options') || isset($_GET['ticketdebug'])) : ?>
        <div style="margin:0 0 16px;padding:12px 14px;background:#fffbe6;...">
            ... DEBUG e-ticket ...
        </div>
        <?php endif; ?>
```

- [ ] **Step 2: thankyou.php — thay nút tải bằng dòng thông báo**

Trong `.tsh-ty-actions`, thay:
```php
            <?php if ($eticket_expiry) : ?>
            <button type="button" id="tsh-eticket-btn" class="tsh-ty-btn tsh-ty-btn--ghost" data-order="<?php echo esc_attr(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?>"><?php esc_html_e('E-TICKET LÀM QUÀ TẶNG', 'monamedia'); ?></button>
            <?php endif; ?>
```
bằng: (bỏ nút, không thêm gì ở đây — dòng thông báo đặt ở Step 3)

- [ ] **Step 3: thankyou.php — thêm dòng thông báo (chỉ khi có e-ticket)**

Ngay trước `</div><!-- /.tsh-ty-card -->` (cuối card), thêm:
```php
            <?php if ($eticket_expiry) : ?>
            <p class="tsh-ty-eticket-note" style="margin:16px 0 0;padding:12px 14px;background:#faf8f4;border:1px solid #e4e2dd;border-radius:10px;font-size:13px;color:#717171">
                <?php esc_html_e('E-ticket quà tặng sẽ được gửi qua email sau khi thanh toán được xác nhận.', 'monamedia'); ?>
            </p>
            <?php endif; ?>
```

- [ ] **Step 4: thankyou.php — xoá khối `.tsh-voucher` ẩn**

Xoá toàn bộ khối:
```php
        <?php if ($eticket_expiry) : ?>
        <!-- Voucher e-ticket (ẩn ngoài màn hình...) -->
        <div class="tsh-voucher" aria-hidden="true"> ... </div>
        <?php endif; ?>
```
(nằm gần cuối template, trước `</div><!-- /.tsh-ty-body -->`).

- [ ] **Step 5: CommonHook.php — bỏ enqueue html2canvas + eticket.js**

Xoá toàn bộ block:
```php
   if (is_order_received_page()) {
      global $wp;
      $tsh_oid   = absint($wp->query_vars['order-received'] ?? 0);
      $tsh_order = $tsh_oid ? wc_get_order($tsh_oid) : null;
      if ($tsh_order && $tsh_order->get_meta('_tsh_eticket_expiry')) {
         wp_enqueue_script('html2canvas', ...);
         wp_enqueue_script('tsh-eticket', ...);
      }
   }
```

- [ ] **Step 6: Xoá file eticket.js + bỏ CSS `.tsh-voucher`**

```bash
git rm wp-content/themes/thesoundhealing/assets/scripts/eticket.js
```
Trong `assets/css/style.css`: xoá khối bắt đầu từ comment `/* ── E-ticket voucher (html2canvas...` đến hết các rule `.tsh-voucher*` (đến trước rule kế tiếp không thuộc `.tsh-voucher`).

- [ ] **Step 7: Kiểm tra + Commit** (đọc lại if/endif, tag balanced; grep `tsh-voucher`, `tsh-eticket-btn`, `html2canvas`, `ticketdebug` trong thankyou.php + CommonHook = 0)

```bash
git add wp-content/themes/thesoundhealing/woocommerce/checkout/thankyou.php wp-content/themes/thesoundhealing/inc/hooks/CommonHook.php wp-content/themes/thesoundhealing/assets/css/style.css
git commit -m "chore(eticket): bỏ tải ảnh + debug trên trang cảm ơn, thêm dòng thông báo email"
```

---

## Self-Review

**Spec coverage:**
- Helper on-demand `tsh_eticket_expiry` → Task 1. ✔
- Bỏ debug (save_eticket_meta, admin, thankyou) → Task 1 + Task 3. ✔
- Email e-ticket HTML riêng khi processing/completed, gửi 1 lần → Task 2. ✔
- Bỏ tải (nút, voucher ẩn, enqueue, eticket.js, CSS) → Task 3. ✔
- Dòng thông báo trên trang cảm ơn → Task 3. ✔
- Email không kèm banner → Task 2 (HTML không có img banner). ✔

**Placeholder scan:** Không có TBD/TODO; các removal chỉ rõ khối cần xoá bằng marker cụ thể. ✔

**Type consistency:** `tsh_eticket_expiry(\WC_Order,bool):string` nhất quán Task 1 (định nghĩa) ↔ Task 2/3 (dùng); meta `_tsh_eticket_expiry`/`_tsh_eticket_days`/`_tsh_eticket_email_sent` nhất quán. ✔

**Rủi ro verify (sau restart PHP):**
1. Email gửi đúng khi đơn → processing (SePay webhook `payment_complete` cũng đặt processing → hook fire).
2. `tsh_eticket_expiry` với `$persist=false` ở admin không gây save mid-render.
3. Không gửi email trùng khi status đổi qua lại (guard `_tsh_eticket_email_sent`).
