<?php

/**
 * Header Main Component
 * Design: KienA Figma — Node 94:28119
 */

defined('ABSPATH') || exit;

?>
<?php
function mona_render_lang_switcher($classes = '')
{
   if (!function_exists('icl_get_languages')) return;
   $langs = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str');
   if (empty($langs) || !is_array($langs)) return;
   $label_map = ['vi' => 'VN', 'en' => 'ENG'];
   $items = array_values($langs);
   foreach ($items as $i => $item) {
      if ($i > 0) echo '<span class="block w-px h-4 bg-[#d9d9d9]"></span>';
      $code = $label_map[$item['language_code']] ?? strtoupper($item['language_code']);
      if ($item['active']) {
         echo '<span class="font-bold text-[14px] uppercase lang-active ' . esc_attr($classes) . '">' . esc_html($code) . '</span>';
      } else {
         echo '<a href="' . esc_url($item['url']) . '" class="font-bold text-[14px] uppercase ' . esc_attr($classes) . '">' . esc_html($code) . '</a>';
      }
   }
}
?>

<!-- =============================================
     HEADER
     ============================================= -->
<header class="hd <?php echo is_front_page() ? ' hd-home' : ''; ?>">
   <div class="container">
      <div class="h-full flex items-center justify-between">

         <!-- Logo -->
         <a href="<?php echo esc_url(home_url('/')); ?>" class="block relative z-1 shrink-0 hd-logo max-sm:absolute max-sm:left-1/2 max-sm:translate-x-[-50%]">
            <?php
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo_url = $custom_logo_id
               ? wp_get_attachment_image_url($custom_logo_id, 'full')
               : MONA_THEME_PATH_URI . '/assets/images/logo2.png';
            ?>
            <img src="<?php echo esc_url($logo_url); ?>"
               class="block w-full object-contain" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
         </a>

         <!-- Right: Nav + Language (desktop) -->
         <div class="max-xl:hidden">
            <div class="flex justify-center items-center gap-6 ">

               <!-- Nav items -->
               <?php wp_nav_menu([
                  'theme_location' => 'header-menu-pc',
                  'container'      => 'nav',
                  'container_class' => 'flex items-center gap-6 absolute left-1/2 translate-x-[-50%]',
                  'menu_class'     => 'menu-list flex items-center gap-6',
                  'link_class'     => 'hd-nav-link',
                  'depth'          => 2,
                  'fallback_cb'    => false,
                  'walker'         => new Mona_Walker_Nav_Menu_Desktop(),
               ]); ?>


               <!-- Language switcher -->
               <div class="flex items-center gap-2 hd-lang">
                  <?php mona_render_lang_switcher(); ?>
               </div>

            </div>
         </div>

         <!-- Hamburger (mobile) -->
         <button type="button" class="js-nav-open max-sm:ml-auto max-xl:flex hidden items-center justify-center w-10 h-10 cursor-pointer" aria-label="<?php esc_attr_e('Mở menu', 'monamedia'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
               <line x1="3" y1="6" x2="21" y2="6" stroke="#283377" stroke-width="2" stroke-linecap="round" />
               <line x1="3" y1="12" x2="21" y2="12" stroke="#283377" stroke-width="2" stroke-linecap="round" />
               <line x1="3" y1="18" x2="21" y2="18" stroke="#283377" stroke-width="2" stroke-linecap="round" />
            </svg>
         </button>

      </div>
   </div>
</header>

<!-- =============================================
     MOBILE NAV DRAWER
     ============================================= -->
<div class="hd-nav" id="hd-nav" aria-hidden="true">

   <!-- Top bar -->
   <div class="flex items-center justify-between p-2 border-b border-[#f0f0f0]">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="block hd-logo">
         <img src="<?php echo esc_url($logo_url); ?>"
            class="block w-full object-contain" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
      </a>
      <button type="button" class="js-nav-close flex items-center justify-center w-10 h-10 cursor-pointer" aria-label="<?php esc_attr_e('Đóng menu', 'monamedia'); ?>">
         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <line x1="4" y1="4" x2="20" y2="20" stroke="#283377" stroke-width="2" stroke-linecap="round" />
            <line x1="20" y1="4" x2="4" y2="20" stroke="#283377" stroke-width="2" stroke-linecap="round" />
         </svg>
      </button>
   </div>

   <!-- Nav items -->
   <?php wp_nav_menu([
      'theme_location' => 'header-menu-pc',
      'container'      => 'nav',
      'container_class' => 'flex flex-col p-2',
      'menu_class'     => 'menu-list flex flex-col',
      'link_class'     => 'hd-nav-item font-bold text-[14px] block uppercase py-3 border-b border-[#f0f0f0] text-pri',
      'depth'          => 2,
      'fallback_cb'    => false,
      'walker'         => new Mona_Walker_Nav_Menu_Mobile(),
   ]); ?>

   <!-- Language switcher -->
   <div class="flex items-center gap-3 px-2 pt-2 hd-lang justify-end">
      <?php mona_render_lang_switcher(); ?>
   </div>

</div>