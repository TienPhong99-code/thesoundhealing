<?php

/**
 * Header Main Component
 * Design: KienA Figma — Node 94:28119
 */

defined('ABSPATH') || exit;

$lang_vi = home_url('/');
$lang_en = '#';
?>

<!-- =============================================
     HEADER
     ============================================= -->
<header class="hd <?php echo is_front_page() ? ' hd-home' : ''; ?>">
   <div class="container">
      <div class="h-full flex items-center justify-between">

         <!-- Logo -->
         <a href="<?php echo esc_url(home_url('/')); ?>" class="block relative z-1 shrink-0 hd-logo">
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
                  'container_class' => 'flex items-center gap-6',
                  'menu_class'     => 'menu-list flex items-center gap-6',
                  'link_class'     => 'hd-nav-link',
                  'depth'          => 2,
                  'fallback_cb'    => false,
                  'walker'         => new Mona_Walker_Nav_Menu_Desktop(),
               ]); ?>

               <!-- Divider -->
               <span class="block w-px h-6 bg-[#d9d9d9] shrink-0"></span>

               <!-- Language switcher -->
               <div class="flex items-center gap-2 hd-lang">
                  <a href="<?php echo esc_url($lang_vi); ?>" class="font-bold text-[14px] uppercase lang-active">VI</a>
                  <span class="block w-px h-4 bg-[#d9d9d9]"></span>
                  <a href="<?php echo esc_url($lang_en); ?>" class="font-bold text-[14px] uppercase">EN</a>
               </div>

            </div>
         </div>

         <!-- Hamburger (mobile) -->
         <button type="button" class="js-nav-open  max-xl:flex hidden items-center justify-center w-10 h-10 cursor-pointer" aria-label="<?php esc_attr_e('Mở menu', 'monamedia'); ?>">
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
      <a href="<?php echo esc_url($lang_vi); ?>" class="font-bold text-[14px] uppercase lang-active">VI</a>
      <span class="block w-px h-4 bg-[#d9d9d9]"></span>
      <a href="<?php echo esc_url($lang_en); ?>" class="font-bold text-[14px] uppercase">EN</a>
   </div>

</div>