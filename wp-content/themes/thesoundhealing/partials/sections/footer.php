<?php
defined('ABSPATH') || exit;

$company    = get_field('footer_company', 'option')     ?: [];
$raw_socials = get_field('footer_socials', 'option')    ?: [];
$sample = [
   'logo'      => null,
   'tagline'   => 'Conscious growth for the modern soul. Zen minimalism in sound & energy.',
   'socials'   => [
      ['label' => 'Instagram', 'url' => '#'],
      ['label' => 'Facebook',  'url' => '#'],
      ['label' => 'LinkedIn',  'url' => '#'],
   ],
   'address'   => '123 Thảo Điền, Quận 2, TP. Hồ Chí Minh',
   'email'     => 'hello@aetheria.vn',
   'phone'     => '+84 90 123 4567',
   'copyright' => 'Copyright © 2026 HEALIVERSE HOLDINGS., JSC | The Sound Healing',
];

// ── Link "Chính sách & Điều khoản" ────────────────────────────────────────
// Bắt buộc phải có ở footer cho hồ sơ BCT. Chèn thẳng vào <ul> của footer menu
// thay vì thêm item vào menu trong wp-admin, vì menu #2 đang gán cho CẢ header
// lẫn footer — thêm ở đó sẽ hiện luôn trên header.
// Lấy trang bản tiếng Việt rồi nhờ WPML đổi sang bản đúng ngôn ngữ hiện tại,
// nên nhãn + URL tự khớp (/chinh-sach/ ↔ /en/policies/).
$_policy = get_posts([
   'post_type'        => 'page',
   'name'             => 'chinh-sach',
   'post_status'      => 'publish',
   'numberposts'      => 1,
   'suppress_filters' => true, // bỏ qua lọc ngôn ngữ của WPML để luôn tìm ra bản gốc
]);

$policy_link = '';
if (! empty($_policy[0])) {
   $_pid = (int) apply_filters('wpml_object_id', $_policy[0]->ID, 'page', true);
   if ($_pid) {
      $policy_link = sprintf(
         '<li class="menu-item"><a href="%s"><span class="text-[#414847] text-[13px] hover:text-[#c2a056] transition-colors">%s</span></a></li>',
         esc_url(get_permalink($_pid)),
         esc_html(get_the_title($_pid))
      );
   }
}

$data = [
   'logo'      => $company['logo'] ?? null,
   'tagline'   => get_field('footer_tagline', 'option')    ?: $sample['tagline'],
   'socials'   => !empty($raw_socials) ? $raw_socials      : $sample['socials'],
   'address'   => !empty($company['address']) ? $company['address'] : $sample['address'],
   'email'     => !empty($company['email'])   ? $company['email']   : $sample['email'],
   'phone'     => !empty($company['hotline']) ? $company['hotline'] : $sample['phone'],
   'copyright' => get_field('footer_copyright', 'option') ?: $sample['copyright'],
];
?>

<footer class="site-footer bg-[#f5f3ee] py-5 border-t border-[rgba(192,200,198,0.25)]">
   <div class="container">
      <div class="flex items-center justify-between gap-4 max-lg:flex-col max-lg:items-start max-lg:gap-5">
         <!-- Logo + Socials -->
         <div class="flex items-center gap-2 shrink-0">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
               <?php if (!empty($data['logo'])) : ?>
                  <?php echo mona_get_image_by_id($data['logo'], 'medium', false, ['class' => 'block h-12 w-auto', 'sizes' => '110px', 'alt' => get_bloginfo('name')]); ?>
               <?php else : ?>
                  <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/logo2.png'); ?>"
                     class="block h-8 w-auto"
                     alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
               <?php endif; ?>
            </a>
            <?php if (!empty($data['socials'])) : ?>
               <div class="flex gap-4">
                  <?php foreach ($data['socials'] as $social) : ?>
                     <a href="<?php echo esc_url($social['url'] ?? '#'); ?>"
                        class="text-[#c2a056] text-[13px] hover:opacity-70 transition-opacity">
                        <?php echo esc_html($social['label']); ?>
                     </a>
                  <?php endforeach; ?>
               </div>
            <?php endif; ?>
         </div>

         <!-- Nav Links -->
         <?php
         $_menu_class = 'flex flex-wrap gap-x-6 gap-y-2 list-none m-0 p-0 items-center';

         if (has_nav_menu('footer-menu')) :
            wp_nav_menu([
               'theme_location' => 'footer-menu',
               'container'      => false,
               'menu_class'     => $_menu_class,
               'depth'          => 1,
               'fallback_cb'    => false,
               'link_before'    => '<span class="text-[#414847] text-[13px] hover:text-[#c2a056] transition-colors">',
               'link_after'     => '</span>',
               // Chèn link chính sách vào cuối <ul> để nó ăn đúng style menu.
               'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s' . $policy_link . '</ul>',
            ]);
         elseif ($policy_link) : // chưa gán menu nào → vẫn phải có link chính sách
         ?>
            <ul class="<?php echo esc_attr($_menu_class); ?>"><?php echo $policy_link; ?></ul>
         <?php endif; ?>

         <!-- Contact -->
         <div class="flex items-center gap-6 shrink-0 max-sm:flex-col max-sm:items-start max-sm:gap-2">
            <?php if (!empty($data['email'])) : ?>
               <a href="mailto:<?php echo esc_attr($data['email']); ?>"
                  class="text-[#414847] text-[13px] hover:text-[#c2a056] transition-colors">
                  <?php echo esc_html($data['email']); ?>
               </a>
            <?php endif; ?>
            <?php if (!empty($data['phone'])) : ?>
               <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $data['phone'])); ?>"
                  class="text-[#414847] text-[13px] hover:text-[#c2a056] transition-colors">
                  <?php echo esc_html($data['phone']); ?>
               </a>
            <?php endif; ?>
         </div>

      </div>

      <!-- Thông tin pháp nhân (bắt buộc công bố) -->
      <?php
      /**
       * 4 thông tin luật bắt buộc công bố về chủ sở hữu website: tên pháp nhân,
       * MST kèm nơi + ngày cấp, địa chỉ trụ sở, liên hệ.
       *
       * Để cứng trong code chứ không lấy từ ACF: field "Địa chỉ" và "Mã số thuế"
       * trong Theme Settings đang trống, và dòng bản quyền cũ ghi "HEALIVERSE
       * HOLDINGS., JSC" — không khớp tên pháp nhân trên giấy ĐKKD. Dữ liệu này
       * không được phép sai lệch nên không để phụ thuộc vào ô nhập.
       */
      // Chọn chữ theo ngôn ngữ ngay trong PHP thay vì __(): trên site này gettext
      // của theme KHÔNG ra tiếng Anh ở /en/ (textdomain nạp trước khi WPML đổi
      // locale), nên dùng __() thì nhãn sẽ kẹt tiếng Việt. Cùng cách với
      // inc/data/chinh-sach.php.
      $_lang = defined('ICL_LANGUAGE_CODE') ? (string) ICL_LANGUAGE_CODE : substr((string) determine_locale(), 0, 2);
      $_en   = $_lang === 'en';

      $legal = [
         'name'    => 'CÔNG TY CỔ PHẦN HEALIVERSE',
         'tax'     => '0317596409',
         'tax_by'  => $_en
            ? 'issued by the Department of Finance of Ho Chi Minh City on 06 Dec 2022'
            : 'do Sở Tài chính TP. Hồ Chí Minh cấp ngày 06/12/2022',
         'address' => $_en
            ? '104/20 Mai Thi Luu, Tan Dinh Ward, Ho Chi Minh City'
            : '104/20 Mai Thị Lựu, Phường Tân Định, TP. Hồ Chí Minh',
         'l_tax'   => $_en ? 'Tax code' : 'MST',
         'l_addr'  => $_en ? 'Address'  : 'Địa chỉ',
         'hotline' => !empty($data['phone']) ? $data['phone'] : '0939 624 684 - 0906 502 582',
         'email'   => !empty($data['email']) ? $data['email'] : 'admin@thesoundhealing.vn',
      ];
      ?>
      <div class="company-legal mt-4 pt-4 border-t border-[rgba(192,200,198,0.25)] text-center text-[#717171] text-[12px] leading-[20px] max-lg:text-left">
         <p>&copy; <?php echo esc_html($legal['name']); ?></p>
         <p><?php printf('%s: %s, %s.', esc_html($legal['l_tax']), esc_html($legal['tax']), esc_html($legal['tax_by'])); ?></p>
         <p><?php printf('%s: %s.', esc_html($legal['l_addr']), esc_html($legal['address'])); ?></p>
         <p>
            Hotline:
            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', strtok($legal['hotline'], '-'))); ?>"
               class="hover:text-[#c2a056] transition-colors"><?php echo esc_html($legal['hotline']); ?></a>
            |
            Email:
            <a href="mailto:<?php echo esc_attr($legal['email']); ?>"
               class="hover:text-[#c2a056] transition-colors"><?php echo esc_html($legal['email']); ?></a>
         </p>

         <?php // Sau khi BCT duyệt: dán logo "Đã thông báo" (khách gửi lại mã) vào đây.
         ?>
      </div>
   </div>
</footer>