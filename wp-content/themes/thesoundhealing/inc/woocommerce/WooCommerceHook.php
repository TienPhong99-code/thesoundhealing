<?php
defined('ABSPATH') || exit;

class TSH_WooCommerce_Hook
{

    public function __construct()
    {
        add_action('after_setup_theme',   [$this, 'declare_support']);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        add_action('init',                [$this, 'register_endpoint']);
        add_action('template_redirect',   [$this, 'handle_buy_now']);
        add_filter('woocommerce_checkout_get_value', [$this, 'prefill_checkout'], 10, 2);
        add_action('woocommerce_checkout_order_processed', [$this, 'save_booking_meta']);
        add_action('woocommerce_admin_order_data_after_billing_address', [$this, 'display_booking_meta']);
        add_filter('woocommerce_checkout_fields', [$this, 'simplify_checkout_fields']);
        add_filter('woocommerce_order_button_text', fn() => 'Đặt lịch ngay');
        add_filter('woocommerce_email_heading_customer_processing_order', fn() => 'Cảm ơn bạn đã đặt lịch hẹn');
        add_filter('woocommerce_email_heading_customer_on_hold_order',    fn() => 'Cảm ơn bạn đã đặt lịch hẹn');
        add_filter('woocommerce_email_subject_customer_processing_order', [$this, 'customer_email_subject'], 10, 2);
        add_filter('woocommerce_email_subject_customer_on_hold_order',    [$this, 'customer_email_subject'], 10, 2);
        add_filter('woocommerce_gateway_description',          [$this, 'add_bacs_qr_checkout'], 10, 2);
        add_action('woocommerce_thankyou_bacs',              [$this, 'show_bacs_qr_thankyou']);
        add_action('wp_footer',                              [$this, 'checkout_bacs_js']);
        add_action('wp_footer',                              [$this, 'thankyou_polling_js']);
        add_action('woocommerce_email_after_order_table',    [$this, 'email_bacs_ref'], 10, 4);
        add_action('wp_ajax_nopriv_tsh_confirm_transfer',    [$this, 'ajax_confirm_transfer']);
        add_action('wp_ajax_tsh_confirm_transfer',           [$this, 'ajax_confirm_transfer']);
        add_action('woocommerce_order_status_on-hold',        [$this, 'auto_complete_sepay'], 20, 2);
        add_action('wp_ajax_nopriv_tsh_sepay_paid',          [$this, 'ajax_sepay_paid']);
        add_action('wp_ajax_tsh_sepay_paid',                 [$this, 'ajax_sepay_paid']);
        add_filter('woocommerce_email_enabled_new_order',   [$this, 'prevent_duplicate_new_order_email'], 10, 3);
        add_filter('woocommerce_email_heading_new_order',   [$this, 'new_order_email_heading'], 10, 2);
        add_filter('woocommerce_email_subject_new_order',   [$this, 'new_order_email_subject'], 10, 2);
        add_filter('woocommerce_get_cart_item_from_session', [$this, 'restore_guests_cart_item'], 10, 2);
        add_action('woocommerce_before_calculate_totals',    [$this, 'apply_guests_price']);
        add_filter('woocommerce_get_item_data',              [$this, 'display_guests_in_cart'], 10, 2);
    }

    public function declare_support(): void
    {
        add_theme_support('woocommerce');
    }

    public function register_endpoint(): void
    {
        add_rewrite_rule('^mua-ngay/?$', 'index.php?tsh_buy_now=1', 'top');
        add_rewrite_tag('%tsh_buy_now%', '([^&]+)');
    }

    public function handle_buy_now(): void
    {
        if (!get_query_var('tsh_buy_now')) return;

        $product_id = (int) ($_GET['product_id'] ?? 0);
        $nonce      = sanitize_text_field($_GET['nonce'] ?? '');

        if (!$product_id || !wp_verify_nonce($nonce, 'tsh_buy_now')) {
            wp_die('Yêu cầu không hợp lệ.', '', ['response' => 400]);
        }

        $product = wc_get_product($product_id);
        if (!$product || !$product->is_purchasable()) {
            wp_die('Sản phẩm không tồn tại.', '', ['response' => 404]);
        }

        $booking = $this->get_booking();
        $guests  = max(1, (int) preg_replace('/\D/', '', $booking['guests'] ?? '1'));

        WC()->cart->empty_cart();
        $added = WC()->cart->add_to_cart($product_id, 1, 0, [], ['tsh_guests' => $guests]);
        if (!$added) {
            wp_die('Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.', '', ['response' => 400]);
        }
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }

    private function get_booking(): array
    {
        $token = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
        if (!$token) return [];
        return (array) get_transient('tsh_booking_' . $token);
    }

    public function prefill_checkout($value, string $input)
    {
        $booking = $this->get_booking();
        if (!$booking) return $value;

        $map = [
            'billing_first_name' => $booking['fullname'] ?? '',
            'billing_email'      => $booking['email']    ?? '',
            'billing_phone'      => $booking['phone']    ?? '',
        ];

        return $map[$input] ?? $value;
    }

    public function save_booking_meta(int $order_id): void
    {
        $token = sanitize_text_field($_COOKIE['tsh_booking_token'] ?? '');
        if (!$token) return;

        $booking = (array) get_transient('tsh_booking_' . $token);
        if (!$booking) return;

        $order = wc_get_order($order_id);
        if (!$order) return;

        $meta_map = [
            '_booking_date'       => 'date',
            '_booking_time'       => 'time',
            '_booking_location'   => 'location',
            '_booking_guests'     => 'guests',
            '_booking_instructor' => 'instructor',
            '_booking_children'   => 'children',
        ];

        foreach ($meta_map as $meta_key => $key) {
            if (!empty($booking[$key])) {
                $order->update_meta_data($meta_key, $booking[$key]);
            }
        }
        $order->save();

        delete_transient('tsh_booking_' . $token);
        setcookie('tsh_booking_token', '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
    }

    public function wrap_checkout_open(): void
    {
        echo '<div class="tsh-checkout-wrap"><div class="container">';
    }

    public function wrap_checkout_close(): void
    {
        echo '</div></div>';
    }

    public function simplify_checkout_fields(array $fields): array
    {
        $keep = ['billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone'];
        foreach (array_keys($fields['billing'] ?? []) as $key) {
            if (!in_array($key, $keep, true)) {
                unset($fields['billing'][$key]);
            }
        }
        // Last name is a hidden field auto-filled from full name — not required
        if (isset($fields['billing']['billing_last_name'])) {
            $fields['billing']['billing_last_name']['required'] = false;
        }
        unset($fields['shipping'], $fields['order']);
        return $fields;
    }

    // ── VietQR ────────────────────────────────────────────────────────────

    private function to_ascii(string $str): string
    {
        $map = [
            'á' => 'a',
            'à' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'ạ' => 'a',
            'ă' => 'a',
            'ắ' => 'a',
            'ằ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'ặ' => 'a',
            'â' => 'a',
            'ấ' => 'a',
            'ầ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ậ' => 'a',
            'é' => 'e',
            'è' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ẹ' => 'e',
            'ê' => 'e',
            'ế' => 'e',
            'ề' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ệ' => 'e',
            'í' => 'i',
            'ì' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ị' => 'i',
            'ó' => 'o',
            'ò' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ọ' => 'o',
            'ô' => 'o',
            'ố' => 'o',
            'ồ' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ộ' => 'o',
            'ơ' => 'o',
            'ớ' => 'o',
            'ờ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ợ' => 'o',
            'ú' => 'u',
            'ù' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ụ' => 'u',
            'ư' => 'u',
            'ứ' => 'u',
            'ừ' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ự' => 'u',
            'ý' => 'y',
            'ỳ' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'ỵ' => 'y',
            'đ' => 'd',
        ];
        foreach ($map as $from => $to) {
            $map[mb_strtoupper($from, 'UTF-8')] = strtoupper($to);
        }
        return strtoupper(strtr($str, $map));
    }

    private function vietqr_url(int $amount, string $add_info): string
    {
        return 'https://img.vietqr.io/image/' . TSH_BANK_ID . '-' . TSH_BANK_ACCOUNT . '-compact2.png?' . http_build_query([
            'amount'      => $amount,
            'addInfo'     => $add_info,
            'accountName' => TSH_BANK_NAME,
        ]);
    }

    public function add_bacs_qr_checkout(string $description, string $payment_id): string
    {
        if (is_admin()) return $description;

        // ── BACS ─────────────────────────────────────────────────────────
        if ($payment_id === 'bacs') {
            $cart       = WC()->cart;
            $total      = $cart ? (int) round((float) $cart->get_total('edit')) : 0;
            $name       = sanitize_text_field(WC()->customer ? WC()->customer->get_billing_first_name() : '');
            $first_item = $cart ? reset($cart->get_cart()) : null;
            $service    = $first_item ? $this->to_ascii($first_item['data']->get_name()) : '';
            $info       = 'DAT LICH' . ($service ? ' - ' . $service : '') . ($name ? ' - ' . $this->to_ascii($name) : ' - TSH');
            $base       = 'https://img.vietqr.io/image/' . TSH_BANK_ID . '-' . TSH_BANK_ACCOUNT . '-compact2.png?' . http_build_query([
                'accountName' => TSH_BANK_NAME,
            ]);
            $src = $base . '&amount=' . $total . '&addInfo=' . rawurlencode($info);

            ob_start(); ?>
            <div class="tsh-bacs-qr">
                <img src="<?= esc_url($src) ?>" data-base="<?= esc_url($base) ?>" data-service="<?= esc_attr($service) ?>" alt="QR chuyển khoản <?= esc_attr(TSH_BANK_ID) ?>">
                <p class="tsh-bacs-qr__note">Quét mã QR bằng app ngân hàng để thanh toán tự động</p>
            </div>
            <script>
                jQuery(function($) {
                    function toAscii(str) {
                        str = str.replace(/đ/g, 'd').replace(/Đ/g, 'D');
                        return str.normalize('NFD').replace(/[̀-ͯ]/g, '').toUpperCase();
                    }

                    function updateBacsQr() {
                        var $img = $('.tsh-bacs-qr:not(.tsh-sepay-checkout-qr) img');
                        if (!$img.length) return;
                        var amount = parseInt($('.order-total .amount').first().text().replace(/\D/g, '')) || 0;
                        var name = toAscii($('#billing_first_name').val() || '');
                        var service = $img.data('service') || '';
                        var addInfo = 'DAT LICH' + (service ? ' - ' + service : '') + (name ? ' - ' + name : ' - TSH');
                        $img.attr('src', $img.data('base') + '&amount=' + amount + '&addInfo=' + encodeURIComponent(addInfo));
                    }
                    $(document.body).on('updated_checkout', updateBacsQr);
                    $(document).on('input', '#billing_first_name', updateBacsQr);
                });
            </script>
        <?php
            return $description . ob_get_clean();
        }

        // ── SePay ─────────────────────────────────────────────────────────
        if ($payment_id === 'sepay') {
            $cart  = WC()->cart;
            $total = $cart ? (int) round((float) $cart->get_total('edit')) : 0;

            // Token duy nhất per session — để webhook xác định thanh toán
            $session = WC()->session;
            $token   = $session ? $session->get('tsh_sepay_token') : '';
            if (!$token) {
                $token = strtoupper(bin2hex(random_bytes(4))); // 8 ký tự hex
                if ($session) $session->set('tsh_sepay_token', $token);
            }

            $add_info = 'TSHCK' . $token;
            $base     = 'https://img.vietqr.io/image/' . TSH_BANK_ID . '-' . TSH_BANK_ACCOUNT . '-compact2.png?' . http_build_query([
                'accountName' => TSH_BANK_NAME,
            ]);
            $src = $base . '&amount=' . $total . '&addInfo=' . rawurlencode($add_info);

            ob_start(); ?>
            <div class="tsh-bacs-qr tsh-sepay-checkout-qr"
                data-token="<?= esc_attr($token) ?>"
                data-base="<?= esc_url($base) ?>"
                data-addinfo="<?= esc_attr($add_info) ?>">
                <img src="<?= esc_url($src) ?>" alt="QR SePay">
                <div class="tsh-payment-waiting">
                    <p>Quét mã QR để thanh toán<br><span>Trang sẽ tự chuyển sau khi xác nhận</span></p>
                </div>
            </div>
        <?php
            return $description . ob_get_clean();
        }

        return $description;
    }

    public function show_bacs_qr_thankyou(int $order_id): void
    {
        $order = wc_get_order($order_id);
        if (!$order) return;

        // Đơn đã thanh toán (SePay đã xác nhận) → hiện success state
        if (in_array($order->get_status(), ['processing', 'completed'], true)) {
        ?>
            <div class="tsh-bacs-qr tsh-bacs-qr--ty tsh-bacs-qr--success">
                <div class="tsh-payment-confirmed tsh-payment-confirmed--full">
                    <span>✓</span>
                    <div>
                        <p>Thanh toán thành công!</p>
                        <p class="tsh-payment-confirmed__sub">Email xác nhận đã gửi đến <strong><?= esc_html($order->get_billing_email()) ?></strong></p>
                    </div>
                </div>
            </div>
        <?php
            return;
        }

        // Chờ thanh toán → hiện QR
        $amount     = (int) round((float) $order->get_total());
        $first_item = reset($order->get_items());
        $service    = $first_item ? $this->to_ascii($first_item->get_name()) : '';
        $info       = 'DAT LICH' . ($service ? ' - ' . $service : '') . ' - TSH' . $order_id;
        $url        = $this->vietqr_url($amount, $info);
        ?>
        <div class="tsh-bacs-qr tsh-bacs-qr--ty">
            <h3 class="tsh-bacs-qr__title">Hoàn tất thanh toán</h3>
            <img src="<?= esc_url($url) ?>" alt="QR chuyển khoản ACB">
            <div class="tsh-bacs-qr__info">
                <div class="tsh-bacs-qr__row"><span>Ngân hàng</span><strong><?= esc_html(TSH_BANK_ID) ?></strong></div>
                <div class="tsh-bacs-qr__row"><span>Số tài khoản</span><strong><?= esc_html(TSH_BANK_ACCOUNT) ?></strong></div>
                <div class="tsh-bacs-qr__row"><span>Chủ tài khoản</span><strong><?= esc_html(TSH_BANK_NAME) ?></strong></div>
                <div class="tsh-bacs-qr__row"><span>Số tiền</span><strong><?= wc_price($amount) ?></strong></div>
                <div class="tsh-bacs-qr__row tsh-bacs-qr__row--ref"><span>Nội dung CK</span><strong>TSH <?= esc_html($order_id) ?></strong></div>
            </div>
            <button type="button" id="tsh-confirm-transfer"
                data-order="<?= (int) $order_id ?>"
                data-key="<?= esc_attr($order->get_order_key()) ?>"
                class="tsh-confirm-btn">
                Tôi đã chuyển khoản xong
            </button>
            <div id="tsh-transfer-msg" style="display:none" class="tsh-transfer-msg">
                <p>Cảm ơn bạn! Chúng tôi sẽ kiểm tra và xác nhận đặt lịch trong vòng <strong>2 giờ</strong>.</p>
                <p>Email xác nhận gửi đến: <strong><?= esc_html($order->get_billing_email()) ?></strong></p>
            </div>
        </div>
    <?php
    }

    // ── Booking meta ──────────────────────────────────────────────────────

    // ── Admin email: hiện nội dung CK nổi bật ────────────────────────────

    public function email_bacs_ref(\WC_Order $order, bool $sent_to_admin, bool $plain_text, \WC_Email $email): void
    {
        if (!$sent_to_admin || $order->get_payment_method() !== 'bacs') return;

        $ref         = 'TSH' . $order->get_id();
        $first_item  = reset($order->get_items());
        $service     = $first_item ? $this->to_ascii($first_item->get_name()) : '';
        $full_ref    = 'DAT LICH' . ($service ? ' - ' . $service : '') . ' - ' . $ref;

        echo '<div style="margin:16px 0;padding:14px 16px;background:#fff8e1;border-left:4px solid #c2a056;font-family:sans-serif">
            <p style="margin:0 0 6px;font-size:13px;color:#666">Nội dung chuyển khoản khách sẽ ghi:</p>
            <p style="margin:0;font-size:16px;font-weight:700;color:#1b1c19;letter-spacing:.5px">' . esc_html($full_ref) . '</p>
            <p style="margin:6px 0 0;font-size:13px;color:#666">Mã đơn: <strong>' . esc_html($ref) . '</strong> &nbsp;|&nbsp; Số tiền: <strong>' . wc_price($order->get_total()) . '</strong></p>
        </div>';
    }

    // ── AJAX: khách xác nhận đã chuyển khoản ─────────────────────────────

    // ── SePay checkout: polling token + auto-complete ─────────────────────

    public function ajax_sepay_paid(): void
    {
        $token = sanitize_text_field($_GET['token'] ?? '');
        if (!$token) {
            wp_send_json_error();
            return;
        }

        $data = get_transient('tsh_sepay_tk_' . $token);
        wp_send_json_success(['paid' => !empty($data['paid'])]);
    }

    public function auto_complete_sepay(int $order_id, $order = null): void
    {
        if (!$order) $order = wc_get_order($order_id);
        if (!$order || $order->get_payment_method() !== 'sepay') return;

        $session = WC()->session;
        $token   = $session ? $session->get('tsh_sepay_token') : '';
        if (!$token) return;

        $data = get_transient('tsh_sepay_tk_' . $token);
        if (!$data || empty($data['paid'])) return;

        // Hook fires khi SePay plugin đặt on-hold — ta override ngay sang processing
        $order->payment_complete();
        $order->add_order_note('SePay xác nhận thanh toán — tự động chuyển sang đang xử lý.');
        delete_transient('tsh_sepay_tk_' . $token);
        $session->__unset('tsh_sepay_token');
    }

    public function ajax_confirm_transfer(): void
    {
        $order_id = (int) ($_POST['order_id'] ?? 0);
        $key      = sanitize_text_field($_POST['order_key'] ?? '');

        $order = wc_get_order($order_id);
        if (!$order || $order->get_order_key() !== $key) {
            wp_send_json_error([], 403);
        }

        if (in_array($order->get_status(), ['pending', 'on-hold'], true)) {
            $order->add_order_note('Khách xác nhận đã chuyển khoản — chờ admin kiểm tra.');
        }

        wp_send_json_success(['email' => $order->get_billing_email()]);
    }

    // ── Checkout: ẩn Place Order khi chọn BACS ───────────────────────────

    public function checkout_bacs_js(): void
    {
        if (!is_checkout() || is_order_received_page()) return;
        $ajax_url = admin_url('admin-ajax.php');
    ?>
        <script>
            jQuery(function($) {
                var $orig = $('#place_order');
                var $fake = $('<button type="button" id="tsh-place-order">Xác nhận đặt lịch</button>');
                var ajaxUrl = '<?= esc_js($ajax_url) ?>';
                var sepayTimer;
                $orig.after($fake);
                $fake.on('click', function() {
                    $orig[0].click();
                });

                function stopSepayPoll() {
                    clearTimeout(sepayTimer);
                }

                function startSepayPoll() {
                    var $qr = $('.tsh-sepay-checkout-qr');
                    var token = $qr.data('token');
                    if (!token) return;

                    function poll() {
                        $.get(ajaxUrl, {
                                action: 'tsh_sepay_paid',
                                token: token
                            })
                            .done(function(res) {
                                if (res.success && res.data && res.data.paid) {
                                    history.replaceState(null, '', '/');
                                    $orig[0].click();
                                } else {
                                    sepayTimer = setTimeout(poll, 5000);
                                }
                            })
                            .fail(function() {
                                sepayTimer = setTimeout(poll, 5000);
                            });
                    }
                    poll();
                }

                // Cập nhật số tiền QR SePay khi cart thay đổi
                $(document.body).on('updated_checkout', function() {
                    var $qr = $('.tsh-sepay-checkout-qr');
                    if (!$qr.length) return;
                    var amount = parseInt($('.order-total .amount').first().text().replace(/\D/g, '')) || 0;
                    $qr.find('img').attr('src', $qr.data('base') + '&amount=' + amount + '&addInfo=' + encodeURIComponent($qr.data('addinfo')));
                });

                function toggle(method) {
                    stopSepayPoll();
                    if (method === 'bacs') {
                        $orig.hide();
                        $fake.show();
                    } else if (method === 'sepay') {
                        $orig.hide();
                        $fake.hide();
                        startSepayPoll();
                    } else {
                        $orig.show();
                        $fake.hide();
                    }
                }

                toggle($('input[name="payment_method"]:checked').val());
                $(document.body).on('payment_method_selected', function() {
                    toggle($('input[name="payment_method"]:checked').val());
                });
            });
        </script>
    <?php
    }

    // ── Thank you: polling tự động xác nhận SePay ────────────────────────

    public function thankyou_polling_js(): void
    {
        if (!is_order_received_page()) return;

        global $wp;
        $order_id = absint($wp->query_vars['order-received'] ?? 0);
        $order    = $order_id ? wc_get_order($order_id) : null;

        if (!$order || !in_array($order->get_payment_method(), ['bacs', 'sepay'], true)) return;
        if (!in_array($order->get_status(), ['pending', 'on-hold'], true)) return;
    ?>
        <script>
            jQuery(function($) {
                var ajaxUrl = '<?= esc_js(admin_url('admin-ajax.php')) ?>';
                var orderId = <?= (int) $order_id ?>;
                var orderKey = '<?= esc_js($order->get_order_key()) ?>';
                var timer;

                // Nút "Tôi đã chuyển khoản"
                $('#tsh-confirm-transfer').on('click', function() {
                    var $btn = $(this);
                    $btn.prop('disabled', true).text('Đang gửi xác nhận...');
                    $.post(ajaxUrl, {
                        action: 'tsh_confirm_transfer',
                        order_id: orderId,
                        order_key: orderKey
                    }).always(function() {
                        $btn.hide();
                        $('#tsh-transfer-msg').show();
                    });
                });

                // Polling — khi SePay xác nhận thì reload trang
                function check() {
                    $.get(ajaxUrl, {
                            action: 'tsh_order_status',
                            order_id: orderId,
                            order_key: orderKey
                        })
                        .done(function(res) {
                            if (!res.success) return;
                            var s = res.data.status;
                            if (s === 'processing' || s === 'completed') {
                                clearInterval(timer);
                                window.location.reload();
                            }
                        });
                }

                timer = setInterval(check, 5000);
            });
        </script>
<?php
    }

    // ── Admin new order email ─────────────────────────────────────────────

    public function prevent_duplicate_new_order_email(bool $enabled, $order, $email): bool {
        if (!$enabled || !$order instanceof \WC_Order) return $enabled;

        if ($order->get_meta('_tsh_new_order_email_sent')) {
            return false;
        }
        $order->update_meta_data('_tsh_new_order_email_sent', '1');
        $order->save_meta_data();
        return true;
    }

    private function order_booking_heading(\WC_Order $order): string {
        $items   = $order->get_items();
        $service = $items ? reset($items)->get_name() : '';
        $name    = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
        $date    = $order->get_meta('_booking_date');

        $parts = array_filter([$name ?: '', $service ?: '', $date ?: '']);
        return implode(' — ', $parts);
    }

    public function new_order_email_heading($heading, $order): string {
        if (!$order instanceof \WC_Order) return $heading;
        $custom = $this->order_booking_heading($order);
        return $custom ?: $heading;
    }

    public function new_order_email_subject($subject, $order): string {
        if (!$order instanceof \WC_Order) return $subject;
        $custom = $this->order_booking_heading($order);
        return $custom ? '[Đặt lịch] ' . $custom : $subject;
    }

    public function customer_email_subject($subject, $order): string {
        if (!$order instanceof \WC_Order) return $subject;
        $items   = $order->get_items();
        $service = $items ? reset($items)->get_name() : '';
        $date    = $order->get_meta('_booking_date');
        $time    = $order->get_meta('_booking_time');

        $parts = array_filter([$service ?: '', $date ?: '', $time ?: '']);
        return $parts ? '[Đặt lịch] ' . implode(' — ', $parts) : $subject;
    }

    // ── Guests pricing ────────────────────────────────────────────────────

    public function restore_guests_cart_item(array $cart_item, array $values): array
    {
        if (!empty($values['tsh_guests'])) {
            $cart_item['tsh_guests'] = (int) $values['tsh_guests'];
        }
        return $cart_item;
    }

    public function apply_guests_price(\WC_Cart $cart): void
    {
        if (is_admin() && !defined('DOING_AJAX')) return;
        foreach ($cart->get_cart() as $item) {
            $guests = (int) ($item['tsh_guests'] ?? 1);
            if ($guests <= 1) continue;
            $base = (float) $item['data']->get_regular_price();
            if ($base > 0) {
                $item['data']->set_price($base * $guests);
            }
        }
    }

    public function display_guests_in_cart(array $item_data, array $cart_item): array
    {
        $guests = (int) ($cart_item['tsh_guests'] ?? 1);
        if ($guests > 1) {
            $item_data[] = [
                'key'   => 'Số người tham gia',
                'value' => $guests . ' người',
            ];
        }
        return $item_data;
    }

    // ── Booking meta ──────────────────────────────────────────────────────

    public function display_booking_meta(\WC_Order $order): void
    {
        $labels = [
            '_booking_date'       => 'Ngày đặt',
            '_booking_time'       => 'Khung giờ',
            '_booking_location'   => 'Chi nhánh',
            '_booking_guests'     => 'Số người',
            '_booking_instructor' => 'Người hướng dẫn',
            '_booking_children'   => 'Trẻ em tham gia',
        ];

        $rows = [];
        foreach ($labels as $key => $label) {
            $val = $order->get_meta($key);
            if ($val) $rows[] = '<strong>' . esc_html($label) . ':</strong> ' . esc_html($val);
        }

        if (!$rows) return;

        echo '<div class="tsh-booking-meta" style="margin-top:12px"><h4>Thông tin đặt lịch</h4><p>' . implode('<br>', $rows) . '</p></div>';
    }
}

new TSH_WooCommerce_Hook();
