<?php

/**
 * Trang tải e-ticket (mở từ nút trong email e-ticket).
 * Card render GIỐNG email e-ticket (2 cột: nội dung trái + banner phải), cố định
 * rộng 820px để html2canvas chụp ra ảnh đúng giao diện desktop dù xem trên mobile.
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
    'date'       => 'Booking date',
    'time'       => 'Time slot',
    'branch'     => 'Branch',
    'name'       => 'Full name',
    'phone'      => 'Phone number',
    'email'      => 'Email',
    'guests'     => 'Guests',
    'children'   => 'Children',
    'instructor' => 'Instructor',
    'service'    => 'Service',
    'expiry'     => 'Expiry date',
    'people'     => 'people',
    'contact'    => 'Contact hotline to book:',
    'hint'       => 'Please present this e-ticket when using the service.',
    'download'   => 'Download image',
    'loading'    => 'Generating…',
    'banner_alt' => 'The Healing Universe of Việt Nam',
] : [
    'page'       => 'E-ticket',
    'code'       => 'Mã e-ticket',
    'date'       => 'Ngày đặt',
    'time'       => 'Khung giờ',
    'branch'     => 'Chi nhánh',
    'name'       => 'Họ và tên',
    'phone'      => 'Số điện thoại',
    'email'      => 'Email',
    'guests'     => 'Số người tham gia',
    'children'   => 'Trẻ em tham gia',
    'instructor' => 'Người hướng dẫn',
    'service'    => 'Dịch vụ',
    'expiry'     => 'Ngày hết hạn',
    'people'     => 'người',
    'contact'    => 'Liên hệ hotline để đặt lịch:',
    'hint'       => 'Vui lòng xuất trình mã e-ticket khi sử dụng dịch vụ.',
    'download'   => 'Tải ảnh',
    'loading'    => 'Đang tạo…',
    'banner_alt' => 'The Healing Universe of Việt Nam',
];

$order_id     = $order->get_id();
$code         = '#' . str_pad($order_id, 5, '0', STR_PAD_LEFT);
$b_date       = (string) $order->get_meta('_booking_date');
$b_time       = (string) $order->get_meta('_booking_time');
$b_loc        = (string) $order->get_meta('_booking_location');
$b_guests     = (string) $order->get_meta('_booking_guests');
$b_children   = (string) $order->get_meta('_booking_children');
$b_instructor = (string) $order->get_meta('_booking_instructor');
$name         = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
$phone        = (string) $order->get_billing_phone();
$email        = (string) $order->get_billing_email();
$items        = $order->get_items();
$service      = $items ? reset($items)->get_name() : '';
// Vé quà tặng: KHÔNG hiện tổng tiền/thanh toán. Hiện ngày hết hạn (nếu có).
$expiry       = function_exists('tsh_eticket_expiry') ? tsh_eticket_expiry($order) : '';
$expiry_fmt   = $expiry ? date_i18n('d/m/Y', strtotime($expiry)) : '';

$logo_url   = MONA_THEME_PATH_URI . '/assets/images/logo2.png';
$banner_url = MONA_THEME_PATH_URI . '/assets/images/banner-confirm.png';
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

$rows  = $row($L['date'], $b_date);
$rows .= $row($L['time'], $b_time);
$rows .= $row($L['branch'], $b_loc);
$rows .= $row($L['name'], $name);
$rows .= $row($L['phone'], $phone);
$rows .= $row($L['email'], $email);
$rows .= $row($L['guests'], $b_guests !== '' ? $b_guests . ' ' . $L['people'] : '');
$rows .= $row($L['children'], $b_children);
$rows .= $row($L['instructor'], $b_instructor);
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

        body {
            margin: 0;
            padding: 24px 12px 48px;
            background: #f4f2ec;
            font-family: Arial, Helvetica, sans-serif;
            color: #1b1c19
        }

        .tsh-et-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch
        }

        .tsh-et-card {
            width: 820px;
            margin: 0 auto;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(27, 28, 25, .08)
        }

        .tsh-et-actions {
            width: 820px;
            max-width: 100%;
            margin: 22px auto 0;
            text-align: center
        }

        .tsh-et-dl {
            display: inline-block;
            background: #c2a056;
            color: #fff;
            border: 0;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 14px 40px;
            border-radius: 8px;
            cursor: pointer
        }

        .tsh-et-dl[disabled] {
            opacity: .6;
            cursor: default
        }
    </style>
</head>

<body>
    <div class="tsh-et-scroll">
        <table class="tsh-et-card" id="tsh-et-card" cellpadding="0" cellspacing="0" border="0">
            <!-- Header: logo -->
            <tr>
                <td style="background:#fff;padding:22px 24px;text-align:center;border-bottom:1px solid #efece4">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="180" style="display:inline-block;width:180px;max-width:58%;height:auto;border:0">
                </td>
            </tr>
            <!-- Body: 2 cột (nội dung trái, banner phải) -->
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
                                <div style="margin-top:18px;padding:14px 16px;background:#fbf8f0;border-radius:10px;font-size:14px">
                                    <p style="margin:0 0 6px;color:#717171;font-size:12px"><?php echo esc_html($L['contact']); ?></p>
                                    <p style="margin:0"><strong>English:</strong> 0939 624 684 &nbsp;|&nbsp; <strong>Tiếng Việt:</strong> 0906 502 582</p>
                                </div>
                                <p style="margin:14px 0 0;font-size:12px;color:#8a8577;font-style:italic"><?php echo esc_html($L['hint']); ?></p>
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

    <div class="tsh-et-actions">
        <button type="button" class="tsh-et-dl" id="tsh-et-dl"><?php echo esc_html($L['download']); ?></button>
    </div>

    <script src="<?php echo esc_url($h2c_url); ?>"></script>
    <script>
        (function() {
            var btn = document.getElementById('tsh-et-dl');
            var card = document.getElementById('tsh-et-card');
            if (!btn || !card) return;

            btn.addEventListener('click', function() {
                if (typeof html2canvas === 'undefined') {
                    window.print();
                    return;
                }
                var orig = btn.textContent;
                btn.disabled = true;
                btn.textContent = <?php echo wp_json_encode($L['loading']); ?>;

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
                            a.download = 'e-ticket-<?php echo esc_js(str_pad($order_id, 5, '0', STR_PAD_LEFT)); ?>.png';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(url);
                            btn.disabled = false;
                            btn.textContent = orig;
                        }, 'image/png');
                    })
                    .catch(function() {
                        btn.disabled = false;
                        btn.textContent = orig;
                    });
            });
        })();
    </script>
</body>

</html>