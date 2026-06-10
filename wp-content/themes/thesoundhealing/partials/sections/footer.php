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
   'copyright' => '© 2024 AETHERIA SPIRITUAL EDUCATION. ALL RIGHTS RESERVED.',
];

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

<footer class="site-footer bg-[#f5f3ee] border-t border-[rgba(192,200,198,0.3)] pt-[81px] pb-10">
   <div class="container max-w-[1280px]">

      <!-- Top: 3 cột -->
      <div class="row gap-y-12">

         <!-- Col 1: Brand Identity -->
         <div class="col col-6 max-md:w-full!">
            <div class="flex flex-col gap-6">

               <!-- Logo -->
               <a href="<?php echo esc_url(home_url('/')); ?>" class="block w-fit max-md:mx-auto">
                  <?php if (!empty($data['logo'])) : ?>
                     <?php echo mona_get_image_by_id($data['logo'], 'full', false, ['class' => 'block w-[240px]  inline-block', 'alt' => get_bloginfo('name')]); ?>
                  <?php else : ?>
                     <img src="<?php echo esc_url(MONA_THEME_PATH_URI . '/assets/images/logo2.png'); ?>"
                        class="block h-8 w-auto"
                        alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                  <?php endif; ?>
               </a>

               <!-- Tagline -->
               <?php if (!empty($data['tagline'])) : ?>
                  <p class="text-[#414847] max-md:text-center text-[16px] leading-[24px] max-w-[384px]">
                     <?php echo wp_kses_post($data['tagline']); ?>
                  </p>
               <?php endif; ?>

               <!-- Social Links -->
               <?php if (!empty($data['socials'])) : ?>
                  <div class="flex gap-6 pt-2 max-md:justify-center">
                     <?php foreach ($data['socials'] as $social) : ?>
                        <a href="<?php echo esc_url($social['url'] ?? '#'); ?>"
                           class="text-[#c2a056] text-[16px] leading-[24px] hover:opacity-70 transition-opacity">
                           <?php echo esc_html($social['label']); ?>
                        </a>
                     <?php endforeach; ?>
                  </div>
               <?php endif; ?>

            </div>
         </div>

         <!-- Col 2: Quick Links -->
         <div class="col col-3 max-md:col-6 max-sm:w-1/2!">
            <div class="flex flex-col gap-6">
               <p class="text-[#c2a056] text-[16px] leading-[24px] uppercase tracking-wider">
                  <?php esc_html_e('MENU', 'monamedia'); ?>
               </p>
               <?php wp_nav_menu([
                  'theme_location' => 'footer-menu',
                  'container'      => false,
                  'menu_class'     => 'flex flex-col gap-3 list-none m-0 p-0',
                  'link_class'     => 'text-[#414847] hover:text-[#133a35] transition-colors',
                  'depth'          => 1,
                  'fallback_cb'    => false,
               ]); ?>
            </div>
         </div>

         <!-- Col 3: Contact -->
         <div class="col col-3 max-md:col-6 max-sm:w-1/2!">
            <div class="flex flex-col gap-6">
               <p class="text-[#c2a056] text-[16px] leading-[24px] uppercase tracking-wider">
                  <?php esc_html_e('LIÊN HỆ', 'monamedia'); ?>
               </p>
               <div class="flex flex-col gap-3">
                  <?php if (!empty($data['address'])) : ?>
                     <p class="text-[#414847]">
                        <?php echo wp_kses_post($data['address']); ?>
                     </p>
                  <?php endif; ?>
                  <?php if (!empty($data['email'])) : ?>
                     <a href="mailto:<?php echo esc_attr($data['email']); ?>"
                        class="text-[#414847] hover:text-[#c2a056] transition-colors">
                        <?php echo esc_html($data['email']); ?>
                     </a>
                  <?php endif; ?>
                  <?php if (!empty($data['phone'])) : ?>
                     <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $data['phone'])); ?>"
                        class="text-[#414847] hover:text-[#c2a056] transition-colors">
                        <?php echo esc_html($data['phone']); ?>
                     </a>
                  <?php endif; ?>
               </div>
            </div>
         </div>

      </div>

      <!-- Bottom Bar -->
      <div class="flex items-center justify-between pt-[33px] mt-20 border-t border-[rgba(192,200,198,0.3)]">
         <p class="text-[#717977] text-[14px] leading-[24px] uppercase tracking-wider">
            <?php echo esc_html($data['copyright']); ?>
         </p>

      </div>

   </div>
</footer>