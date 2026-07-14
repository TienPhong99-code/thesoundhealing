<?php defined('ABSPATH') || exit; ?>
</td>
</tr>
<!-- /Body end -->

<!-- Support note -->
<tr>
  <td style="padding:32px 36px 0">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td style="background:#faf8f4;border-radius:8px;padding:20px 24px">
          <p style="margin:0 0 12px;font-size:12px;color:#999;line-height:1.9">
            <?php esc_html_e('Bạn cần hỗ trợ? Liên hệ chúng tôi qua email', 'monamedia'); ?>
            <a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>" style="color:#c2a056;text-decoration:none"><?php echo esc_html(get_option('admin_email')); ?></a>
            <?php esc_html_e('hoặc gọi hotline. Chúng tôi luôn sẵn sàng phục vụ bạn.', 'monamedia'); ?>
          </p>
          <p style="margin:0;font-size:12px;font-weight:600;color:#1b1c19;line-height:1.9">
            English: <a href="tel:0939624684" style="color:#c2a056;text-decoration:none">0939 624 684</a>
            &nbsp;|&nbsp;
            <?php esc_html_e('Tiếng Việt', 'monamedia'); ?>: <a href="tel:0906502582" style="color:#c2a056;text-decoration:none">0906 502 582</a>
          </p>
        </td>
      </tr>
    </table>
  </td>
</tr>

</table>

<!-- Bottom spacer -->
<table class="email-wrap" width="560" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="height:16px;font-size:0;line-height:0">&nbsp;</td>
  </tr>
</table>

</td>
</tr>
</table>
</body>

</html>