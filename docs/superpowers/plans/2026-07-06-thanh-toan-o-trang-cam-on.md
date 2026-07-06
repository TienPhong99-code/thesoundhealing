# Chuyển thanh toán sang trang cảm ơn — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Checkout chỉ chọn cọc 50%/100% + phương thức (không QR); bấm "Đặt lịch ngay" tạo đơn pending rồi trả tiền (QR/PayPal) ở trang cảm ơn; SePay tự swap tại chỗ khi webhook xác nhận.

**Architecture:** Dùng luồng WooCommerce chuẩn (order pending → pay trên order-received). Bỏ hack nút-giả/polling trên checkout và ajax `tsh_set_payment_type`; cọc điều khiển bằng field form `tsh_paytype` đọc qua `woocommerce_checkout_update_order_review` + `woocommerce_checkout_process`. Trang cảm ơn dựng QR theo `TSH{order_id}` (webhook đã hỗ trợ), polling `tsh_order_status` swap tại chỗ.

**Tech Stack:** WordPress, WooCommerce, jQuery, plugin SePay gateway. Không test framework, không PHP CLI.

## Global Constraints

- Toàn bộ code trong class `TSH_WooCommerce_Hook` tại `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`. Không thêm file PHP mới.
- Text domain `'monamedia'`.
- Session key cọc: `tsh_payment_type` = `'full'` (mặc định) | `'deposit'`. Field form: `input[name="tsh_paytype"]` value `full`/`deposit` (đã có ở `payment_type_options`).
- QR trang cảm ơn: nội dung CK = `'TSH' . $order_id`; số tiền = `(int) round($order->get_total())`. Webhook `SepayWebhook.php` khớp `TSH{order_id}` (Case 1) → `payment_complete()` — GIỮ NGUYÊN.
- Phương thức: `sepay` (QR + auto), `tsh_paypal_qr` (info + nút "Tôi đã thanh toán"), `tsh_cash` (message). Cổng `tsh_sepay_credit` vẫn disable "Sắp ra mắt".
- Email: KHÔNG gửi cho khách khi pending/on-hold; chỉ gửi khi `payment_complete()` (processing).
- Đơn pending chưa trả: để nguyên, không auto-cancel.
- Repo KHÔNG có test PHP và KHÔNG có PHP CLI → bỏ `php -l`; verify bằng đọc code + user test trên WordPress live **sau khi reset OPcache**. Xem `.superpowers/sdd` / memory `deploy-ftp-opcache`.
- Deploy đúng path `public_html/wp-content/themes/thesoundhealing/...` qua FTP (FTP root = home, KHÔNG phải docroot).
- Giả định (đã đúng ở luồng cũ, xác nhận ở lần test đầu): gateway `sepay` `process_payment()` tạo đơn (pending/on-hold) và redirect tới trang order-received.

---

### Task 1: Cọc điều khiển bằng field form (bỏ ajax tsh_set_payment_type)

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

**Interfaces:**
- Consumes: session `tsh_payment_type`; `get_payment_type()`, `apply_deposit_fee()` (đã có).
- Produces:
  - `sync_payment_type_from_post(string $posted_data): void` — hook `woocommerce_checkout_update_order_review`.
  - `sync_payment_type_on_process(): void` — hook `woocommerce_checkout_process`.
  - `payment_type_js()` (viết lại) — chỉ trigger `update_checkout` khi đổi radio.

- [ ] **Step 1: Thêm 2 method sync + set session từ POST** (đặt ngay sau `get_payment_type()`)

```php
    /**
     * Đọc lựa chọn cọc từ post_data của update_order_review (field tsh_paytype
     * trong form checkout) → set session. Thay cho ajax tsh_set_payment_type.
     */
    public function sync_payment_type_from_post(string $posted_data): void
    {
        parse_str($posted_data, $arr);
        $type = ($arr['tsh_paytype'] ?? '') === 'deposit' ? 'deposit' : 'full';
        if (WC()->session) {
            WC()->session->set('tsh_payment_type', $type);
        }
    }

    /**
     * Lúc submit đặt lịch: chốt lại session theo field tsh_paytype đã post,
     * để apply_deposit_fee tính đúng khi tạo đơn.
     */
    public function sync_payment_type_on_process(): void
    {
        $type = ($_POST['tsh_paytype'] ?? '') === 'deposit' ? 'deposit' : 'full';
        if (WC()->session) {
            WC()->session->set('tsh_payment_type', $type);
        }
    }
```

- [ ] **Step 2: Đăng ký 2 hook, GỠ đăng ký ajax cũ** trong `__construct()`

Xoá 2 dòng:
```php
        add_action('wp_ajax_nopriv_tsh_set_payment_type', [$this, 'ajax_set_payment_type']);
        add_action('wp_ajax_tsh_set_payment_type',        [$this, 'ajax_set_payment_type']);
```
Thêm (đặt gần nhóm hook checkout, ví dụ ngay sau `add_action('woocommerce_cart_calculate_fees', [$this, 'apply_deposit_fee']);`):
```php
        add_action('woocommerce_checkout_update_order_review', [$this, 'sync_payment_type_from_post']);
        add_action('woocommerce_checkout_process',             [$this, 'sync_payment_type_on_process']);
```

- [ ] **Step 3: Xoá method `ajax_set_payment_type()`** (toàn bộ method, quanh dòng 1073 hiện tại)

- [ ] **Step 4: Viết lại `payment_type_js()`** — bỏ ajax, chỉ trigger update_checkout

```php
    public function payment_type_js(): void
    {
        if (!is_checkout() || is_order_received_page()) return;
        ?>
        <script>
            jQuery(function($) {
                // Đổi radio cọc/full → WooCommerce tính lại tổng (fee đọc từ post_data).
                $(document.body).on('change', 'input[name="tsh_paytype"]', function() {
                    $('.tsh-paytype__opt').removeClass('is-active');
                    $(this).closest('.tsh-paytype__opt').addClass('is-active');
                    $(document.body).trigger('update_checkout');
                });
            });
        </script>
        <?php
    }
```

- [ ] **Step 5: Bỏ `data-nonce` + nonce khỏi `payment_type_options()`** (không còn dùng nonce ajax)

Trong `payment_type_options()`: xoá dòng `$nonce = wp_create_nonce('tsh_payment_type');` và đổi
`<div class="tsh-paytype" data-nonce="<?= esc_attr($nonce) ?>">` thành `<div class="tsh-paytype">`.

- [ ] **Step 6: Kiểm tra + Commit** (php -l bị bỏ — không có CLI; đọc lại brace/logic)

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "refactor(checkout): điều khiển cọc bằng field form thay cho ajax tsh_set_payment_type"
```

---

### Task 2: Bỏ QR + nút giả + polling trên checkout

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

**Interfaces:**
- Produces: `checkout_bacs_js()` (viết lại gọn) — giữ UX chọn cổng, bỏ fake button/polling/QR.
- `add_bacs_qr_checkout()` — bỏ nhánh `sepay` (không QR trên checkout).

- [ ] **Step 1: Bỏ nhánh `sepay` trong `add_bacs_qr_checkout()`**

Xoá toàn bộ khối `if ($payment_id === 'sepay') { ... }` (dòng ~500-548). Giữ nhánh `bacs` (dead-safe) và `return $description;`.

- [ ] **Step 2: Viết lại `checkout_bacs_js()`** — bỏ fake button, sepay polling, QR update; giữ disable-credit + deselect-on-load + show/hide payment box

```php
    public function checkout_bacs_js(): void
    {
        if (!is_checkout() || is_order_received_page()) return;
        ?>
        <script>
            jQuery(function($) {
                function showPaymentBox(method) {
                    $('.payment_box').hide();
                    $('.payment_box.payment_method_' + method).show();
                }

                // Master/Credit Card — placeholder chờ BCT: disable radio
                function disableMasterCard() {
                    var $mc = $('#payment_method_tsh_sepay_credit');
                    if (!$mc.length) return;
                    $mc.prop('disabled', true).closest('li').addClass('tsh-pm-disabled');
                    var $label = $mc.closest('li').find('label[for="payment_method_tsh_sepay_credit"]');
                    if ($label.length && !$label.find('.tsh-soon-badge').length) {
                        var $badge = $('<span class="tsh-soon-badge"><?= esc_js(__('Sắp ra mắt', 'monamedia')) ?></span>');
                        var $icon = $label.find('.tsh-pay-ic');
                        $icon.length ? $badge.insertBefore($icon) : $label.append(' ', $badge);
                    }
                    if ($mc.is(':checked')) $mc.prop('checked', false);
                }

                // Không active cổng nào khi mới vào — khách phải tự click chọn
                var userPicked = false;
                $(document.body).on('click change', 'input[name="payment_method"]:not(:disabled)', function() {
                    userPicked = true;
                });

                function refreshPayments() {
                    disableMasterCard();
                    if (!userPicked || !$('input[name="payment_method"]:checked').length) {
                        $('input[name="payment_method"]').prop('checked', false);
                        $('.payment_box').hide();
                        return;
                    }
                    showPaymentBox($('input[name="payment_method"]:checked').val());
                }

                refreshPayments();
                $(document.body).on('payment_method_selected updated_checkout', refreshPayments);
            });
        </script>
        <?php
    }
```

- [ ] **Step 3: Kiểm tra + Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "refactor(checkout): bỏ QR/nút giả/polling — dùng nút Đặt lịch thật của WooCommerce"
```

---

### Task 3: Trang cảm ơn — QR SePay (TSH{order_id}) + PayPal + Thanh toán khác

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

**Interfaces:**
- Consumes: webhook `TSH{order_id}` (SepayWebhook Case 1), `$order->get_total()`, `vietqr_url()`, `copy_btn()`, `deposit_notice_html()`.
- Produces: `render_thankyou_payment(int $order_id): void` — hook `woocommerce_thankyou` (tổng quát), branch theo `get_payment_method()`.

- [ ] **Step 1: Đổi hook trong `__construct()`**

Xoá: `add_action('woocommerce_thankyou_bacs', [$this, 'show_bacs_qr_thankyou']);`
Thêm: `add_action('woocommerce_thankyou', [$this, 'render_thankyou_payment'], 5);`

- [ ] **Step 2: Thay `show_bacs_qr_thankyou()` bằng `render_thankyou_payment()`**

```php
    public function render_thankyou_payment(int $order_id): void
    {
        $order = wc_get_order($order_id);
        if (!$order) return;
        $method = $order->get_payment_method();
        $paid   = in_array($order->get_status(), ['processing', 'completed'], true);
        $email  = $order->get_billing_email();

        // Đã thanh toán → success (mọi phương thức)
        if ($paid) {
            echo '<div class="tsh-bacs-qr tsh-bacs-qr--ty tsh-bacs-qr--success"><div class="tsh-payment-confirmed tsh-payment-confirmed--full"><span>✓</span><div><p>' . esc_html__('Thanh toán thành công!', 'monamedia') . '</p><p class="tsh-payment-confirmed__sub">' . esc_html__('Email xác nhận đã gửi đến', 'monamedia') . ' <strong>' . esc_html($email) . '</strong></p></div></div></div>';
            return;
        }

        // Thanh toán khác → chỉ báo thành công, NV liên hệ
        if ($method === 'tsh_cash') {
            echo '<div class="tsh-deposit-notice" style="margin:16px 0;padding:16px 18px;background:#fbf8f0;border:1px solid #c2a056;border-radius:12px;color:#1b1c19"><p style="margin:0;font-weight:600">' . esc_html__('Đặt lịch thành công!', 'monamedia') . '</p><p style="margin:6px 0 0">' . esc_html__('Nhân viên sẽ liên hệ với bạn để xác nhận thanh toán.', 'monamedia') . '</p></div>';
            return;
        }

        // SePay → QR TSH{order_id}, số tiền = tổng đơn
        if ($method === 'sepay') {
            $amount = (int) round((float) $order->get_total());
            $info   = 'TSH' . $order_id;
            $url    = $this->vietqr_url($amount, $info);
            ?>
            <div class="tsh-bacs-qr tsh-bacs-qr--ty" id="tsh-ty-sepay">
                <h3 class="tsh-bacs-qr__title"><?php esc_html_e('Quét mã để hoàn tất thanh toán', 'monamedia'); ?></h3>
                <img src="<?= esc_url($url) ?>" alt="QR SePay">
                <div class="tsh-bacs-qr__info">
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Ngân hàng', 'monamedia'); ?></span><strong><?= esc_html(TSH_BANK_ID) ?></strong></div>
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tài khoản', 'monamedia'); ?></span><span class="tsh-bacs-qr__val"><strong><?= esc_html(TSH_BANK_ACCOUNT) ?></strong><?= $this->copy_btn() ?></span></div>
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tiền', 'monamedia'); ?></span><strong><?= wc_price($amount) ?></strong></div>
                    <div class="tsh-bacs-qr__row tsh-bacs-qr__row--ref"><span><?php esc_html_e('Nội dung CK', 'monamedia'); ?></span><span class="tsh-bacs-qr__val"><strong><?= esc_html($info) ?></strong><?= $this->copy_btn() ?></span></div>
                </div>
                <div class="tsh-payment-waiting"><p><?php esc_html_e('Trang sẽ tự cập nhật sau khi nhận được thanh toán', 'monamedia'); ?></p></div>
            </div>
            <?php
            return;
        }

        // PayPal → info + nút "Tôi đã thanh toán"
        if ($method === 'tsh_paypal_qr') {
            ?>
            <div class="tsh-bacs-qr tsh-bacs-qr--ty">
                <h3 class="tsh-bacs-qr__title"><?php esc_html_e('Thanh toán qua PayPal', 'monamedia'); ?></h3>
                <div class="tsh-bacs-qr__info">
                    <div class="tsh-bacs-qr__row"><span><?php esc_html_e('Số tiền', 'monamedia'); ?></span><strong><?= wc_price((float) $order->get_total()) ?></strong></div>
                </div>
                <button type="button" id="tsh-confirm-transfer" data-order="<?= (int) $order_id ?>" data-key="<?= esc_attr($order->get_order_key()) ?>" class="tsh-confirm-btn"><?php esc_html_e('Tôi đã thanh toán', 'monamedia'); ?></button>
                <div id="tsh-transfer-msg" style="display:none" class="tsh-transfer-msg">
                    <p><?php esc_html_e('Cảm ơn bạn! Chúng tôi sẽ kiểm tra và xác nhận trong vòng 2 giờ.', 'monamedia'); ?></p>
                    <p><?php esc_html_e('Email xác nhận gửi đến:', 'monamedia'); ?> <strong><?= esc_html($email) ?></strong></p>
                </div>
            </div>
            <?php
            return;
        }
    }
```

Ghi chú: PayPal hiện QR/thông tin tài khoản có thể lấy từ `WC_Gateway_TSH_Paypal` như code cũ nếu cần; ở đây tối giản phần bắt buộc. Có thể bổ sung QR PayPal ở bước hoàn thiện.

- [ ] **Step 3: Kiểm tra + Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "feat(thankyou): hiện QR SePay(TSH{order_id})/PayPal/cash theo phương thức"
```

---

### Task 4: Polling trang cảm ơn — swap tại chỗ cho SePay (bỏ reload)

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

**Interfaces:**
- Consumes: `ajax_order_status` (SepayWebhook, trả `{status}`); markup `#tsh-ty-sepay` (Task 3); nút `#tsh-confirm-transfer` (PayPal).
- Produces: `thankyou_polling_js()` (viết lại) — swap `#tsh-ty-sepay` sang success khi `processing`.

- [ ] **Step 1: Viết lại `thankyou_polling_js()`**

```php
    public function thankyou_polling_js(): void
    {
        if (!is_order_received_page()) return;
        global $wp;
        $order_id = absint($wp->query_vars['order-received'] ?? 0);
        $order    = $order_id ? wc_get_order($order_id) : null;
        if (!$order || !in_array($order->get_payment_method(), ['sepay', 'tsh_paypal_qr'], true)) return;
        if (!in_array($order->get_status(), ['pending', 'on-hold'], true)) return;
        $email = $order->get_billing_email();
        ?>
        <script>
            jQuery(function($) {
                var ajaxUrl = '<?= esc_js(admin_url('admin-ajax.php')) ?>';
                var orderId = <?= (int) $order_id ?>;
                var orderKey = '<?= esc_js($order->get_order_key()) ?>';
                var successHtml = '<div class="tsh-bacs-qr tsh-bacs-qr--ty tsh-bacs-qr--success"><div class="tsh-payment-confirmed tsh-payment-confirmed--full"><span>✓</span><div><p><?= esc_js(__('Thanh toán thành công!', 'monamedia')) ?></p><p class="tsh-payment-confirmed__sub"><?= esc_js(__('Email xác nhận đã gửi đến', 'monamedia')) ?> <strong><?= esc_js($email) ?></strong></p></div></div></div>';
                var timer;

                // PayPal: nút "Tôi đã thanh toán"
                $('#tsh-confirm-transfer').on('click', function() {
                    var $btn = $(this);
                    $btn.prop('disabled', true).text('<?= esc_js(__('Đang gửi xác nhận...', 'monamedia')) ?>');
                    $.post(ajaxUrl, { action: 'tsh_confirm_transfer', order_id: orderId, order_key: orderKey })
                        .always(function() { $btn.hide(); $('#tsh-transfer-msg').show(); });
                });

                // SePay: polling → swap tại chỗ (không reload)
                function check() {
                    $.get(ajaxUrl, { action: 'tsh_order_status', order_id: orderId, order_key: orderKey })
                        .done(function(res) {
                            if (!res.success) return;
                            var s = res.data.status;
                            if (s === 'processing' || s === 'completed') {
                                clearInterval(timer);
                                $('#tsh-ty-sepay').replaceWith(successHtml);
                            }
                        });
                }
                if ($('#tsh-ty-sepay').length) timer = setInterval(check, 5000);
            });
        </script>
        <?php
    }
```

- [ ] **Step 2: Kiểm tra + Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "feat(thankyou): SePay xác nhận → swap tại chỗ (bỏ reload)"
```

---

### Task 5: Email chỉ khi đã thanh toán (chặn on-hold/pending cho khách)

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

**Interfaces:**
- Produces: `disable_customer_onhold_email(bool $enabled, $order): bool` — filter `woocommerce_email_enabled_customer_on_hold_order`.

- [ ] **Step 1: Thêm method** (đặt gần `prevent_duplicate_new_order_email`)

```php
    /**
     * Không gửi email on-hold cho KHÁCH (đơn chưa thanh toán). Email "cảm ơn"
     * chỉ gửi khi payment_complete() (customer processing email).
     */
    public function disable_customer_onhold_email(bool $enabled, $order): bool
    {
        return false;
    }
```

- [ ] **Step 2: Đăng ký filter trong `__construct()`**

```php
        add_filter('woocommerce_email_enabled_customer_on_hold_order', [$this, 'disable_customer_onhold_email'], 10, 2);
```

- [ ] **Step 3: Kiểm tra + Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "feat(email): không gửi email on-hold cho khách khi chưa thanh toán"
```

---

### Task 6: Dọn code chết (thận trọng)

**Files:**
- Modify: `wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php`

- [ ] **Step 1: Gỡ các mảnh của luồng checkout-QR cũ không còn tham chiếu**

Chỉ gỡ khi ĐÃ chắc không còn nơi gọi (grep trước):
- `ajax_sepay_paid()` + 2 hook `wp_ajax(_nopriv)_tsh_sepay_paid` — chỉ dùng bởi checkout polling (đã bỏ ở Task 2).
- `auto_complete_sepay()` + hook `woocommerce_order_status_on-hold` — luồng mới dùng webhook `payment_complete()` trực tiếp; giữ lại KHÔNG hại nhưng thừa. Gỡ nếu grep xác nhận `tsh_sepay_token` không còn set (đã bỏ khi xoá nhánh sepay checkout ở Task 2).

Lệnh kiểm tra trước khi gỡ:
```bash
grep -n "tsh_sepay_token\|tsh_sepay_paid\|TSHCK\|auto_complete_sepay" wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
```
Nếu chỉ còn định nghĩa (không còn nơi set/gọi) → gỡ. Nếu còn tham chiếu → GIỮ.

- [ ] **Step 2: (Tuỳ chọn) Gỡ nhánh `TSHCK` trong `SepayWebhook.php`** nếu không còn dùng token checkout. Nếu không chắc → giữ (vô hại).

- [ ] **Step 3: Kiểm tra + Commit**

```bash
git add wp-content/themes/thesoundhealing/inc/woocommerce/WooCommerceHook.php
git commit -m "chore: dọn code checkout-QR cũ không còn dùng"
```

---

## Self-Review

**Spec coverage:**
- Checkout chỉ cọc + phương thức, không QR → Task 1 (cọc field form) + Task 2 (bỏ QR/nút giả). ✔
- Tạo đơn pending → thankyou → hành vi WooCommerce mặc định sau Task 2 (nút place-order thật). ✔
- Thankyou QR SePay(TSH{order_id})/PayPal/cash → Task 3. ✔
- SePay tự swap tại chỗ → Task 4. ✔
- Email chỉ khi đã thanh toán → Task 5. ✔
- Đơn pending để nguyên → không auto-cancel (không task nào thêm cron). ✔
- Bỏ ajax tsh_set_payment_type → Task 1. ✔
- Dọn code cũ → Task 6. ✔

**Placeholder scan:** Task 3 Step 2 có ghi chú "có thể bổ sung QR PayPal" — đây là tối giản có chủ đích (PayPal chỉ cần info + nút xác nhận theo spec), không phải placeholder chặn. Các step khác có code đầy đủ.

**Type consistency:** `tsh_payment_type` (session), `tsh_paytype` (field form), `TSH{order_id}` (webhook), `#tsh-ty-sepay` (Task 3 render ↔ Task 4 swap), `tsh_order_status`/`tsh_confirm_transfer` (ajax sẵn có) — nhất quán giữa các task.

**Rủi ro cần verify ở lần test đầu (sau reset OPcache):**
1. Gateway `sepay` `process_payment()` tạo đơn + redirect thankyou đúng (giả định từ luồng cũ).
2. Trạng thái đơn sau submit (pending vs on-hold) — ảnh hưởng điều kiện hiện QR ở Task 3/4 (đang chấp nhận cả `pending` và `on-hold`).
3. Email: xác nhận không có email khách nào gửi khi chưa thanh toán (Task 5 chặn on-hold; pending vốn không gửi).
