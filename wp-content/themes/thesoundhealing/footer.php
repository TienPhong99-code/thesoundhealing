<?php

/**
 * The template for displaying footer.
 *
 * @package MONA.Media / Website
 */

if (!defined('ABSPATH')) {
    die();
}

?>
</main>

<?php if (!is_singular(['khoa_hoc', 'workshop', 'dich_vu'])) : ?>
    <?php get_template_part('partials/sections/footer'); ?>
<?php endif; ?>

<?php
// Modals — render ở body level, trên mọi z-index
get_template_part('partials/modals/modal-share');
if (is_singular('tuyen_dung')) {
    get_template_part('partials/modals/modal-ung-tuyen');
}
if (is_front_page()) {
    get_template_part('partials/sections/home/section', 'popup-du-an');
}
?>

<?php wp_footer(); ?>

<div class="zalo-chat-widget" data-oaid="2752431197352283024" data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width="" data-height=""></div>
<script src="https://sp.zalo.me/plugins/sdk.js"></script>
</body>

</html>