<?php
if (! defined('ABSPATH')) {
   die;
}

// Inject link_class + active class vào <a> của wp_nav_menu()
add_filter('nav_menu_link_attributes', function ($atts, $item, $args, $depth) {
   if (! empty($args->link_class)) {
      $base_class    = explode(' ', trim($args->link_class))[0]; // chỉ lấy class đầu tiên làm base
      $classes       = [$args->link_class];
      if ($item->current || $item->current_item_ancestor) {
         $classes[] = $base_class . '--active';
      }
      $atts['class'] = implode(' ', $classes);
   }
   return $atts;
}, 10, 4);

// After setup theme
add_action('after_setup_theme', function () {
   // regsiter menu
   register_nav_menus([
      'header-menu-pc'  => __('Header Menu PC', 'monamedia'),
      'header-menu-mb'  => __('Header Menu Mobile', 'monamedia'),
      'footer-menu'     => __('Footer Menu', 'monamedia'),
   ]);
});

// Register ACF options page
add_action('acf/init', function () {
   if (function_exists('acf_add_options_page')) {
      acf_add_options_page([
         'page_title'  => 'Thiết lập Theme',
         'menu_title'  => 'Theme Settings',
         'menu_slug'   => 'theme-settings',
         'capability'  => 'manage_options',
         'parent_slug' => 'themes.php',
         'position'    => false,
         'icon_url'    => false,
         'redirect'    => false,
      ]);
   }
});

/**
 * Add param to admin url when use ajax
 * 
 * @param string $url The complete admin area URL including scheme and path.
 * @param string $path Path relative to the admin area URL. Blank string if no path is specified.
 * @param int|null $blog_id Site ID, or null for the current site
 * 
 * @return string
 */
add_filter('admin_url', function ($url, $path, $blog_id) {
   if ($path === 'admin-ajax.php' && ! is_admin()) {
      $url .= '?mona-ajax';
   }

   return $url;
}, 999, 3);

// Tailwind CDN
add_action('wp_head', function () {
?>
   <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
   <style type="text/tailwindcss">
      @theme {
  --color-pri: #283377;
  --color-sec: #ed1c24;
}
</style>
<?php
}, 1);

// Register css
add_action('wp_enqueue_scripts', function () {
   // CSS thư viện — nằm trên để theme CSS đè lại
   wp_enqueue_style('mona-swiper',    MONA_THEME_PATH_URI . '/assets/library/swiper/swiper-bundle.min.css',   [], MONA_THEME_VERSION);
   wp_enqueue_style('mona-fancybox', MONA_THEME_PATH_URI . '/assets/library/fancybox/fancybox.css',          [], MONA_THEME_VERSION);
   wp_enqueue_style('mona-flatpickr', MONA_THEME_PATH_URI . '/assets/library/flatpickr/flatpickr.min.css',   [], MONA_THEME_VERSION);

   // CSS theme
   if (is_404()) {
      wp_enqueue_style('mona-404', MONA_THEME_PATH_URI . '/assets/css/404.css');
   }
   wp_enqueue_style('mona-main-style', get_stylesheet_uri(), [], filemtime(MONA_THEME_PATH . '/style.css'));
   wp_enqueue_style('mona-style', MONA_THEME_PATH_URI . '/assets/css/style.css', [], filemtime(MONA_THEME_PATH . '/assets/css/style.css'));
   wp_enqueue_style('mona-notification', MONA_THEME_PATH_URI . '/assets/css/notification.css', [], filemtime(MONA_THEME_PATH . '/assets/css/notification.css'));
}, 10);

// Register js
add_action('wp_enqueue_scripts', function () {
   wp_add_inline_script('jquery-core', 'window.$=jQuery');

   // Mở lại khi cần — uncomment từng dòng
   wp_enqueue_script('mona-lenis',            MONA_THEME_PATH_URI . '/assets/library/lenis/lenis.min.js',                             array(), filemtime(MONA_THEME_PATH . '/assets/library/lenis/lenis.min.js'), array('in_footer' => true));
   wp_enqueue_script('mona-swiper',           MONA_THEME_PATH_URI . '/assets/library/swiper/swiper-bundle.min.js',                    array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-aos',              MONA_THEME_PATH_URI . '/assets/library/aos/aos.js',                                     array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-select2',          MONA_THEME_PATH_URI . '/assets/library/select2/select2.min.js',                         array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-flatpickr',        MONA_THEME_PATH_URI . '/assets/library/flatpickr/flatpickr.js',                         array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-SmoothScroll',     MONA_THEME_PATH_URI . '/assets/library/smoothscroll/SmoothScroll.min.js',                array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-splitting',        MONA_THEME_PATH_URI . '/assets/library/splitting/splitting.min.js',                     array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   wp_enqueue_script('mona-fancybox',         MONA_THEME_PATH_URI . '/assets/library/fancybox/fancybox.umd.js',                       array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   wp_enqueue_script('mona-gsap',             MONA_THEME_PATH_URI . '/assets/library/gsap/gsap.min.js',                               array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   wp_enqueue_script('mona-ScrollTrigger',    MONA_THEME_PATH_URI . '/assets/library/gsap/ScrollTrigger.min.js',                      array('mona-gsap'), MONA_THEME_VERSION, array('in_footer' => true));
   wp_enqueue_script('mona-MorphSVGPlugin',   MONA_THEME_PATH_URI . '/assets/library/gsap/MorphSVGPlugin.min.js',                     array('mona-gsap'), filemtime(MONA_THEME_PATH . '/assets/library/gsap/MorphSVGPlugin.min.js'), array('in_footer' => true));
   wp_enqueue_script('mona-SplitText',        MONA_THEME_PATH_URI . '/assets/library/gsap/SplitText.min.js',                          array('mona-gsap'), filemtime(MONA_THEME_PATH . '/assets/library/gsap/SplitText.min.js'),        array('in_footer' => true));
   // wp_enqueue_script('mona-ukiyo',            MONA_THEME_PATH_URI . '/assets/library/ukiyo/ukiyo.min.js',                             array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-splide',           MONA_THEME_PATH_URI . '/assets/library/splide/splide.min.js',                           array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-splide-extension', MONA_THEME_PATH_URI . '/assets/library/splide/splide-extension-auto-scroll.min.js',     array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-vanilla',          MONA_THEME_PATH_URI . '/assets/library/vanilatilt/vanilla-tilt.min.js',                 array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-jquery.ripples',   MONA_THEME_PATH_URI . '/assets/library/ripples/jquery.ripples-min.js',                  array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-main',             MONA_THEME_PATH_URI . '/assets/scripts/main.js',                                        array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
   // wp_enqueue_script('mona-backend',          MONA_THEME_PATH_URI . '/assets/scripts/mona-frontend.js',                               array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));

   // $params = apply_filters('mona_ajax_params', [
   //    'siteURL'   => get_site_url(),
   //    'ajaxURL'   => admin_url('admin-ajax.php'),
   //    'ajaxNonce' => wp_create_nonce('mona-ajax-security'),
   // ]);
   // wp_localize_script('mona-backend', 'mona_params', $params);

   wp_enqueue_script('mona-modal',          MONA_THEME_PATH_URI . '/assets/scripts/modules/common/modal.js',          array(), filemtime(MONA_THEME_PATH . '/assets/scripts/modules/common/modal.js'),          array('in_footer' => true));
   wp_enqueue_script('mona-header',         MONA_THEME_PATH_URI . '/assets/scripts/modules/common/header.js',         array(), filemtime(MONA_THEME_PATH . '/assets/scripts/modules/common/header.js'),         array('in_footer' => true));
   wp_enqueue_script('mona-flatpickr',      MONA_THEME_PATH_URI . '/assets/library/flatpickr/flatpickr.js',                                                               array(), MONA_THEME_VERSION,                                                                                          array('in_footer' => true));
   wp_enqueue_script('mona-search-booking', MONA_THEME_PATH_URI . '/assets/scripts/modules/common/search-booking.js', array('mona-flatpickr'), filemtime(MONA_THEME_PATH . '/assets/scripts/modules/common/search-booking.js'), array('in_footer' => true));
   wp_enqueue_script('mona-main', MONA_THEME_PATH_URI . '/assets/scripts/main.js', array('jquery', 'mona-swiper', 'mona-lenis', 'mona-modal', 'mona-header'), filemtime(MONA_THEME_PATH . '/assets/scripts/main.js'), array('in_footer' => true));

   if (is_front_page()) {
      wp_enqueue_script('mona-hero',    MONA_THEME_PATH_URI . '/assets/scripts/modules/home/hero.js',    array('mona-gsap', 'mona-MorphSVGPlugin', 'mona-SplitText'), filemtime(MONA_THEME_PATH . '/assets/scripts/modules/home/hero.js'),    array('in_footer' => true));
      wp_enqueue_script('mona-home', MONA_THEME_PATH_URI . '/assets/scripts/home.js', array('jquery', 'mona-swiper', 'mona-main', 'mona-gsap', 'mona-ScrollTrigger', 'mona-MorphSVGPlugin', 'mona-SplitText', 'mona-hero'), filemtime(MONA_THEME_PATH . '/assets/scripts/home.js'), array('in_footer' => true));
   }

   if (is_singular('post')) {
      wp_enqueue_script('mona-single', MONA_THEME_PATH_URI . '/assets/scripts/single.js', array('jquery', 'mona-main'), filemtime(MONA_THEME_PATH . '/assets/scripts/single.js'), array('in_footer' => true));
   }

   if (is_singular('khoa_hoc')) {
      wp_enqueue_script('mona-khoa-hoc', MONA_THEME_PATH_URI . '/assets/scripts/khoa-hoc.js', array('jquery', 'contact-form-7', 'mona-fancybox'), filemtime(MONA_THEME_PATH . '/assets/scripts/khoa-hoc.js'), array('in_footer' => true));
      wp_localize_script('mona-khoa-hoc', 'kh_course', [
         'id'   => get_the_ID(),
         'name' => get_the_title(),
      ]);
   }

   if (is_singular('dich_vu')) {
      wp_enqueue_style('mona-flatpickr', MONA_THEME_PATH_URI . '/assets/library/flatpickr/flatpickr.min.css', [], MONA_THEME_VERSION);
      wp_enqueue_script('mona-flatpickr', MONA_THEME_PATH_URI . '/assets/library/flatpickr/flatpickr.js', [], MONA_THEME_VERSION, array('in_footer' => true));
      wp_enqueue_script('mona-dich-vu', MONA_THEME_PATH_URI . '/assets/scripts/dich-vu.js', array('jquery', 'contact-form-7', 'mona-fancybox', 'mona-flatpickr'), filemtime(MONA_THEME_PATH . '/assets/scripts/dich-vu.js'), array('in_footer' => true));
   }

   if (is_singular('workshop')) {
      wp_enqueue_script('mona-ws', MONA_THEME_PATH_URI . '/assets/scripts/ws.js', array('jquery', 'contact-form-7', 'mona-fancybox'), filemtime(MONA_THEME_PATH . '/assets/scripts/ws.js'), array('in_footer' => true));
      wp_localize_script('mona-ws', 'ws_data', [
         'id'   => get_the_ID(),
         'name' => get_the_title(),
      ]);
   }

   if (is_page_template('page-template/template-about.php')) {
      wp_enqueue_script('mona-about', MONA_THEME_PATH_URI . '/assets/scripts/about.js', array('jquery', 'mona-gsap', 'mona-ScrollTrigger', 'mona-main'), filemtime(MONA_THEME_PATH . '/assets/scripts/about.js'), array('in_footer' => true));
   }

   if (is_page_template('page-template/template-hoat-dong-cong-dong.php')) {
      wp_enqueue_style('mona-fancybox', MONA_THEME_PATH_URI . '/assets/library/fancybox/fancybox.css', [], MONA_THEME_VERSION);
      wp_enqueue_script('mona-fancybox', MONA_THEME_PATH_URI . '/assets/library/fancybox/fancybox.umd.js', array('jquery'), MONA_THEME_VERSION, array('in_footer' => true));
      wp_enqueue_script('masonry');
      wp_enqueue_script('mona-hoat-dong-cong-dong', MONA_THEME_PATH_URI . '/assets/scripts/hoat-dong-cong-dong.js', array('jquery', 'mona-main', 'mona-fancybox', 'masonry'), filemtime(MONA_THEME_PATH . '/assets/scripts/hoat-dong-cong-dong.js'), array('in_footer' => true));
   }
}, 10);

// Change script type to module
add_filter('script_loader_tag', function ($tag, $handle) {
   // Handlers
   $handlers = apply_filters('mona_script_to_module', [
      'mona-main',
      'mona-backend',
   ]);

   if (in_array($handle, $handlers)) {
      $tag = str_replace('<script', '<script type="module"', $tag);
   }

   return $tag;
}, 10, 2);

// Preconnect google font
add_action('wp_head', function () {
?>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
<?php
}, 1);

// Dequeue plugin JS không dùng — mở lại khi cần
add_action('wp_enqueue_scripts', function () {
   // WooCommerce
   wp_dequeue_script('wc-jquery-blockui');
   wp_deregister_script('wc-jquery-blockui');
   wp_dequeue_script('wc-add-to-cart');
   wp_deregister_script('wc-add-to-cart');
   wp_dequeue_script('wc-js-cookie');
   wp_deregister_script('wc-js-cookie');
   wp_dequeue_script('woocommerce');
   wp_deregister_script('woocommerce');
   wp_dequeue_script('sourcebuster-js');
   wp_deregister_script('sourcebuster-js');
   wp_dequeue_script('wc-order-attribution');
   wp_deregister_script('wc-order-attribution');

   // Contact Form 7 — tắt toàn site, bật lại ở trang cần
   $need_cf7 = is_singular('tuyen_dung')
      || is_singular('khoa_hoc')
      || is_singular('workshop')
      || is_singular('dich_vu')
      || is_page_template('page-template/template-tuyen-dung.php')
      || is_page_template('page-template/template-contact.php');
   if (! $need_cf7) {
      wp_dequeue_script('swv');
      wp_deregister_script('swv');
      wp_dequeue_script('contact-form-7');
      wp_deregister_script('contact-form-7');
   }

   // DevVN Image Hotspot
   wp_dequeue_script('powertip');
   wp_deregister_script('powertip');
   wp_dequeue_script('maps-points');
   wp_deregister_script('maps-points');

   // A3 Lazy Load
   wp_dequeue_script('jquery-lazyloadxt');
   wp_deregister_script('jquery-lazyloadxt');
   wp_dequeue_script('jquery-lazyloadxt-srcset');
   wp_deregister_script('jquery-lazyloadxt-srcset');
   wp_dequeue_script('jquery-lazyloadxt-extend');
   wp_deregister_script('jquery-lazyloadxt-extend');
}, 99);

// Dequeue plugin CSS không dùng — mở lại khi cần
add_action('wp_enqueue_scripts', function () {
   // Contact Form 7
   wp_dequeue_style('contact-form-7');

   // DevVN Image Hotspot
   wp_dequeue_style('powertip');
   wp_dequeue_style('maps-points');

   // A3 Lazy Load
   wp_dequeue_style('jquery-lazyloadxt-spinner-css');

   // WooCommerce
   wp_dequeue_style('woocommerce-layout');
   wp_deregister_style('woocommerce-layout');
   wp_dequeue_style('woocommerce-smallscreen');
   wp_deregister_style('woocommerce-smallscreen');
   wp_dequeue_style('woocommerce-general');
   wp_deregister_style('woocommerce-general');
   wp_dequeue_style('wc-blocks-style');
   wp_deregister_style('wc-blocks-style');
   wp_dequeue_style('woocommerce-inline');
   wp_deregister_style('woocommerce-inline');
}, 99);

// Override query posts
add_action('pre_get_posts', function (WP_Query $query) {
   if (! $query->is_admin() && $query->is_main_query()) {
      if ($query->is_category() || $query->is_tag() || $query->is_home()) {
         $paged = max(get_query_var('paged'), 1);
         $posts_per_page = wp_is_mobile() ? MONA_POSTS_PER_PAGE : 9;

         $query->set('posts_per_page', $posts_per_page);
         $query->set('offset', ($paged - 1) * $posts_per_page);
      }
   }
}, 10, 1);

// Override body class
add_filter('mona_main_classes', function (array $classes) {
   if (is_page_template('page-template/template-policy.php')) {
      $classes[] = 'page-policy';
   } elseif (is_page_template('page-template/template-contact.php')) {
      $classes[] = 'page-contact';
   } elseif (is_page_template('page-template/template-about.php')) {
      $classes[] = 'page-about';
   } elseif (is_singular('post')) {
      $classes[] = 'page-news';
   } elseif (is_front_page()) {
      $classes[] = 'page-home';
   } elseif (is_page() && ! is_page_template() && ! is_front_page() && ! is_home()) {
      $classes[] = 'page-policy';
   }

   return $classes;
}, 10, 1);

add_action('template_redirect', function () {
   global $mona_current_permalink;

   $mona_current_permalink = mona_get_current_permalink();
});

// Shift+Enter tạo <br> thay vì <p> trong tất cả TinyMCE editor
add_filter('tiny_mce_before_init', function (array $settings) {
   $settings['newline_as_br']       = true;
   $settings['force_br_newlines']   = true;
   $settings['force_p_newlines']    = false;
   return $settings;
});

// Thông báo tiếng Việt cho CF7
add_filter('wpcf7_messages', function (array $messages) {
   $messages['mail_sent_ok']['default']           = 'Cảm ơn bạn! Tin nhắn của bạn đã được gửi thành công.';
   $messages['mail_sent_ng']['default']           = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
   $messages['validation_error']['default']       = 'Vui lòng kiểm tra lại các trường bên dưới.';
   $messages['spam']['default']                   = 'Tin nhắn của bạn bị đánh dấu là spam.';
   $messages['invalid_required']['default']       = 'Vui lòng điền vào trường này.';
   $messages['invalid_too_long']['default']       = 'Nội dung quá dài.';
   $messages['invalid_too_short']['default']      = 'Nội dung quá ngắn.';
   $messages['invalid_email']['default']          = 'Địa chỉ email không hợp lệ.';
   $messages['invalid_url']['default']            = 'URL không hợp lệ.';
   $messages['invalid_tel']['default']            = 'Số điện thoại không hợp lệ.';
   $messages['invalid_date']['default']           = 'Ngày không hợp lệ.';
   $messages['upload_failed']['default']          = 'Tải lên thất bại.';
   $messages['upload_file_type_invalid']['default'] = 'Loại file không được phép.';
   $messages['upload_file_too_large']['default']  = 'File vượt quá dung lượng cho phép.';
   return $messages;
});

// Gửi email HTML cho các form CF7 của site
add_filter('wpcf7_mail_components', function (array $components, $form) {
   $html_forms = ['Đăng ký khóa học', 'Đăng ký Workshop', 'Liên Hệ'];
   if (in_array($form->title(), $html_forms, true)) {
      $components['headers'] = 'Content-Type: text/html; charset=UTF-8' . "\r\n";
   }
   return $components;
}, 10, 2);

// Tắt WordPress Emoji JS
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
