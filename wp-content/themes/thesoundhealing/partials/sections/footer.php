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

<footer class="site-footer bg-[#f5f3ee] py-5 border-t border-[rgba(192,200,198,0.25)]">
   <div class="container">
      <div class="flex items-center justify-between gap-8 max-lg:flex-col max-lg:items-start max-lg:gap-5">

         <!-- Logo + Socials -->
         <div class="flex items-center gap-6 shrink-0">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
               <?php if (!empty($data['logo'])) : ?>
                  <?php echo mona_get_image_by_id($data['logo'], 'full', false, ['class' => 'block h-8 w-auto', 'alt' => get_bloginfo('name')]); ?>
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
         <?php wp_nav_menu([
            'theme_location' => 'footer-menu',
            'container'      => false,
            'menu_class'     => 'flex flex-wrap gap-x-6 gap-y-2 list-none m-0 p-0 items-center',
            'depth'          => 1,
            'fallback_cb'    => false,
            'link_before'    => '<span class="text-[#414847] text-[13px] hover:text-[#c2a056] transition-colors">',
            'link_after'     => '</span>',
         ]); ?>

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
   </div>
</footer>