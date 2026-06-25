<?php defined('ABSPATH') || exit; ?>
          </td>
        </tr>
        <!-- /Body end -->

        <!-- Support note -->
        <tr>
          <td style="padding:24px 36px 0">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="background:#faf8f4;border-radius:8px;padding:14px 18px">
                  <p style="margin:0;font-size:12px;color:#999;line-height:1.7">
                    Bạn cần hỗ trợ? Liên hệ chúng tôi qua email
                    <a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>" style="color:#c2a056;text-decoration:none"><?php echo esc_html(get_option('admin_email')); ?></a>
                    hoặc gọi hotline. Chúng tôi luôn sẵn sàng phục vụ bạn.
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="padding:28px 36px;text-align:center;border-top:1px solid #edeae4;margin-top:28px">
            <p style="margin:0 0 6px;font-size:13px;font-weight:600;color:#1b1c19;letter-spacing:2px;text-transform:uppercase">The Sound Healing</p>
            <p style="margin:0 0 12px;font-size:12px;color:#aaa;line-height:1.6">
              <?php echo wp_kses_post(wpautop(wptexturize(apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'))))); ?>
            </p>
            <p style="margin:0;font-size:11px;color:#ccc">
              © <?php echo esc_html(date('Y')); ?> The Sound Healing. All rights reserved.
            </p>
          </td>
        </tr>

      </table>

      <!-- Bottom spacer -->
      <table class="email-wrap" width="560" cellpadding="0" cellspacing="0" border="0">
        <tr><td style="height:40px"></td></tr>
      </table>

    </td>
  </tr>
</table>
</body>
</html>
