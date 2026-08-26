<?php

/**
 * Email "Đơn hoàn thành" — thư cảm ơn sau buổi trải nghiệm + mời đánh giá Google.
 * KHÔNG hiện tóm tắt đơn hàng (email "đang xử lý" đã có chi tiết đặt lịch).
 *
 * Chuỗi dịch qua WPML String Translation (context "monamedia") bằng helper
 * mona_wpml_string() — client sửa bản EN trong WPML → String Translation, KHÔNG dùng .po/.mo.
 *
 * @var WC_Order  $order
 * @var string    $email_heading
 * @var WC_Email  $email
 */

defined('ABSPATH') || exit;

$name       = trim($order->get_billing_first_name() . ' ' . $order->get_billing_last_name());
$review_url = 'https://maps.app.goo.gl/QxQDTDfYL5SYFkVs8';

// Ngôn ngữ ép theo ĐƠN chứ không theo ngôn ngữ hiện hành: email gửi khi nhân viên bấm
// "Hoàn thành" trong admin (tiếng Việt) → để WPML tự chọn thì khách EN nhận email tiếng Việt.
$lang = tsh_order_lang($order);

// Thân email này dịch qua WPML ($lang ở trên), nhưng footer email vẫn dùng gettext
// (domain 'monamedia') → phải ép locale luôn. Restore ở cuối file.
$tsh_switched = tsh_switch_email_locale($order);

// Chuỗi gốc tiếng Việt → dịch qua WPML String Translation. Xuất ra đều esc_html().
$t_greet   = mona_wpml_string('Kính gửi', 'Email hoàn thành – lời chào', 'monamedia', $lang);
$t_guest   = mona_wpml_string('Quý khách', 'Email hoàn thành – tên mặc định', 'monamedia', $lang);
$t_p1      = mona_wpml_string('Cảm ơn bạn đã lựa chọn HEALIVERSE.', 'Email hoàn thành – câu 1', 'monamedia', $lang);
$t_p2      = mona_wpml_string('Chúng tôi hy vọng trải nghiệm tại HEALIVERSE đã mang đến cho bạn những phút giây thư giãn sâu, dễ chịu và tiếp thêm năng lượng tích cực trên hành trình chăm sóc tinh thần và phát triển bản thân.', 'Email hoàn thành – câu 2', 'monamedia', $lang);
$t_p3      = mona_wpml_string('Nếu bạn hài lòng với trải nghiệm của mình, chúng tôi rất mong bạn dành ít phút để chia sẻ cảm nhận bằng một đánh giá trên Google. Sự ủng hộ của bạn sẽ giúp nhiều người biết đến HEALIVERSE và có thêm cơ hội tìm thấy một không gian nghỉ ngơi, thư giãn và tái tạo năng lượng.', 'Email hoàn thành – câu 3', 'monamedia', $lang);
$t_cta     = mona_wpml_string('Để lại đánh giá 5 sao trên Google', 'Email hoàn thành – CTA tiêu đề', 'monamedia', $lang);
$t_cta_btn = mona_wpml_string('Đánh giá trên Google', 'Email hoàn thành – CTA nút', 'monamedia', $lang);
$t_close   = mona_wpml_string('Chúng tôi rất mong được chào đón bạn quay trở lại bất cứ khi nào bạn cần một khoảng lặng để nghỉ ngơi, tái tạo năng lượng và kết nối với chính mình.', 'Email hoàn thành – câu kết', 'monamedia', $lang);
$t_regards = mona_wpml_string('Trân trọng,', 'Email hoàn thành – Trân trọng', 'monamedia', $lang);
$t_vi_lbl  = mona_wpml_string('Tiếng Việt', 'Email hoàn thành – nhãn hotline VN', 'monamedia', $lang);

do_action('woocommerce_email_header', $email_heading, $email);
?>

<!-- Greeting -->
<p style="margin:0 0 18px;font-size:15px;color:#444;line-height:1.6">
    <?php echo esc_html($t_greet); ?> <strong style="color:#1b1c19"><?php echo esc_html($name ?: $t_guest); ?></strong>,
</p>

<!-- Thank you -->
<p style="margin:0 0 16px;font-size:14px;color:#555;line-height:1.8">
    <?php echo esc_html($t_p1); ?>
</p>
<p style="margin:0 0 16px;font-size:14px;color:#555;line-height:1.8">
    <?php echo esc_html($t_p2); ?>
</p>
<p style="margin:0 0 24px;font-size:14px;color:#555;line-height:1.8">
    <?php echo esc_html($t_p3); ?>
</p>

<!-- Google review CTA -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 26px">
    <tr>
        <td style="background:#faf8f4;border:1px solid #eadfbf;border-radius:10px;padding:22px 20px;text-align:center">
            <p style="margin:0 0 14px;font-size:15px;font-weight:700;color:#1b1c19;line-height:1.5">
                &#11088; <?php echo esc_html($t_cta); ?>
            </p>
            <a href="<?php echo esc_url($review_url); ?>" style="display:inline-block;background:#c2a056;color:#ffffff !important;text-decoration:none;font-size:14px;font-weight:700;letter-spacing:.3px;padding:13px 30px;border-radius:8px">
                <?php echo esc_html($t_cta_btn); ?>
            </a>
        </td>
    </tr>
</table>

<!-- Closing -->
<p style="margin:0 0 28px;font-size:14px;color:#555;line-height:1.8">
    <?php echo esc_html($t_close); ?>
</p>

<!-- Signature -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #edeae4">
    <tr>
        <td style="padding-top:22px">
            <p style="margin:0 0 6px;font-size:14px;color:#555;line-height:1.6"><?php echo esc_html($t_regards); ?></p>
            <p style="margin:0;font-size:15px;font-weight:700;color:#1b1c19;line-height:1.5">THE SOUND HEALING by HEALIVERSE</p>
            <p style="margin:2px 0 16px;font-size:13px;font-style:italic;color:#c2a056;letter-spacing:.5px">Sound &bull; Scent &bull; Soul</p>

            <p style="margin:0 0 6px;font-size:13px;color:#555;line-height:1.7">
                &#127760;&nbsp; <a href="https://thesoundhealing.vn" style="color:#c2a056;text-decoration:none">www.thesoundhealing.vn</a>
            </p>
            <p style="margin:0 0 6px;font-size:13px;color:#555;line-height:1.7">
                &#9993;&nbsp; <a href="mailto:admin@thesoundhealing.vn" style="color:#c2a056;text-decoration:none">admin@thesoundhealing.vn</a>
            </p>
            <p style="margin:0 0 4px;font-size:13px;color:#1b1c19;line-height:1.7">
                &#128222;&nbsp; <strong><?php echo esc_html($t_vi_lbl); ?>:</strong> +84 906 502 582
            </p>
            <p style="margin:0;font-size:13px;color:#1b1c19;line-height:1.7">
                &#128222;&nbsp; <strong>English:</strong> +84 939 624 684
            </p>
        </td>
    </tr>
</table>

<!-- Chân thân email: giãn 24px để footer không dính sát. Dùng ô spacer chứ không đặt
     padding-bottom trên <table> — Outlook (engine Word) bỏ qua padding của table. -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td style="height:24px;font-size:0;line-height:0">&nbsp;</td>
    </tr>
</table>

<?php
do_action('woocommerce_email_footer', $email);

if ($tsh_switched) restore_previous_locale();
?>