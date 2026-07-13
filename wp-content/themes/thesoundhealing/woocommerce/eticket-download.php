<?php

/**
 * Trang tải e-ticket (mở từ nút trong email e-ticket).
 * TỰ ĐỘNG chụp ảnh + tải ngay khi vào trang — KHÔNG preview card. Card render
 * ẩn ngoài màn hình (820px, giống email e-ticket) để html2canvas chụp ra ảnh
 * đúng giao diện desktop. Khách chỉ thấy màn "đang tạo" rồi ảnh tự tải.
 *
 * Biến truyền vào từ TSH_WooCommerce_Hook::handle_eticket_page():
 * @var WC_Order $order
 * @var string   $lang  'en' | 'vi'
 */

defined('ABSPATH') || exit;

$is_en = ($lang === 'en');

$L = $is_en ? [
    'page'       => 'E-ticket',
    'code'       => 'E-ticket code',
    'branch'     => 'Branch',
    'name'       => 'Full name',
    'phone'      => 'Phone number',
    'guests'     => 'Guests',
    'children'   => 'Children',
    'service'    => 'Service / Course',
    'expiry'     => 'Expiry date',
    'notes'      => 'NOTES',
    'people'     => 'people',
    'gift'       => 'This gift is sent to you with love and warm wishes for moments of rest, relaxation, and renewed energy. To use your E-ticket, please contact us via Zalo/WhatsApp/Viber to make a booking before your visit:',
    'hotline_vi' => 'Vietnamese',
    'hotline_en' => 'English',
    'website'    => 'Or via Website:',
    'welcome'    => 'We look forward to welcoming you at HEALIVERSE.',
    'creating'   => 'Preparing your e-ticket…',
    'done'       => 'Your e-ticket has been downloaded. If it didn’t start, tap the button below.',
    'retry'      => 'Download e-ticket',
    'banner_alt' => 'The Healing Universe of Việt Nam',
] : [
    'page'       => 'E-ticket',
    'code'       => 'Mã e-ticket',
    'branch'     => 'Chi nhánh',
    'name'       => 'Họ và tên',
    'phone'      => 'Số điện thoại',
    'guests'     => 'Số người tham gia',
    'children'   => 'Trẻ em tham gia',
    'service'    => 'Dịch vụ / Khoá học',
    'expiry'     => 'Ngày hết hạn',
    'notes'      => 'NOTES',
    'people'     => 'người',
    'gift'       => 'Món quà này được gửi đến bạn như một lời yêu thương và lời chúc cho những phút giây nghỉ ngơi, thư giãn và tái tạo năng lượng. Để sử dụng E-ticket, vui lòng liên hệ với chúng tôi qua Zalo/Whatsapp/Viber để đặt lịch trước khi đến:',
    'hotline_vi' => 'Tiếng Việt',
    'hotline_en' => 'English',
    'website'    => 'Hoặc qua Website:',
    'welcome'    => 'Chúng tôi rất mong được chào đón bạn tại HEALIVERSE.',
    'creating'   => 'Đang tạo e-ticket của bạn…',
    'done'       => 'E-ticket đã được tải xuống. Nếu chưa tải, bấm nút bên dưới.',
    'retry'      => 'Tải e-ticket',
    'banner_alt' => 'The Healing Universe of Việt Nam',
];

$order_id     = $order->get_id();
$code         = '#' . str_pad($order_id, 5, '0', STR_PAD_LEFT);
$b_loc        = (string) $order->get_meta('_booking_location');
$b_guests     = (string) $order->get_meta('_booking_guests');
$b_children   = (string) $order->get_meta('_booking_children');
$name         = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
$phone        = (string) $order->get_billing_phone();
$note         = (string) $order->get_customer_note();
$items        = $order->get_items();
$service      = $items ? reset($items)->get_name() : '';
// Vé quà tặng: KHÔNG hiện tổng tiền/thanh toán. Hiện ngày hết hạn (nếu có).
$expiry       = function_exists('tsh_eticket_expiry') ? tsh_eticket_expiry($order) : '';
$expiry_fmt   = $expiry ? date_i18n('d/m/Y', strtotime($expiry)) : '';

$logo_url   = MONA_THEME_PATH_URI . '/assets/images/logo2.png';
$banner_url = MONA_THEME_PATH_URI . '/assets/images/banner-confirmv3.png';
$h2c_url    = MONA_THEME_PATH_URI . '/assets/library/html2canvas/html2canvas.min.js';

// 1 dòng label + value (value đã escape). Rỗng → bỏ qua. $gold → tô vàng.
$row = function (string $label, string $value, bool $gold = false): string {
    if ($value === '') return '';
    $vstyle = $gold ? 'font-weight:700;color:#c2a056' : 'font-weight:600;color:#1b1c19';
    return '<tr>'
        . '<td style="padding:8px 12px 8px 0;color:#717171;vertical-align:top;font-size:14px">' . esc_html($label) . '</td>'
        . '<td style="padding:8px 0;text-align:right;word-break:break-word;overflow-wrap:break-word;vertical-align:top;font-size:14px;' . $vstyle . '">' . esc_html($value) . '</td>'
        . '</tr>';
};

$rows  = $row($L['branch'], $b_loc);
$rows .= $row($L['name'], $name);
$rows .= $row($L['phone'], $phone);
$rows .= $row($L['guests'], $b_guests !== '' ? $b_guests . ' ' . $L['people'] : '');
$rows .= $row($L['children'], $b_children);
$rows .= $row($L['service'], $service);
$rows .= $row($L['expiry'], $expiry_fmt, true);
?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr($lang); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo esc_html($L['page'] . ' ' . $code); ?></title>
    <style>
        * {
            box-sizing: border-box
        }

        html,
        body {
            overflow-x: hidden
        }

        body {
            margin: 0;
            padding: 40px 16px;
            background: #f4f2ec;
            font-family: Arial, Helvetica, sans-serif;
            color: #1b1c19
        }

        .tsh-et-status {
            max-width: 420px;
            margin: 24px auto;
            text-align: center;
            padding: 34px 26px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(27, 28, 25, .06)
        }

        .tsh-et-status img {
            width: 150px;
            max-width: 60%;
            height: auto;
            margin: 0 auto 20px
        }

        .tsh-et-spin {
            width: 34px;
            height: 34px;
            margin: 0 auto 18px;
            border: 3px solid #eee;
            border-top-color: #c2a056;
            border-radius: 50%;
            animation: tsh-spin .8s linear infinite
        }

        @keyframes tsh-spin {
            to {
                transform: rotate(360deg)
            }
        }

        .tsh-et-msg {
            margin: 0 0 18px;
            font-size: 15px;
            line-height: 1.6;
            color: #444
        }

        .tsh-et-dl {
            display: inline-block;
            background: #c2a056;
            color: #fff;
            border: 0;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 13px 34px;
            border-radius: 8px;
            cursor: pointer
        }

        .tsh-et-dl[disabled] {
            opacity: .6;
            cursor: default
        }

        /* Card ẩn ngoài màn hình — chỉ để html2canvas chụp, không cho user xem */
        .tsh-et-offscreen {
            position: absolute;
            left: -9999px;
            top: 0;
            width: 820px
        }

        .tsh-et-card {
            width: 820px;
            background: #fff;
            border-radius: 14px;
            overflow: hidden
        }
    </style>
</head>

<body>
    <!-- Màn hình khách thấy: đang tạo → đã tải. KHÔNG preview card. -->
    <div class="tsh-et-status">
        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <div class="tsh-et-spin" id="tsh-et-spin"></div>
        <p class="tsh-et-msg" id="tsh-et-msg"><?php echo esc_html($L['creating']); ?></p>
        <button type="button" class="tsh-et-dl" id="tsh-et-dl" style="display:none"><?php echo esc_html($L['retry']); ?></button>
    </div>

    <!-- Card e-ticket (ẩn ngoài màn hình) — giống email e-ticket, 2 cột + banner -->
    <div class="tsh-et-offscreen" aria-hidden="true">
        <table class="tsh-et-card" id="tsh-et-card" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td style="padding:0">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                        <tr>
                            <td style="width:58%;padding:26px 24px;vertical-align:top;font-size:14px;line-height:1.5;color:#1b1c19">
                                <div style="padding:14px 18px;background:#faf8f4;border:1px dashed #c2a056;border-radius:10px;margin-bottom:18px">
                                    <span style="color:#717171;font-size:13px"><?php echo esc_html($L['code']); ?></span><br>
                                    <strong style="color:#c2a056;font-size:22px;letter-spacing:1px"><?php echo esc_html($code); ?></strong>
                                </div>
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;font-size:14px">
                                    <?php echo $rows; // phpcs:ignore WordPress.Security.EscapeOutput 
                                    ?>
                                </table>
                                <?php if ($note !== '') : ?>
                                    <div style="margin-top:18px;padding:12px 16px;background:#faf8f4;border-radius:10px;font-size:14px">
                                        <p style="margin:0 0 4px;color:#717171;font-size:12px;font-weight:700;letter-spacing:.5px"><?php echo esc_html($L['notes']); ?></p>
                                        <p style="margin:0;color:#1b1c19"><?php echo nl2br(esc_html($note)); // phpcs:ignore WordPress.Security.EscapeOutput
                                                                            ?></p>
                                    </div>
                                <?php endif; ?>
                                <div style="margin-top:18px;padding:14px 16px;background:#fbf8f0;border-radius:10px;font-size:13px;line-height:1.6;color:#5c584d">
                                    <p style="margin:0 0 10px"><?php echo esc_html($L['gift']); ?></p>
                                    <p style="margin:0 0 4px"><strong><?php echo esc_html($L['hotline_vi']); ?>:</strong> 0906 502 582 &nbsp;|&nbsp; <strong><?php echo esc_html($L['hotline_en']); ?>:</strong> 0939 624 684</p>
                                    <p style="margin:0 0 4px"><?php echo esc_html($L['website']); ?> <strong>thesoundhealing.vn</strong></p>
                                    <p style="margin:0"><?php echo esc_html($L['welcome']); ?></p>
                                </div>
                            </td>
                            <td style="width:42%;background:#f4f7ee;vertical-align:top;font-size:0;line-height:0">
                                <img src="<?php echo esc_url($banner_url); ?>" alt="<?php echo esc_attr($L['banner_alt']); ?>" style="display:block;width:100%;height:auto;border:0">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <script src="<?php echo esc_url($h2c_url); ?>"></script>
    <script>
        (function() {
            var card = document.getElementById('tsh-et-card');
            var msg = document.getElementById('tsh-et-msg');
            var spin = document.getElementById('tsh-et-spin');
            var btn = document.getElementById('tsh-et-dl');
            if (!card) return;

            var FILENAME = 'e-ticket-<?php echo esc_js(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?>.png';
            var DONE = <?php echo wp_json_encode($L['done']); ?>;

            function finish() {
                if (spin) spin.style.display = 'none';
                if (msg) msg.textContent = DONE;
                if (btn) btn.style.display = '';
            }

            function generate() {
                if (typeof html2canvas === 'undefined') {
                    finish();
                    return;
                }
                html2canvas(card, {
                        scale: 2,
                        useCORS: true,
                        backgroundColor: '#ffffff'
                    })
                    .then(function(canvas) {
                        canvas.toBlob(function(blob) {
                            var url = URL.createObjectURL(blob);
                            var a = document.createElement('a');
                            a.href = url;
                            a.download = FILENAME;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(url);
                            finish();
                        }, 'image/png');
                    })
                    .catch(function() {
                        finish();
                    });
            }

            // Tự chụp + tải ngay khi ảnh (logo + banner) load xong.
            if (document.readyState === 'complete') generate();
            else window.addEventListener('load', generate);

            // Nút "Tải lại" (nếu trình duyệt chặn auto-download) → user tự bấm.
            if (btn) btn.addEventListener('click', generate);
        })();
    </script>
</body>

</html>